@extends('layouts.admin')

@section('title', 'Backup & Pemulihan Database & Bank Soal')

@section('content')
<div class="page-header">
    <div>
        <div style="font-size: 11px; color: var(--accent); font-weight: 700; text-transform: uppercase;">Pemeliharaan Sistem & Keamanan Data</div>
        <h2 class="page-title">Backup & Pemulihan Database & Bank Soal</h2>
        <p class="page-subtitle">Amankan seluruh data ujian, master siswa, jadwal, dan paket butir soal ke dalam arsip cadangan mandiri untuk mitigasi risiko.</p>
    </div>
    <div style="display: flex; gap: 8px;">
        <form action="{{ route('admin.backup.database') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary btn-sm">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                Cadangkan Database Sekarang
            </button>
        </form>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
    <!-- CARD 1: INFORMASI BACKUP DATABASE -->
    <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 20px;">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
            <div style="background: #e0f2fe; color: #0284c7; padding: 8px; border-radius: 8px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg>
            </div>
            <div>
                <h3 style="font-size: 15px; font-weight: 700; color: var(--primary);">Cadangan Database SQL Penuh</h3>
                <p style="font-size: 12px; color: var(--text-muted);">Mencakup seluruh tabel: siswa, nilai, jadwal, bank soal, dan pengaturan dinas.</p>
            </div>
        </div>

        <p style="font-size: 12.5px; color: #475569; line-height: 1.6; margin-bottom: 16px;">
            Klik tombol di bawah ini untuk membuat salinan berkas cadangan database (.sql) yang kompatibel untuk dipulihkan baik di server lokal maupun cPanel hosting MySQL.
        </p>

        <form action="{{ route('admin.backup.database') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                Buat Salinan Database SQL Sekarang
            </button>
        </form>
    </div>

    <!-- CARD 2: RESTORE / IMPOR BANK SOAL -->
    <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 20px;">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
            <div style="background: #fef3c7; color: #d97706; padding: 8px; border-radius: 8px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
            </div>
            <div>
                <h3 style="font-size: 15px; font-weight: 700; color: var(--primary);">Pulihkan / Impor Bank Soal (.JSON)</h3>
                <p style="font-size: 12px; color: var(--text-muted);">Impor paket bank soal cadangan lengkap dengan pertanyaan, opsi, dan bobot.</p>
            </div>
        </div>

        <form action="{{ route('admin.backup.bank_soal.import_json') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 6px;">Pilih Berkas JSON Cadangan Bank Soal:</label>
                <input type="file" name="file_json" accept=".json" required style="width: 100%; padding: 8px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
            </div>

            <button type="submit" class="btn btn-accent" style="width: 100%; justify-content: center;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 16 12 12 8 16"></polyline><line x1="12" y1="12" x2="12" y2="21"></line><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"></path></svg>
                Pulihkan / Impor Paket Soal
            </button>
        </form>
    </div>
</div>

<!-- SECTION 1: DAFTAR BERKAS CADANGAN DATABASE DI SERVER -->
<div style="margin-bottom: 28px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
        <h3 style="font-size: 16px; font-weight: 700; color: var(--primary);">Riwayat Arsip Cadangan Database di Server</h3>
        <span style="font-size: 12px; color: var(--text-muted);">Lokasi: <code>storage/app/backups/</code></span>
    </div>

    <div class="table-container">
        <table class="table-compact">
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;">No.</th>
                    <th>Nama Berkas Cadangan</th>
                    <th>Ukuran Berkas</th>
                    <th>Waktu Pembuatan</th>
                    <th style="text-align: center; width: 160px;">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($backupList as $idx => $b)
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td>
                        <strong style="color: var(--primary);">{{ $b['name'] }}</strong>
                    </td>
                    <td><code>{{ $b['size'] }}</code></td>
                    <td>{{ $b['date'] }}</td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 6px; justify-content: center;">
                            <a href="{{ route('admin.backup.database.unduh', $b['name']) }}" class="btn btn-primary btn-sm" title="Unduh Berkas ke Komputer">
                                Unduh
                            </a>
                            <form action="{{ route('admin.backup.database.hapus', $b['name']) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus berkas cadangan {{ $b['name'] }}?');">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 24px; color: #64748b;">
                        Belum ada berkas cadangan database yang tersimpan di server. Klik 'Cadangkan Database Sekarang' di atas.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- SECTION 2: DAFTAR BANK SOAL UNTUK DI-EKSPOR / DIBACKUP -->
<div>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
        <h3 style="font-size: 16px; font-weight: 700; color: var(--primary);">Cadangkan Paket Bank Soal Mandiri (.JSON)</h3>
        <span style="font-size: 12px; color: var(--text-muted);">Total: {{ $bankSoalList->count() }} Paket Soal</span>
    </div>

    <div class="table-container">
        <table class="table-compact">
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;">No.</th>
                    <th>Kode & Nama Paket Soal</th>
                    <th>Mata Pelajaran & Jurusan</th>
                    <th>Standar Preset</th>
                    <th>Jumlah Butir Soal</th>
                    <th>KKM</th>
                    <th style="text-align: center; width: 160px;">Ekspor Cadangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bankSoalList as $idx => $bank)
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td>
                        <strong style="color: var(--primary);">{{ $bank->nama_bank }}</strong>
                        <div style="font-size: 11px; color: var(--text-muted);">Kode: <code>{{ $bank->kode_bank }}</code></div>
                    </td>
                    <td>
                        {{ $bank->mataPelajaran->nama_mapel ?? 'Semua Mapel' }}
                        <div style="font-size: 11px; color: var(--text-muted);">{{ $bank->mataPelajaran->jurusan ?? 'Semua' }}</div>
                    </td>
                    <td>
                        @if ($bank->standar_preset == 25)
                            <span class="badge badge-info">Standar 25</span>
                        @else
                            <span class="badge badge-warning">Standar 40</span>
                        @endif
                    </td>
                    <td><strong>{{ $bank->soal_butir_count }} Butir</strong></td>
                    <td>{{ $bank->kkm }}</td>
                    <td style="text-align: center;">
                        <a href="{{ route('admin.backup.bank_soal.export_json', $bank->id) }}" class="btn btn-secondary btn-sm" title="Unduh Paket Soal Lengkap (.JSON)">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                            Backup (.JSON)
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 24px; color: #64748b;">
                        Belum ada Bank Soal yang dibuat di sistem.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
