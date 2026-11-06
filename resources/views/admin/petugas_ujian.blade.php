@extends('layouts.admin')

@section('title', 'Penugasan Proktor & Pengawas')

@section('content')
<div class="page-header">
    <div>
        <div style="font-size: 11px; color: var(--accent); font-weight: 700; text-transform: uppercase;">Administrasi Pelaksanaan Asesmen</div>
        <h2 class="page-title">Penugasan Proktor & Pengawas Ujian</h2>
        <p class="page-subtitle">Tentukan petugas proktor dan pengawas ruang untuk asesmen CBT, serta cetak kartu tanda pengenal dinas resmi A4.</p>
    </div>
    <div style="display: flex; gap: 8px;">
        <a href="{{ route('admin.cetak_kartu_petugas') }}" target="_blank" class="btn btn-primary btn-sm">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
            Cetak Kartu Proktor & Pengawas A4
        </a>
        <button type="button" class="btn btn-accent btn-sm" onclick="openModal('modalTambahPetugas')">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Tambah Penugasan Petugas
        </button>
    </div>
</div>

@if (session('success'))
<div style="background: var(--success-bg); border-left: 4px solid var(--success); padding: 12px 16px; border-radius: var(--radius-sm); margin-bottom: 20px; font-size: 13px; color: #166534;">
    {{ session('success') }}
</div>
@endif

<div class="table-container">
    <table class="table-compact">
        <thead>
            <tr>
                <th style="width: 40px;">No.</th>
                <th>Nama Lengkap Petugas</th>
                <th>NIP / NUPTK</th>
                <th>Jenis Tugas</th>
                <th>Ruangan & Sesi</th>
                <th>Nomor SK Tugas</th>
                <th>Jadwal Terkait</th>
                <th style="text-align: center; width: 140px;">Tindakan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($petugasList as $idx => $p)
            <tr>
                <td>{{ $idx + 1 }}</td>
                <td>
                    <strong style="color: var(--primary);">{{ $p->user->name ?? '-' }}</strong>
                    <div style="font-size: 11px; color: var(--text-muted);">{{ $p->user->email ?? '' }}</div>
                </td>
                <td>
                    <code>{{ $p->user->nip ?: ($p->user->nuptk ?: $p->user->username) }}</code>
                </td>
                <td>
                    @if ($p->jenis_petugas === 'proktor')
                        <span class="badge badge-warning" style="font-weight: 700;">PROKTOR CBT</span>
                    @else
                        <span class="badge badge-info" style="font-weight: 700;">PENGAWAS RUANG</span>
                    @endif
                </td>
                <td>
                    <strong>{{ $p->ruangan }}</strong> &bull; <span style="color: var(--accent);">{{ $p->sesi }}</span>
                </td>
                <td>
                    <span style="font-size: 11.5px; color: #475569;">{{ $p->nomor_sk ?: '-' }}</span>
                </td>
                <td>
                    {{ $p->jadwal->nama_ujian ?? 'Semua Jadwal Aktif' }}
                </td>
                <td style="text-align: center;">
                    <div style="display: flex; gap: 6px; justify-content: center;">
                        <a href="{{ route('admin.cetak_kartu_petugas', ['petugas_id' => $p->id]) }}" target="_blank" class="btn btn-secondary btn-sm" title="Cetak Kartu ID">
                            Cetak
                        </a>
                        <form action="{{ route('admin.petugas_ujian.hapus', $p->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus penugasan {{ $p->user->name ?? '' }}?');">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 32px; color: #64748b;">
                    Belum ada petugas proktor atau pengawas yang ditugaskan. Klik tombol 'Tambah Penugasan Petugas' di atas.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- MODAL TAMBAH PENUGASAN PETUGAS -->
<div id="modalTambahPetugas" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15,23,42,0.6); z-index: 999; align-items: center; justify-content: center;">
    <div style="background: #ffffff; border-radius: var(--radius); width: 95%; max-width: 520px; padding: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--primary);">Penugasan Proktor & Pengawas</h3>
            <button type="button" onclick="closeModal('modalTambahPetugas')" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #64748b;">&times;</button>
        </div>

        <form action="{{ route('admin.petugas_ujian.simpan') }}" method="POST">
            @csrf

            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Pilih Guru / Staf Sekolah *</label>
                <select name="user_id" required style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                    <option value="">-- Pilih dari Daftar Guru Dapodik --</option>
                    @foreach ($guruList as $g)
                        <option value="{{ $g->id }}">
                            {{ $g->name }} ({{ $g->nip ? 'NIP: ' . $g->nip : 'User: ' . $g->username }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Peran / Tugas *</label>
                    <select name="jenis_petugas" required style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                        <option value="pengawas">Pengawas Ruang</option>
                        <option value="proktor">Proktor CBT</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Ruangan Ujian *</label>
                    <input type="text" name="ruangan" required value="Lab Komputer 1" placeholder="Contoh: Lab Komputer 1" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Sesi Ujian *</label>
                    <input type="text" name="sesi" required value="Sesi 1" placeholder="Contoh: Sesi 1" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Nomor SK Penugasan</label>
                    <input type="text" name="nomor_sk" value="421.5/012/SMKN1.KB/2026" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Jadwal Ujian Terkait (Opsional)</label>
                <select name="jadwal_ujian_id" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                    <option value="">-- Berlaku untuk Semua Jadwal Ujian --</option>
                    @foreach ($jadwalList as $j)
                        <option value="{{ $j->id }}">{{ $j->nama_ujian }}</option>
                    @endforeach
                </select>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 8px; border-top: 1px solid var(--border-light); padding-top: 14px;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalTambahPetugas')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Penugasan</button>
            </div>
        </form>
    </div>
</div>
@endsection
