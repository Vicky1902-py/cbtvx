<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Administrator') &bull; CBT SMKN 1 Kupang Barat</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <style>
        :root {
            --primary: #0f2b48;
            --primary-dark: #07172c;
            --accent: #0077b6;
            --accent-hover: #005b8c;
            --bg-page: #f1f5f9;
            --surface: #ffffff;
            --border: #cbd5e1;
            --border-light: #e2e8f0;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --success: #15803d;
            --success-bg: #dcfce7;
            --warning: #b45309;
            --warning-bg: #fef3c7;
            --danger: #b91c1c;
            --danger-bg: #fee2e2;
            --radius: 4px;
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
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Kiri Tetap */
        .sidebar {
            width: 250px;
            background: var(--primary);
            color: #ffffff;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            border-right: 1px solid rgba(0,0,0,0.15);
        }

        .sidebar-brand {
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            background: var(--primary-dark);
        }

        .sidebar-brand img {
            width: 36px;
            height: 36px;
            object-fit: contain;
        }

        .sidebar-brand h1 {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.3px;
            line-height: 1.2;
        }

        .sidebar-brand p {
            font-size: 10px;
            color: #94a3b8;
        }

        .nav-group-title {
            padding: 16px 16px 6px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #94a3b8;
        }

        .nav-menu {
            list-style: none;
            display: flex;
            flex-direction: column;
        }

        .nav-item a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            color: #cbd5e1;
            text-decoration: none;
            font-weight: 500;
            border-left: 3px solid transparent;
            transition: all 0.15s ease;
        }

        .nav-item a:hover {
            background: rgba(255,255,255,0.06);
            color: #ffffff;
        }

        .nav-item.active a {
            background: rgba(255,255,255,0.12);
            color: #ffffff;
            border-left-color: #38bdf8;
            font-weight: 600;
        }

        .nav-item svg {
            width: 18px;
            height: 18px;
            opacity: 0.85;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 14px 16px;
            border-top: 1px solid rgba(255,255,255,0.1);
            font-size: 11px;
            color: #94a3b8;
            background: var(--primary-dark);
        }

        /* Konten Kanan */
        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        /* Bilah Atas Sederhana */
        .topbar {
            height: 54px;
            background: var(--surface);
            border-bottom: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
        }

        .topbar-info {
            display: flex;
            align-items: center;
            gap: 16px;
            font-size: 13px;
        }

        .badge-academic {
            background: #e2e8f0;
            padding: 3px 8px;
            border-radius: var(--radius);
            font-weight: 600;
            color: #334155;
            font-size: 11px;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .user-greeting {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-main);
        }

        .btn-logout {
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
            padding: 6px 12px;
            border-radius: var(--radius);
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-logout:hover {
            background: #fca5a5;
            color: #7f1d1d;
        }

        /* Konten Halaman */
        .content-area {
            padding: 24px;
            flex: 1;
        }

        .page-header {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-main);
        }

        .page-subtitle {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        /* Standar Tombol */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            font-size: 13px;
            font-weight: 600;
            border-radius: var(--radius);
            border: 1px solid transparent;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--accent);
            color: #ffffff;
            border-color: #00669e;
        }

        .btn-primary:hover {
            background: var(--accent-hover);
        }

        .btn-secondary {
            background: #ffffff;
            color: #334155;
            border-color: var(--border);
        }

        .btn-secondary:hover {
            background: #f8fafc;
        }

        .btn-sm {
            padding: 4px 8px;
            font-size: 11px;
        }

        /* Standar Tabel Padat */
        .table-container {
            background: var(--surface);
            border: 1px solid var(--border-light);
            border-radius: var(--radius);
            overflow-x: auto;
        }

        .table-compact {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            text-align: left;
        }

        .table-compact th {
            background: #f8fafc;
            color: #475569;
            font-weight: 600;
            padding: 10px 14px;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        .table-compact td {
            padding: 10px 14px;
            border-bottom: 1px solid var(--border-light);
            vertical-align: middle;
        }

        .table-compact tr:nth-child(even) {
            background-color: #fafbfc;
        }

        .table-compact tr:hover {
            background-color: #f1f5f9;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 2px 7px;
            font-size: 11px;
            font-weight: 600;
            border-radius: 3px;
        }

        .badge-success { background: var(--success-bg); color: var(--success); }
        .badge-warning { background: var(--warning-bg); color: var(--warning); }
        .badge-danger { background: var(--danger-bg); color: var(--danger); }
        .badge-info { background: #e0f2fe; color: #0369a1; }
        .badge-neutral { background: #e2e8f0; color: #475569; }

        /* Notifikasi */
        .alert {
            padding: 12px 16px;
            border-radius: var(--radius);
            font-size: 13px;
            margin-bottom: 20px;
            border: 1px solid transparent;
        }
        .alert-success { background: #f0fdf4; border-color: #bbf7d0; color: #166534; }
        .alert-danger { background: #fef2f2; border-color: #fecaca; color: #991b1b; }

        /* Footer */
        .footer-copyright {
            padding: 16px 24px;
            border-top: 1px solid var(--border-light);
            background: var(--surface);
            font-size: 12px;
            color: var(--text-muted);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar Kiri Tetap -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('images/logo_smk.png') }}" alt="Logo SMK">
            <div>
                <h1>SMKN 1 KUPANG BARAT</h1>
                <p>CBT Asesmen Terstandar</p>
            </div>
        </div>

        <div class="nav-group-title">Utama</div>
        <ul class="nav-menu">
            <li class="nav-item {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    <span>Dashboard & Monitoring</span>
                </a>
            </li>
        </ul>

        <div class="nav-group-title">Data Master</div>
        <ul class="nav-menu">
            <li class="nav-item {{ request()->routeIs('admin.siswa*') ? 'active' : '' }}">
                <a href="{{ route('admin.siswa') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <span>Data Siswa</span>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.bank_soal*') ? 'active' : '' }}">
                <a href="{{ route('admin.bank_soal') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                    <span>Bank Soal (25/40 Soal)</span>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.jadwal*') ? 'active' : '' }}">
                <a href="{{ route('admin.jadwal') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    <span>Jadwal & Token Ujian</span>
                </a>
            </li>
        </ul>

        <div class="nav-group-title">Laporan & Cetak</div>
        <ul class="nav-menu">
            <li class="nav-item {{ request()->routeIs('admin.cetak_kartu*') ? 'active' : '' }}">
                <a href="{{ route('admin.cetak_kartu') }}" target="_blank">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                    <span>Cetak Kartu Ujian A4</span>
                </a>
            </li>
        </ul>

        <div class="nav-group-title">Sistem</div>
        <ul class="nav-menu">
            <li class="nav-item {{ request()->routeIs('admin.pengaturan*') ? 'active' : '' }}">
                <a href="{{ route('admin.pengaturan') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                    <span>Pengaturan & TTD Kepsek</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <div>Kepala Sekolah:</div>
            <div style="font-weight: 600; color: #ffffff;">Vicky Koroh, S.Pd., M.Kom.</div>
            <div style="font-size: 10px; margin-top: 4px;">NIP. 198501192011011005</div>
        </div>
    </aside>

    <!-- Konten Kanan -->
    <div class="main-wrapper">
        <header class="topbar">
            <div class="topbar-info">
                <strong>{{ $sekolah->nama_sekolah ?? 'SMK NEGERI 1 KUPANG BARAT' }}</strong>
                <span class="badge-academic">TP: {{ $sekolah->tahun_ajaran_aktif ?? '2026/2027' }} - {{ $sekolah->semester_aktif ?? 'Genap' }}</span>
            </div>

            <div class="topbar-user">
                <span class="user-greeting">Halo, {{ Auth::user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                        Keluar
                    </button>
                </form>
            </div>
        </header>

        <main class="content-area">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="footer-copyright">
            <div>Hak Cipta &copy; {{ date('Y') }} <strong>Vicky Koroh</strong>. Aplikasi CBT SMKN 1 Kupang Barat.</div>
            <div style="font-size: 11px;">Versi Arsitektur Bersih &bull; Berstandar ANBK / TKA Pusmendik Kemdikbud</div>
        </footer>
    </div>

    @yield('scripts')
</body>
</html>
