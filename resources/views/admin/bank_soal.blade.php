@extends('layouts.admin')

@section('title', 'Bank Soal Asesmen')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">Bank Soal Asesmen & Standarisasi Bobot</h2>
        <p class="page-subtitle">Daftar paket soal dengan preset terstandar 25 & 40 nomor (PG, Benar/Salah, Uraian) total bobot 100.</p>
    </div>
    <div>
        <button type="button" class="btn btn-primary btn-sm" onclick="openModal('modalTambahBank')">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Buat Paket Bank Soal
        </button>
    </div>
</div>

<div class="table-container">
    <table class="table-compact">
        <thead>
            <tr>
                <th style="width: 40px;">No.</th>
                <th>Kode Bank</th>
                <th>Mata Pelajaran</th>
                <th>Tingkat</th>
                <th>Jumlah Butir Soal</th>
                <th>Bobot Maksimal</th>
                <th>KKM</th>
                <th>Status</th>
                <th style="text-align: center; width: 170px;">Tindakan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bankList as $idx => $b)
            <tr>
                <td>{{ $idx + 1 }}</td>
                <td><strong>{{ $b->kode_bank }}</strong></td>
                <td>{{ $b->mataPelajaran->nama_mapel ?? '-' }}</td>
                <td><span class="badge badge-neutral">Tingkat {{ $b->tingkat }}</span></td>
                <td>
                    <strong style="color: var(--primary);">{{ $b->butirSoal->count() }} Butir</strong>
                    <span style="font-size: 11px; color: #64748b;">(Target: {{ $b->jumlah_soal_pg + $b->jumlah_soal_uraian }})</span>
                </td>
                <td><strong>{{ number_format($b->bobot_pg + $b->bobot_uraian, 0) }} Poin</strong></td>
                <td>{{ number_format($b->kkm, 0) }}</td>
                <td>
                    @if ($b->status === 'aktif')
                        <span class="badge badge-success">Aktif</span>
                    @else
                        <span class="badge badge-neutral">{{ ucfirst($b->status) }}</span>
                    @endif
                </td>
                <td style="text-align: center;">
                    <div style="display: flex; gap: 6px; justify-content: center;">
                        <a href="{{ route('admin.bank_soal.detail', $b->id) }}" class="btn btn-primary btn-sm">
                            Kelola Butir Soal
                        </a>
                        <form action="{{ route('admin.bank_soal.hapus', $b->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus bank soal {{ $b->kode_bank }} beserta seluruh butir soalnya?');">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center; padding: 24px; color: #64748b;">
                    Belum ada bank soal. Gunakan tombol 'Buat Paket Bank Soal' di atas.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Tambah Bank Soal -->
<div id="modalTambahBank" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15,23,42,0.6); z-index: 999; align-items: center; justify-content: center;">
    <div style="background: #ffffff; border-radius: var(--radius); width: 95%; max-width: 520px; padding: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--primary);">Buat Paket Bank Soal Baru</h3>
            <button type="button" onclick="closeModal('modalTambahBank')" style="background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
        </div>
        <form action="{{ route('admin.bank_soal.simpan') }}" method="POST">
            @csrf
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Kode Bank Soal (Unik) *</label>
                <input type="text" name="kode_bank" required placeholder="Contoh: BS-ASJ-XI-2026" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
            </div>
            <div style="display: grid; grid-template-columns: 1.3fr 1fr; gap: 12px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Mata Pelajaran *</label>
                    <select name="mata_pelajaran_id" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                        @foreach ($mapelList as $m)
                            <option value="{{ $m->id }}">{{ $m->nama_mapel }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Tingkat Sasaran *</label>
                    <select name="tingkat" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                        <option value="Semua">Semua Tingkat</option>
                        <option value="10">Kelas 10</option>
                        <option value="11">Kelas 11</option>
                        <option value="12">Kelas 12</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Standar Preset Soal (Roadmap TKA Pusmendik) *</label>
                <select name="standar_preset" id="presetSelect" onchange="updatePresetInfo()" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                    <option value="25">Standar 25 Nomor (5 B/S x 4 = 20, 15 PG x 2 = 30, 5 Uraian x 10 = 50 &bull; Total: 100)</option>
                    <option value="40">Standar 40 Nomor (10 B/S x 1 = 10, 20 PG x 2 = 40, 10 Uraian x 5 = 50 &bull; Total: 100)</option>
                    <option value="kustom">Mode Kustom (25 PG Bebas &bull; Bobot 4 per Soal &bull; Total: 100)</option>
                </select>
            </div>

            <div style="background: #f8fafc; border: 1px solid var(--border-light); padding: 10px 14px; border-radius: var(--radius-sm); margin-bottom: 16px; font-size: 12px; color: #475569;" id="presetInfoBox">
                Total bobot dihitung otomatis tepat <strong>100 Poin</strong> sesuai rumus standarisasi asesmen sekolah.
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Kriteria Ketuntasan Minimal (KKM) *</label>
                <input type="number" name="kkm" value="75" required min="0" max="100" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeModal('modalTambahBank')">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm">Simpan & Kelola Butir Soal</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openModal(id) { document.getElementById(id).style.display = 'flex'; }
    function closeModal(id) { document.getElementById(id).style.display = 'none'; }
    function updatePresetInfo() {
        const val = document.getElementById('presetSelect').value;
        const box = document.getElementById('presetInfoBox');
        if (val === '25') {
            box.innerHTML = '<strong>Standar 25 Nomor:</strong> 5 Benar/Salah (bobot 4 = 20) + 15 PG (bobot 2 = 30) + 5 Uraian (bobot 10 = 50). Total bobot: <strong>100 Poin</strong>.';
        } else if (val === '40') {
            box.innerHTML = '<strong>Standar 40 Nomor:</strong> 10 Benar/Salah (bobot 1 = 10) + 20 PG (bobot 2 = 40) + 10 Uraian (bobot 5 = 50). Total bobot: <strong>100 Poin</strong>.';
        } else {
            box.innerHTML = '<strong>Mode Kustom:</strong> 25 Butir Soal Pilihan Ganda (bobot 4.0 per soal). Total bobot: <strong>100 Poin</strong>.';
        }
    }
</script>
@endsection
