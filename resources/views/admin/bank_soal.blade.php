@extends('layouts.admin')

@section('title', 'Bank Soal Asesmen')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">Bank Soal Asesmen & Standarisasi Bobot</h2>
        <p class="page-subtitle">Daftar paket soal dengan preset standar 25 & 40 nomor (Pusmendik TKA/ANBK) berbobot total 100 poin.</p>
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
                <th>Kode & Nama Bank Soal</th>
                <th>Mata Pelajaran & Jurusan</th>
                <th>Guru Pembuat</th>
                <th>Standar Preset</th>
                <th>Rincian Butir (PG / B-S / Uraian)</th>
                <th>Total Bobot Poin</th>
                <th>Status</th>
                <th style="text-align: center; width: 220px;">Tindakan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bankList as $idx => $b)
            @php
                $soals = $b->butirSoal;
                $cPg = $soals->where('jenis_soal', 'pg')->count();
                $cBs = $soals->where('jenis_soal', 'benar_salah')->count();
                $cUraian = $soals->where('jenis_soal', 'uraian')->count();
                $totalBobot = (float) $soals->sum('bobot');
            @endphp
            <tr>
                <td>{{ $idx + 1 }}</td>
                <td>
                    <strong style="color: var(--primary);">{{ $b->kode_bank }}</strong>
                    <div style="font-size: 11.5px; color: #475569;">{{ $b->nama_bank ?: ($b->mataPelajaran->nama_mapel ?? '-') }}</div>
                </td>
                <td>
                    <div style="font-weight: 600; font-size: 13px;">{{ $b->mataPelajaran->nama_mapel ?? '-' }}</div>
                    <div style="font-size: 11px; color: #0284c7;">
                        {{ $b->mataPelajaran->jurusan ?? 'Semua Jurusan' }} &bull; Tingkat {{ $b->tingkat }}
                    </div>
                </td>
                <td>
                    <div style="font-size: 12px; font-weight: 600;">{{ $b->guru ? $b->guru->name : ($b->mataPelajaran->guru->name ?? 'Admin') }}</div>
                    <div style="font-size: 11px; color: #64748b;">KKM: {{ number_format($b->kkm, 0) }}</div>
                </td>
                <td>
                    @if ($b->standar_bobot === '25_soal')
                        <span class="badge badge-info" style="font-size: 11px;">Standar 25 Nomor</span>
                    @elseif ($b->standar_bobot === '40_soal')
                        <span class="badge badge-warning" style="font-size: 11px;">Standar 40 Nomor</span>
                    @else
                        <span class="badge badge-neutral" style="font-size: 11px;">Mode Kustom</span>
                    @endif
                </td>
                <td>
                    <div style="display: flex; gap: 4px; flex-wrap: wrap;">
                        <span class="badge badge-info" style="font-size: 10.5px;" title="Pilihan Ganda">
                            PG: {{ $cPg }}/{{ $b->jumlah_soal_pg }}
                        </span>
                        <span class="badge badge-warning" style="font-size: 10.5px;" title="Benar / Salah">
                            B/S: {{ $cBs }}/{{ $b->jumlah_soal_bs ?? 0 }}
                        </span>
                        <span class="badge badge-neutral" style="font-size: 10.5px;" title="Uraian">
                            Uraian: {{ $cUraian }}/{{ $b->jumlah_soal_uraian }}
                        </span>
                    </div>
                    <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                        Total: <strong>{{ $soals->count() }}</strong> butir
                    </div>
                </td>
                <td>
                    @if ($totalBobot == 100.0)
                        <span class="badge badge-success" style="font-size: 12px; font-weight: 700;">
                            100 / 100 Poin &check;
                        </span>
                    @elseif ($totalBobot > 100.0)
                        <span class="badge badge-danger" style="font-size: 11.5px;">
                            {{ $totalBobot }} / 100 Poin (Lebih)
                        </span>
                    @else
                        <span class="badge badge-warning" style="font-size: 11.5px;">
                            {{ $totalBobot }} / 100 Poin (Kurang)
                        </span>
                    @endif
                </td>
                <td>
                    @if ($b->status === 'aktif')
                        <span class="badge badge-success">Aktif</span>
                    @else
                        <span class="badge badge-neutral">{{ ucfirst($b->status) }}</span>
                    @endif
                </td>
                <td style="text-align: center;">
                    <div style="display: flex; gap: 4px; justify-content: center; flex-wrap: wrap;">
                        <a href="{{ route('admin.bank_soal.detail', $b->id) }}" class="btn btn-primary btn-sm" style="padding: 4px 8px; font-size: 11.5px;">
                            Kelola Butir Soal
                        </a>
                        <a href="{{ route('admin.bank_soal.pratinjau', $b->id) }}" class="btn btn-secondary btn-sm" style="padding: 4px 8px; font-size: 11.5px;" title="Pratinjau antarmuka ujian siswa ANBK" target="_blank">
                            Pratinjau
                        </a>
                        <a href="{{ route('admin.bank_soal.export_xlsx', $b->id) }}" class="btn btn-secondary btn-sm" style="padding: 4px 8px; font-size: 11.5px;" title="Ekspor butir soal ke Excel">
                            XLSX
                        </a>
                        <form action="{{ route('admin.bank_soal.hapus', $b->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus bank soal {{ $b->kode_bank }} beserta seluruh butir soalnya?');">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm" style="padding: 4px 8px; font-size: 11.5px;">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center; padding: 32px; color: #64748b;">
                    Belum ada bank soal. Klik tombol <strong>'Buat Paket Bank Soal'</strong> di atas untuk membuat paket asesmen baru.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Tambah Bank Soal -->
