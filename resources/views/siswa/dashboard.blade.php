<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Asesmen Siswa &bull; SMKN 1 Kupang Barat</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <style>
        :root {
            --primary: #0f2b48;
            --primary-dark: #07172c;
            --accent: #0077b6;
            --accent-hover: #005b8c;
            --bg-page: #f8fafc;
            --surface: #ffffff;
            --border-light: #e2e8f0;
            --border: #cbd5e1;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --radius: 6px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
            background: var(--bg-page);
            color: var(--text-main);
            font-size: 14px;
            line-height: 1.5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top Bar Standar ANBK */
        .portal-header {
            background: var(--primary);
            color: #ffffff;
            padding: 10px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #0284c7;
        }

        .header-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-brand img {
            width: 38px;
            height: 38px;
            object-fit: contain;
        }

        .header-brand h1 {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        .header-brand p {
            font-size: 11px;
            color: #94a3b8;
        }

        .header-user {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-tag {
            background: rgba(255, 255, 255, 0.12);
            padding: 6px 14px;
            border-radius: var(--radius);
            font-size: 12.5px;
            font-weight: 600;
        }

        .btn-logout {
            background: #ef4444;
            color: #ffffff;
            border: none;
            padding: 6px 14px;
            border-radius: var(--radius);
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }

        .btn-logout:hover {
            background: #dc2626;
        }

        /* Container Konten */
        .main-container {
            max-width: 1040px;
            width: 100%;
            margin: 24px auto;
            padding: 0 16px;
            flex: 1;
        }

        /* Hero Banner */
        .welcome-hero {
            background: linear-gradient(135deg, #092543 0%, #0077b6 100%);
            color: #ffffff;
            border-radius: var(--radius);
            padding: 24px 28px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 12px rgba(15, 43, 72, 0.15);
        }

        .hero-left h2 {
            font-size: 20px;
            font-weight: 700;
        }

        .hero-left p {
            font-size: 13px;
            color: #e0f2fe;
            margin-top: 4px;
        }

        .hero-badge {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.25);
            padding: 8px 16px;
            border-radius: var(--radius);
            text-align: right;
        }

        .hero-badge span {
            display: block;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #bae6fd;
        }

        .hero-badge strong {
            font-size: 14px;
        }

        /* Box Instruksi */
        .instructions-card {
            background: #ffffff;
            border: 1px solid var(--border-light);
            border-left: 4px solid var(--accent);
            border-radius: var(--radius);
            padding: 16px 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .instructions-card svg {
            width: 24px;
            height: 24px;
            color: var(--accent);
            flex-shrink: 0;
        }

        .instructions-card p {
            font-size: 13px;
            color: #475569;
        }

        /* Panel Daftar Ujian */
        .table-panel {
            background: #ffffff;
            border: 1px solid var(--border-light);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .table-panel-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-panel-header h3 {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-main);
        }

        .cbt-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
            text-align: left;
        }

        .cbt-table th {
            background: #f8fafc;
            color: #475569;
            font-weight: 600;
            padding: 12px 18px;
            border-bottom: 1px solid var(--border);
        }

        .cbt-table td {
            padding: 14px 18px;
            border-bottom: 1px solid var(--border-light);
            vertical-align: middle;
        }

        .cbt-table tr:hover {
            background-color: #fafbfc;
        }

        /* Standar Tombol */
        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 16px;
            font-size: 13px;
            font-weight: 600;
            border-radius: var(--radius);
            text-decoration: none;
            cursor: pointer;
            border: 1px solid transparent;
        }

        .btn-start {
            background: #0077b6;
            color: #ffffff;
        }

        .btn-start:hover {
            background: #005b8c;
        }

        .btn-continue {
            background: #f59e0b;
            color: #ffffff;
        }

        .btn-continue:hover {
            background: #d97706;
        }

        .badge-done {
            background: #dcfce7;
            color: #15803d;
            padding: 4px 10px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 12px;
            display: inline-block;
        }

        /* Footer */
        .portal-footer {
            margin-top: auto;
            background: #ffffff;
            border-top: 1px solid var(--border-light);
            padding: 14px 24px;
            font-size: 12px;
            color: var(--text-muted);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>

    <header class="portal-header">
        <div class="header-brand">
            <img src="{{ asset('images/logo_smk.png') }}" alt="Logo SMK">
            <div>
                <h1>SMKN 1 KUPANG BARAT</h1>
                <p>CBT Modern Standar TKA Pusmendik</p>
            </div>
        </div>

        <div class="header-user">
            <div class="user-tag">
                {{ $siswa->nama_lengkap }} ({{ $siswa->kelas->nama_kelas ?? 'XII' }})
            </div>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn-logout">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    Keluar
                </button>
            </form>
        </div>
    </header>

    <main class="main-container">
        @if (session('success'))
            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 12px 16px; border-radius: var(--radius); margin-bottom: 20px; font-size: 13.5px; font-weight: 500;">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 12px 16px; border-radius: var(--radius); margin-bottom: 20px; font-size: 13.5px; font-weight: 500;">
                {{ session('error') }}
            </div>
        @endif

        <div class="welcome-hero">
            <div class="hero-left">
                <h2>Selamat Datang, {{ $siswa->nama_lengkap }}!</h2>
                <p>Kelas / Rombel: <strong>{{ $siswa->kelas->nama_kelas ?? 'Peserta' }}</strong> &bull; Sesi: <strong>{{ $siswa->sesi_ujian }}</strong> &bull; Ruang: <strong>{{ $siswa->ruangan_ujian }}</strong></p>
            </div>
            <div class="hero-badge">
                <span>Standar Asesmen</span>
                <strong>TKA Kemdikbud Modern</strong>
            </div>
        </div>

        <div class="instructions-card">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
            <p>Silahkan pilih ujian yang tersedia pada daftar di bawah ini. Pastikan Anda telah menerima <strong>Token Ujian</strong> dari Proktor / Pengawas Ruangan sebelum menekan tombol Mulai.</p>
        </div>

        <div class="table-panel">
            <div class="table-panel-header">
                <h3>Daftar Ujian / Asesmen Aktif</h3>
                <a href="{{ route('siswa.dashboard') }}" style="font-size: 12px; color: var(--accent); text-decoration: none; font-weight: 600;">Segarkan Halaman &bull; Refresh</a>
            </div>

            <table class="cbt-table">
                <thead>
                    <tr>
                        <th style="width: 45px;">No.</th>
                        <th>Nama Asesmen / Tes</th>
                        <th>Mata Pelajaran</th>
                        <th>Durasi</th>
                        <th>Status Pengerjaan</th>
                        <th style="text-align: right;">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jadwalAktif as $idx => $jadwal)
                        @php
                            $peserta = $riwayatUjian->get($jadwal->id);
                        @endphp
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td>
                                <strong style="color: var(--text-main); font-size: 14px;">{{ $jadwal->nama_ujian }}</strong><br>
                                <span style="font-size: 11.5px; color: #64748b;">Jumlah: {{ $jadwal->bankSoal->butirSoal->count() }} Butir Soal</span>
                            </td>
                            <td>{{ $jadwal->bankSoal->mataPelajaran->nama_mapel ?? '-' }}</td>
                            <td><strong>{{ $jadwal->durasi_menit }} Menit</strong></td>
                            <td>
                                @if (!$peserta || $peserta->status === 'belum')
                                    <span style="color: #64748b; font-size: 12px;">Belum Dikerjakan</span>
                                @elseif ($peserta->status === 'berlangsung')
                                    <span style="color: #d97706; font-weight: 600; font-size: 12px;">Sedang Dikerjakan</span>
                                @elseif ($peserta->status === 'selesai')
                                    <span class="badge-done">Sudah Selesai</span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                @if (!$peserta || $peserta->status === 'belum')
                                    <a href="{{ route('siswa.konfirmasi', $jadwal->id) }}" class="btn-action btn-start">
                                        Ikuti Asesmen
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                    </a>
                                @elseif ($peserta->status === 'berlangsung')
                                    <a href="{{ route('siswa.ujian', $jadwal->id) }}" class="btn-action btn-continue">
                                        Lanjutkan Tes
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                    </a>
                                @elseif ($peserta->status === 'selesai')
                                    <span style="font-weight: 700; color: #15803d; font-size: 14px;">Nilai: {{ number_format($peserta->total_nilai, 1) }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 36px; color: #64748b;">
                                Tidak ada ujian yang aktif untuk kelas Anda saat ini. Hubungi operator / proktor jika ujian belum muncul.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>

    <footer class="portal-footer">
        <div>SMK Negeri 1 Kupang Barat &bull; Sesi pengerjaan terlindungi otomatis.</div>
        <div>Hak Cipta &copy; {{ date('Y') }} <strong>Vicky Koroh</strong></div>
    </footer>

</body>
</html>
