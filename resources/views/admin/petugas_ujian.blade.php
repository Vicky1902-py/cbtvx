@extends('layouts.admin')

@section('title', 'Panitia & Petugas Pengawas Ujian')

@section('content')
<div class="page-header">
    <div>
        <div style="font-size: 11px; color: var(--accent); font-weight: 700; text-transform: uppercase;">Administrasi Pelaksanaan Asesmen</div>
        <h2 class="page-title">Panitia & Petugas Pengawas Ujian</h2>
        <p class="page-subtitle">Kelola seluruh penugasan panitia (Ketua, Sekretaris, Bendahara, Teknisi, Koordinator) dan pengawas ruang CBT, serta cetak kartu tanda pengenal dan daftar hadir presensi resmi A4.</p>
    </div>
    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
        <a href="{{ route('admin.cetak_daftar_hadir_petugas') }}" target="_blank" class="btn btn-secondary btn-sm">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            Cetak Daftar Hadir Petugas A4
        </a>
        <a href="{{ route('admin.cetak_kartu_petugas') }}" target="_blank" class="btn btn-primary btn-sm">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
            Cetak Kartu Tanda Pengenal A4
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
                <th>Jabatan / Jenis Penugasan</th>
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
                    @php
                        $roleLower = strtolower($p->jenis_petugas ?? '');
                        $badgeColor = '#334155';
                        $badgeBg = '#f1f5f9';
                        $borderCol = '#cbd5e1';
                        if (str_contains($roleLower, 'ketua') || str_contains($roleLower, 'penanggung')) {
                            $badgeColor = '#1e3a8a'; $badgeBg = '#dbeafe'; $borderCol = '#93c5fd';
                        } elseif (str_contains($roleLower, 'sekretaris') || str_contains($roleLower, 'bendahara')) {
                            $badgeColor = '#6b21a8'; $badgeBg = '#f3e8ff'; $borderCol = '#d8b4fe';
                        } elseif (str_contains($roleLower, 'proktor')) {
                            $badgeColor = '#92400e'; $badgeBg = '#fef3c7'; $borderCol = '#fcd34d';
                        } elseif (str_contains($roleLower, 'teknisi')) {
                            $badgeColor = '#0369a1'; $badgeBg = '#e0f2fe'; $borderCol = '#7dd3fc';
                        } elseif (str_contains($roleLower, 'pengawas')) {
                            $badgeColor = '#065f46'; $badgeBg = '#d1fae5'; $borderCol = '#6ee7b7';
                        } elseif (str_contains($roleLower, 'koordinator')) {
                            $badgeColor = '#0f766e'; $badgeBg = '#ccfbf1'; $borderCol = '#5eead4';
                        }
                    @endphp
                    <span class="badge" style="background: {{ $badgeBg }}; color: {{ $badgeColor }}; border: 1px solid {{ $borderCol }}; font-weight: 700; font-size: 11px; padding: 4px 8px;">
                        {{ strtoupper($p->jenis_petugas) }}
                    </span>
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
                        <button type="button" class="btn btn-warning btn-sm" onclick="editPetugas({{ json_encode($p) }})" title="Ubah Data Penugasan">
                            Edit
                        </button>
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
                    Belum ada panitia atau petugas pengawas yang ditugaskan. Klik tombol 'Tambah Penugasan Petugas' di atas.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- MODAL TAMBAH PENUGASAN PETUGAS -->
<div id="modalTambahPetugas" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15,23,42,0.6); z-index: 999; align-items: center; justify-content: center;">
    <div style="background: #ffffff; border-radius: var(--radius); width: 95%; max-width: 520px; padding: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--primary);">Tambah Penugasan Panitia / Pengawas</h3>
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

            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Peran / Jenis Penugasan *</label>
                <select id="select_tugas_tambah" onchange="onRoleSelectChange('tambah')" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px; font-weight: 600;">
                    <option value="Pengawas Ruang">Pengawas Ruang</option>
                    <option value="Proktor CBT">Proktor CBT</option>
                    <option value="Teknisi CBT">Teknisi CBT / Lab</option>
                    <option value="Ketua Panitia">Ketua Panitia</option>
                    <option value="Sekretaris">Sekretaris Panitia</option>
                    <option value="Bendahara">Bendahara Panitia</option>
                    <option value="Koordinator Pengawas">Koordinator Pengawas</option>
                    <option value="Pembantu Umum">Pembantu Umum / Logistik</option>
                    <option value="Penyusun Naskah Soal">Penyusun Naskah Soal</option>
                    <option value="__custom__">+ Ketik Jabatan / Tugas Lainnya...</option>
                </select>

                <div id="box_custom_tambah" style="display: none; margin-top: 8px;">
                    <input type="text" id="custom_tugas_tambah" oninput="syncCustomRole('tambah')" placeholder="Ketik jenis jabatan/tugas bebas di sini..." style="width: 100%; padding: 8px 10px; border: 2px solid var(--accent); border-radius: var(--radius-sm); font-size: 13px; background: #f0f9ff;">
                </div>

                <input type="hidden" name="jenis_petugas" id="tambah_jenis_petugas" value="Pengawas Ruang">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Ruangan Ujian</label>
                    <input type="text" name="ruangan" value="Lab Komputer 1" placeholder="Contoh: Lab Komputer 1" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Sesi Ujian</label>
                    <input type="text" name="sesi" value="Sesi 1" placeholder="Contoh: Sesi 1 atau Semua Sesi" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
            </div>

            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Nomor SK Penugasan</label>
                <input type="text" name="nomor_sk" value="421.5/012/SMKN1.KB/2026" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
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