<div id="modalTambahBank" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15,23,42,0.6); z-index: 999; align-items: center; justify-content: center;">
    <div style="background: #ffffff; border-radius: var(--radius); width: 95%; max-width: 600px; padding: 24px; max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--primary);">Buat Paket Bank Soal Baru</h3>
            <button type="button" onclick="closeModal('modalTambahBank')" style="background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
        </div>
        <form action="{{ route('admin.bank_soal.simpan') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 12px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Kode Bank Soal (Unik) *</label>
                    <input type="text" name="kode_bank" required placeholder="Contoh: BS-MTK-DPIB-X" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Nama Paket Asesmen</label>
                    <input type="text" name="nama_bank" placeholder="Contoh: Asesmen Sumatif Ganjil 2026/2027" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
            </div>

            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Mata Pelajaran (Mengikuti Input Mapel & Guru Pengampu) *</label>
                <select name="mata_pelajaran_id" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 12.5px;">
                    <option value="">-- Pilih Mata Pelajaran --</option>
                    @foreach ($mapelList as $m)
                        <option value="{{ $m->id }}">
                            [{{ $m->kode_mapel }}] {{ $m->nama_mapel }} &mdash; {{ $m->jurusan }} ({{ $m->tingkat === 'Semua' ? 'Lintas Tingkat' : 'Kls ' . $m->tingkat }}) &bull; Pengampu: {{ $m->guru ? $m->guru->name : 'Belum Ada' }}
                        </option>
                    @endforeach
                </select>
                <div style="font-size: 11px; color: #64748b; margin-top: 3px;">
                    Pilihan mapel otomatis menampilkan kode, jurusan sasaran, dan guru pengampu Dapodik yang berwenang.
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Tingkat Sasaran *</label>
                    <select name="tingkat" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                        <option value="Semua">Semua Tingkat (Lintas Kelas)</option>
                        <option value="10">Kelas 10 (Fase E)</option>
                        <option value="11">Kelas 11 (Fase F)</option>
                        <option value="12">Kelas 12 (Fase F)</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">KKM (Kriteria Ketuntasan) *</label>
                    <input type="number" name="kkm" value="75" required min="0" max="100" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
            </div>

            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Standar Preset Soal (Roadmap TKA Pusmendik) *</label>
                <select name="standar_preset" id="presetSelect" onchange="updatePresetInfo()" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                    <option value="25">Standar 25 Nomor (5 B/S x 4 = 20, 15 PG x 2 = 30, 5 Uraian x 10 = 50 &bull; Total: 100 Poin)</option>
                    <option value="40">Standar 40 Nomor (10 B/S x 1 = 10, 20 PG x 2 = 40, 10 Uraian x 5 = 50 &bull; Total: 100 Poin)</option>
                    <option value="kustom">Mode Kustom (25 Soal PG @ 4 Poin &bull; Total: 100 Poin)</option>
                </select>
            </div>

            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 12px 14px; border-radius: var(--radius-sm); margin-bottom: 20px; font-size: 12px; color: #166534;" id="presetInfoBox">
                <strong>Standar 25 Nomor:</strong> Komposisi 5 Benar/Salah (bobot 4 = 20 poin) + 15 Pilihan Ganda (bobot 2 = 30 poin) + 5 Uraian (bobot 10 = 50 poin). Akumulasi total: <strong>100 Poin</strong>.
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeModal('modalTambahBank')">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm">Simpan & Mulai Kelola Soal</button>
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
            box.innerHTML = '<strong>Standar 25 Nomor:</strong> 5 Benar/Salah (bobot 4 = 20 poin) + 15 Pilihan Ganda (bobot 2 = 30 poin) + 5 Uraian (bobot 10 = 50 poin). Akumulasi total: <strong>100 Poin</strong>.';
        } else if (val === '40') {
            box.innerHTML = '<strong>Standar 40 Nomor:</strong> 10 Benar/Salah (bobot 1 = 10 poin) + 20 Pilihan Ganda (bobot 2 = 40 poin) + 10 Uraian (bobot 5 = 50 poin). Akumulasi total: <strong>100 Poin</strong>.';
        } else {
            box.innerHTML = '<strong>Mode Kustom:</strong> Guru bebas menentukan komposisi butir soal dan bobot per butir hingga mencapai target 100 Poin.';
        }
    }
</script>
@endsection
