<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Administrator') &bull; CBT SMKN 1 Kupang Barat</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    
    <!-- Chart.js Offline Lokal -->
    <script src="{{ asset('js/chart.min.js') }}"></script>

    <!-- KaTeX Rumus Matematika (ANBK / TKA) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/contrib/auto-render.min.js" onload="if (window.renderMathInElement) { renderMathInElement(document.body, {delimiters: [{left: '$$', right: '$$', display: true}, {left: '$', right: '$', display: false}]}); }"></script>

    <style>
        :root {
            --primary: #0f2b48;
            --primary-dark: #07172c;
            --primary-gradient: linear-gradient(180deg, #0f2b48 0%, #091a2e 100%);
            --accent: #0077b6;
            --accent-hover: #005b8c;
            --accent-light: #38bdf8;
            --bg-page: #f1f5f9;
            --surface: #ffffff;
            --border: #cbd5e1;
            --border-light: #e2e8f0;
            --text-main: #0f172a;
            --text-body: #334155;
            --text-muted: #64748b;
            --success: #15803d;
            --success-bg: #dcfce7;
            --warning: #b45309;
            --warning-bg: #fef3c7;
            --danger: #b91c1c;
            --danger-bg: #fee2e2;
            --radius-sm: 4px;
            --radius: 6px;
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 68px;
            --transition-speed: 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
            background: var(--bg-page);
            color: var(--text-body);
            font-size: 13.5px;
            line-height: 1.5;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Kiri Tetap / Collapsible */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--primary-gradient);
            color: #ffffff;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            border-right: 1px solid rgba(0,0,0,0.2);
            transition: width var(--transition-speed);
            overflow-x: hidden;
            position: relative;
            z-index: 100;
        }

        body.sidebar-collapsed .sidebar {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-brand {
            padding: 14px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            background: var(--primary-dark);
            height: 56px;
            white-space: nowrap;
        }

        .sidebar-brand img {
            width: 32px;
            height: 32px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .sidebar-brand-text {
            transition: opacity var(--transition-speed);
        }

        body.sidebar-collapsed .sidebar-brand-text {
            display: none;
        }

        .sidebar-brand h1 {
            font-size: 13px;
            font-weight: 700;
            line-height: 1.2;
            color: #ffffff;
        }

        .sidebar-brand p {
            font-size: 10px;
            color: var(--accent-light);
        }

        .nav-group-title {
            padding: 14px 16px 4px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #94a3b8;
            white-space: nowrap;
            transition: opacity var(--transition-speed);
        }

        body.sidebar-collapsed .nav-group-title {
            display: none;
        }

        .nav-menu {
            list-style: none;
            display: flex;
            flex-direction: column;
        }

        .nav-item a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            color: #cbd5e1;
            text-decoration: none;
            font-weight: 500;
            border-left: 3px solid transparent;
            transition: all 0.15s ease;
            white-space: nowrap;
        }

        .nav-item a:hover {
            background: rgba(255,255,255,0.07);
            color: #ffffff;
        }

        .nav-item.active a {
            background: rgba(255,255,255,0.12);
            color: #ffffff;
            border-left-color: var(--accent-light);
            font-weight: 600;
        }

        .nav-item svg {
            width: 19px;
            height: 19px;
            flex-shrink: 0;
            opacity: 0.9;
        }

        body.sidebar-collapsed .nav-item a {
            padding: 12px 0;
            justify-content: center;
        }

        body.sidebar-collapsed .nav-item span {
            display: none;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 12px 16px;
            border-top: 1px solid rgba(255,255,255,0.08);
            font-size: 11px;
            color: #94a3b8;
            background: var(--primary-dark);
            white-space: nowrap;
        }

        body.sidebar-collapsed .sidebar-footer {
            display: none;
        }

        /* Konten Kanan */
        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            transition: all var(--transition-speed);
        }

        /* Bilah Atas Sederhana */
        .topbar {
            height: 56px;
            background: var(--surface);
            border-bottom: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        /* Tombol Toggle Auxiliary Pane (Sidebar Collapse) */
        .btn-toggle-sidebar {
            background: #f8fafc;
            border: 1px solid var(--border);
            width: 32px;
            height: 32px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--text-body);
            transition: all 0.15s ease;
        }

        .btn-toggle-sidebar:hover {
            background: #e2e8f0;
            color: var(--primary);
        }

        .topbar-info {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 13px;
        }

        .badge-academic {
            background: #e2e8f0;
            padding: 3px 8px;
            border-radius: var(--radius-sm);
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
            border-radius: var(--radius-sm);
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
        }

        /* Konten Halaman */
        .content-area {
            padding: 20px;
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
            padding: 7px 14px;
            font-size: 13px;
            font-weight: 600;
            border-radius: var(--radius-sm);
            border: 1px solid transparent;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s ease;
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

        .btn-danger {
            background: #dc2626;
            color: #ffffff;
            border-color: #b91c1c;
        }

        .btn-danger:hover {
            background: #b91c1c;
        }

        .btn-sm {
            padding: 4px 8px;
            font-size: 11.5px;
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
            border-radius: var(--radius-sm);
            font-size: 13px;
            margin-bottom: 20px;
            border: 1px solid transparent;
        }
        .alert-success { background: #f0fdf4; border-color: #bbf7d0; color: #166534; }
        .alert-danger { background: #fef2f2; border-color: #fecaca; color: #991b1b; }

        /* Footer */
        .footer-copyright {
            padding: 14px 20px;
            border-top: 1px solid var(--border-light);
            background: var(--surface);
            font-size: 12px;
            color: var(--text-muted);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Compact & Sleek Modern Pagination */
        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 18px;
            background: #ffffff;
            border-top: 1px solid var(--border-light);
            border-radius: 0 0 var(--radius) var(--radius);
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        .pagination-info {
            font-size: 12.5px;
            color: var(--text-muted);
        }
        .pagination-info strong {
            color: var(--text-main);
        }
        .pagination-list {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 5px;
            align-items: center;
        }
        .pagination-list .page-item a,
        .pagination-list .page-item span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 32px;
            height: 32px;
            padding: 0 10px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 4px;
            border: 1px solid var(--border);
            background: #ffffff;
            color: var(--text-body);
            text-decoration: none;
            transition: all 0.15s ease;
        }
        .pagination-list .page-item a:hover {
            background: #f1f5f9;
            border-color: var(--accent);
            color: var(--accent);
        }
        .pagination-list .page-item.active span {
            background: var(--accent);
            color: #ffffff;
            border-color: var(--accent);
        }
        .pagination-list .page-item.disabled span {
            color: #94a3b8;
            background: #f8fafc;
            border-color: #e2e8f0;
            cursor: not-allowed;
        }
        /* Fallback if default tailwind SVG pagination is invoked */
        nav[role="navigation"] svg {
            width: 14px !important;
            height: 14px !important;
            display: inline-block !important;
        }
        nav[role="navigation"] .relative.inline-flex {
            font-size: 12px !important;
            padding: 5px 10px !important;
            border-radius: 4px !important;
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar Kiri Tetap / Toggleable -->
    <aside class="sidebar" id="mainSidebar">
        <div class="sidebar-brand">
            <img src="{{ $sekolah->logo_sekolah_url }}" alt="Logo SMK">
            <div class="sidebar-brand-text">
                <h1>{{ $sekolah->nama_sekolah }}</h1>
                <p>CBT Asesmen Terstandar</p>
            </div>
        </div>

        <div class="nav-group-title">Utama</div>
        <ul class="nav-menu">
            <li class="nav-item {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}" title="Dashboard & Monitoring">
                <a href="{{ route('admin.dashboard') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    <span>Dashboard & Monitoring</span>
                </a>
            </li>
        </ul>

        <div class="nav-group-title">Data Master</div>
        <ul class="nav-menu">
            <li class="nav-item {{ request()->routeIs('admin.kelas*') ? 'active' : '' }}" title="Data Kelas">
                <a href="{{ route('admin.kelas') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    <span>Data Kelas & Rombel</span>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.mapel*') ? 'active' : '' }}" title="Mata Pelajaran">
                <a href="{{ route('admin.mapel') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                    <span>Mata Pelajaran</span>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.siswa*') ? 'active' : '' }}" title="Data Siswa">
                <a href="{{ route('admin.siswa') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <span>Data Siswa (Impor XLSX)</span>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}" title="Guru & Pengguna">
                <a href="{{ route('admin.users') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="19" y1="8" x2="19" y2="14"></line><line x1="22" y1="11" x2="16" y2="11"></line></svg>
                    <span>Guru & Pengguna</span>
                </a>
            </li>
        </ul>

        <div class="nav-group-title">Bank Soal & Penilaian</div>
        <ul class="nav-menu">
            <li class="nav-item {{ request()->routeIs('admin.bank_soal*') ? 'active' : '' }}" title="Bank Soal">
                <a href="{{ route('admin.bank_soal') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                    <span>Bank Soal (25/40 Soal)</span>
                </a>
            </li>
        </ul>

        <div class="nav-group-title">Pelaksanaan Ujian</div>
        <ul class="nav-menu">
            <li class="nav-item {{ request()->routeIs('admin.jadwal*') ? 'active' : '' }}" title="Jadwal & Token Ujian">
                <a href="{{ route('admin.jadwal') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    <span>Jadwal & Token Ujian</span>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.petugas_ujian*') ? 'active' : '' }}" title="Petugas & Pengawas">
                <a href="{{ route('admin.petugas_ujian') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <span>Petugas & Pengawas</span>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.cetak_kartu') ? 'active' : '' }}" title="Cetak Kartu Ujian A4">
                <a href="{{ route('admin.cetak_kartu') }}" target="_blank">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                    <span>Cetak Kartu Login A4</span>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.cetak_kartu_petugas*') ? 'active' : '' }}" title="Cetak Kartu Petugas A4">
                <a href="{{ route('admin.cetak_kartu_petugas') }}" target="_blank">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    <span>Cetak Kartu Petugas A4</span>
                </a>
            </li>
        </ul>

        <div class="nav-group-title">Sistem & Profil</div>
        <ul class="nav-menu">
            <li class="nav-item {{ request()->routeIs('admin.pengaturan*') ? 'active' : '' }}" title="Pengaturan & TTD Kepsek">
                <a href="{{ route('admin.pengaturan') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                    <span>Data Sekolah & TTD Kepsek</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <div>Kepala Sekolah:</div>
            <div style="font-weight: 600; color: #ffffff;">{{ $sekolah->kepala_sekolah }}</div>
            <div style="font-size: 10px; margin-top: 4px;">NIP. {{ $sekolah->nip_kepala_sekolah }}</div>
        </div>
    </aside>

    <!-- Konten Kanan -->
    <div class="main-wrapper">
        <header class="topbar">
            <div class="topbar-left">
                <!-- Tombol Toggle Auxiliary Pane -->
                <button type="button" class="btn-toggle-sidebar" id="btnToggleSidebar" title="Kecilkan / Besarkan Menu Sidebar (Auxiliary Pane)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                </button>

                <div class="topbar-info">
                    <strong>{{ $sekolah->nama_sekolah ?? 'SMK NEGERI 1 KUPANG BARAT' }}</strong>
                    <span class="badge-academic">TP: {{ $sekolah->tahun_ajaran_aktif ?? '2026/2027' }} - {{ $sekolah->semester_aktif ?? 'Genap' }}</span>
                </div>
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

    <!-- Script Pengendali Toggle Sidebar & State Persistence -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btnToggle = document.getElementById('btnToggleSidebar');
            const isCollapsed = localStorage.getItem('cbt_sidebar_collapsed') === 'true';

            if (isCollapsed) {
                document.body.classList.add('sidebar-collapsed');
            }

            if (btnToggle) {
                btnToggle.addEventListener('click', function () {
                    document.body.classList.toggle('sidebar-collapsed');
                    const currentState = document.body.classList.contains('sidebar-collapsed');
                    localStorage.setItem('cbt_sidebar_collapsed', currentState);
                });
            }
        });
    </script>

    @yield('scripts')
</body>
</html>