<!-- MODAL EDIT PENUGASAN PETUGAS -->
<div id="modalEditPetugas" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15,23,42,0.6); z-index: 999; align-items: center; justify-content: center;">
    <div style="background: #ffffff; border-radius: var(--radius); width: 95%; max-width: 520px; padding: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--primary);">Edit Penugasan Panitia / Pengawas</h3>
            <button type="button" onclick="closeModal('modalEditPetugas')" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #64748b;">&times;</button>
        </div>

        <form id="formEditPetugas" action="" method="POST">
            @csrf

            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Pilih Guru / Staf Sekolah *</label>
                <select name="user_id" id="edit_user_id" required style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                    @foreach ($guruList as $g)
                        <option value="{{ $g->id }}">
                            {{ $g->name }} ({{ $g->nip ? 'NIP: ' . $g->nip : 'User: ' . $g->username }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Peran / Jenis Penugasan *</label>
                <select id="select_tugas_edit" onchange="onRoleSelectChange('edit')" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px; font-weight: 600;">
                    <option value="Pengawas Ruang">Pengawas Ruang</option>
                    <option value="Proktor CBT">Proktor CBT</option>
                    <option value="Teknisi CBT">Teknisi CBT / Lab</option>
                    <option value="Ketua Panitia">Ketua Panitia</option>
                    <option value="Sekretaris">Sekretaris Panitia</option>
                    <option value="Bendahara">Bendahara Panitia</option>
                    <option value="Koordinator Pengawas">Koordinator Pengawas</option>
                    <option value="Pembantu Umum">Pembantu Umum / Logistik</option>
                    <option value="Penyusun Naskah Soal">Penyusun Naskah Soal</option>
                    <option value="__custom__">+ Ketik Jabatan / Tugas Lainnya...</option>
                </select>

                <div id="box_custom_edit" style="display: none; margin-top: 8px;">
                    <input type="text" id="custom_tugas_edit" oninput="syncCustomRole('edit')" placeholder="Ketik jenis jabatan/tugas bebas di sini..." style="width: 100%; padding: 8px 10px; border: 2px solid var(--accent); border-radius: var(--radius-sm); font-size: 13px; background: #f0f9ff;">
                </div>

                <input type="hidden" name="jenis_petugas" id="edit_jenis_petugas" value="Pengawas Ruang">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Ruangan Ujian</label>
                    <input type="text" name="ruangan" id="edit_ruangan" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Sesi Ujian</label>
                    <input type="text" name="sesi" id="edit_sesi" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
            </div>

            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Nomor SK Penugasan</label>
                <input type="text" name="nomor_sk" id="edit_nomor_sk" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Jadwal Ujian Terkait (Opsional)</label>
                <select name="jadwal_ujian_id" id="edit_jadwal_ujian_id" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                    <option value="">-- Berlaku untuk Semua Jadwal Ujian --</option>
                    @foreach ($jadwalList as $j)
                        <option value="{{ $j->id }}">{{ $j->nama_ujian }}</option>
                    @endforeach
                </select>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 8px; border-top: 1px solid var(--border-light); padding-top: 14px;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditPetugas')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openModal(id) {
        const m = document.getElementById(id);
        if (m) m.style.display = 'flex';
    }

    function closeModal(id) {
        const m = document.getElementById(id);
        if (m) m.style.display = 'none';
    }

    function onRoleSelectChange(mode) {
        const select = document.getElementById('select_tugas_' + mode);
        const customBox = document.getElementById('box_custom_' + mode);
        const customInput = document.getElementById('custom_tugas_' + mode);
        const hiddenInput = document.getElementById(mode + '_jenis_petugas');

        if (select.value === '__custom__') {
            customBox.style.display = 'block';
            customInput.focus();
            hiddenInput.value = customInput.value.trim() || 'Petugas Ujian';
        } else {
            customBox.style.display = 'none';
            hiddenInput.value = select.value;
        }
    }

    function syncCustomRole(mode) {
        const customInput = document.getElementById('custom_tugas_' + mode);
        const hiddenInput = document.getElementById(mode + '_jenis_petugas');
        hiddenInput.value = customInput.value.trim() || 'Petugas Ujian';
    }

    function editPetugas(p) {
        const form = document.getElementById('formEditPetugas');
        form.action = '/admin/petugas-ujian/' + p.id + '/update';
        document.getElementById('edit_user_id').value = p.user_id;

        const select = document.getElementById('select_tugas_edit');
        const customBox = document.getElementById('box_custom_edit');
        const customInput = document.getElementById('custom_tugas_edit');
        const hiddenInput = document.getElementById('edit_jenis_petugas');

        hiddenInput.value = p.jenis_petugas;

        let matched = false;
        for (let i = 0; i < select.options.length; i++) {
            if (select.options[i].value.toLowerCase() === (p.jenis_petugas || '').toLowerCase()) {
                select.selectedIndex = i;
                matched = true;
                break;
            }
        }

        if (!matched) {
            select.value = '__custom__';
            customBox.style.display = 'block';
            customInput.value = p.jenis_petugas;
        } else {
            customBox.style.display = 'none';
            customInput.value = '';
        }

        document.getElementById('edit_ruangan').value = p.ruangan;
        document.getElementById('edit_sesi').value = p.sesi;
        document.getElementById('edit_nomor_sk').value = p.nomor_sk || '';
        document.getElementById('edit_jadwal_ujian_id').value = p.jadwal_ujian_id || '';
        openModal('modalEditPetugas');
    }
</script>
@endsection
