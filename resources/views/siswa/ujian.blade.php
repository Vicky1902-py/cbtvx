<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ujian CBT &bull; {{ $jadwal->nama_ujian }} &bull; SMKN 1 Kupang Barat</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <!-- KaTeX Rumus Matematika (ANBK / TKA - Non-blocking untuk lab offline) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.css" media="print" onload="this.media='all'">
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/contrib/auto-render.min.js"></script>
    <style>
        :root {
            --primary: #0f2b48;
            --primary-dark: #07172c;
            --accent: #0077b6;
            --accent-hover: #005b8c;
            --bg-page: #f1f5f9;
            --surface: #ffffff;
            --border-light: #e2e8f0;
            --border: #cbd5e1;
            --text-main: #0f172a;
            --text-body: #334155;
            --text-muted: #64748b;
            --color-answered: #0077b6;
            --color-doubt: #d97706;
            --color-unanswered: #94a3b8;
            --radius-sm: 4px;
            --radius: 6px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: var(--bg-page);
            color: var(--text-body);
            font-size: 15px;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            user-select: none;
        }

        /* Top Bar ANBK */
        .exam-header {
            background: var(--primary);
            color: #ffffff;
            padding: 8px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #0284c7;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-left img {
            width: 34px;
            height: 34px;
            object-fit: contain;
        }

        .header-left h1 {
            font-size: 13px;
            font-weight: 700;
        }

        .header-left p {
            font-size: 11px;
            color: #94a3b8;
        }

        .header-center {
            text-align: center;
        }

        .header-center .exam-name {
            font-size: 13px;
            font-weight: 700;
            color: #ffffff;
        }

        .header-center .exam-sub {
            font-size: 11px;
            color: #bae6fd;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        /* Timer Standar ANBK */
        .timer-box {
            background: #07172c;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 4px 12px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .timer-box span.label {
            font-size: 11px;
            color: #94a3b8;
            text-transform: uppercase;
        }

        .timer-box strong {
            font-family: monospace;
            font-size: 17px;
            letter-spacing: 1px;
            color: #38bdf8;
        }

        .timer-box.timer-warning strong {
            color: #ef4444;
            animation: pulse 1s infinite;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }

        /* Layout Utama Ujian */
        .exam-body {
            flex: 1;
            display: grid;
            grid-template-columns: 1fr 310px;
            max-width: 1360px;
            width: 100%;
            margin: 0 auto;
            padding: 16px;
            gap: 16px;
        }

        /* Area Soal (Kiri) */
        .question-card {
            background: var(--surface);
            border: 1px solid var(--border-light);
            border-radius: var(--radius);
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .question-header {
            padding: 12px 20px;
            border-bottom: 1px solid var(--border-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fafbfc;
        }

        .question-number-badge {
            font-size: 15px;
            font-weight: 700;
            color: var(--primary);
        }

        .font-sizer {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--text-muted);
        }

        .btn-size {
            background: #ffffff;
            border: 1px solid var(--border);
            padding: 2px 8px;
            border-radius: var(--radius-sm);
            cursor: pointer;
            font-weight: 600;
        }

        .btn-size:hover {
            background: #f1f5f9;
        }

        .question-content {
            padding: 24px;
            flex: 1;
            overflow-y: auto;
        }

        .question-text {
            font-size: 16px;
            color: var(--text-main);
            margin-bottom: 24px;
            line-height: 1.65;
            max-width: 850px;
        }

        /* Pilihan Jawaban */
        .options-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            max-width: 850px;
        }

        .option-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 12px 16px;
            border: 1.5px solid var(--border-light);
            border-radius: var(--radius);
            background: #ffffff;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .option-item:hover {
            border-color: #94a3b8;
            background: #f8fafc;
        }

        .option-item.selected {
            border-color: var(--accent);
            background: #f0f9ff;
        }

        .option-letter {
            width: 28px;
            height: 28px;
            border-radius: var(--radius-sm);
            border: 1.5px solid var(--border);
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13.5px;
            color: var(--text-body);
            flex-shrink: 0;
        }

        .option-item.selected .option-letter {
            background: var(--accent);
            border-color: var(--accent);
            color: #ffffff;
        }

        .option-text {
            flex: 1;
            font-size: 15px;
            color: var(--text-main);
            padding-top: 2px;
        }

        /* Bilah Navigasi Bawah Soal */
        .exam-action-bar {
            padding: 14px 20px;
            border-top: 1px solid var(--border-light);
            background: #fafbfc;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-nav {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 18px;
            font-size: 13.5px;
            font-weight: 600;
            border-radius: var(--radius-sm);
            border: 1px solid transparent;
            cursor: pointer;
        }

        .btn-prev {
            background: #ffffff;
            border-color: var(--border);
            color: var(--text-body);
        }

        .btn-prev:hover {
            background: #f1f5f9;
        }

        .btn-doubt {
            background: #fef3c7;
            border-color: #fde68a;
            color: #b45309;
            font-weight: 600;
        }

        .btn-doubt.active {
            background: #f59e0b;
            color: #ffffff;
            border-color: #d97706;
        }

        .btn-next {
            background: var(--accent);
            color: #ffffff;
        }

        .btn-next:hover {
            background: var(--accent-hover);
        }

        .btn-finish {
            background: #15803d;
            color: #ffffff;
        }

        .btn-finish:hover {
            background: #166534;
        }

        /* Sidebar Kanan: Daftar Nomor Soal */
        .nav-panel {
            background: var(--surface);
            border: 1px solid var(--border-light);
            border-radius: var(--radius);
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            height: fit-content;
        }

        .nav-panel-header {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-light);
            background: #fafbfc;
            font-weight: 700;
            font-size: 13.5px;
            color: var(--text-main);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .number-grid {
            padding: 16px;
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 8px;
            max-height: 400px;
            overflow-y: auto;
        }

        .num-btn {
            aspect-ratio: 1;
            border: 1.5px solid var(--border);
            background: #ffffff;
            border-radius: var(--radius-sm);
            font-weight: 700;
            font-size: 13px;
            color: var(--text-body);
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .num-btn:hover {
            border-color: var(--accent);
        }

        .num-btn.active {
            box-shadow: 0 0 0 2px var(--accent);
        }

        .num-btn.answered {
            background: #0077b6;
            border-color: #0077b6;
            color: #ffffff;
        }

        .num-btn.doubt {
            background: #f59e0b;
            border-color: #d97706;
            color: #ffffff;
        }

        .num-btn .ans-code {
            font-size: 9px;
            position: absolute;
            bottom: 2px;
            font-weight: bold;
        }

        .legend-box {
            padding: 12px 16px;
            border-top: 1px solid var(--border-light);
            background: #fafbfc;
            display: flex;
            flex-direction: column;
            gap: 6px;
            font-size: 11.5px;
            color: var(--text-muted);
        }

        .legend-row {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .legend-dot {
            width: 14px;
            height: 14px;
            border-radius: 3px;
        }

        /* Modal Selesai */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.6);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 999;
        }

        .modal-box {
            background: #ffffff;
            border-radius: var(--radius);
            max-width: 480px;
            width: 90%;
            padding: 24px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
        }

        .modal-title {
            font-size: 17px;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 8px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 8px;
            margin: 16px 0;
            text-align: center;
        }

        .summary-item {
            padding: 10px;
            border-radius: var(--radius-sm);
            background: #f8fafc;
            border: 1px solid var(--border-light);
        }

        .summary-item strong {
            display: block;
            font-size: 18px;
        }

        .question-text {
            overflow-wrap: break-word;
            word-wrap: break-word;
        }

        .question-text table,
        .question-text img,
        .option-text img {
            max-width: 100%;
            height: auto;
        }

        /* Modal Zoom Gambar In-Page (Tidak membuka tab baru / anti-cheat aman) */
        .image-zoom-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.88);
            z-index: 100000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 16px;
            backdrop-filter: blur(4px);
        }

        .image-zoom-box {
            position: relative;
            max-width: 95vw;
            max-height: 90vh;
            background: #ffffff;
            border-radius: 8px;
            padding: 10px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .image-zoom-box img {
            max-width: 100%;
            max-height: calc(90vh - 70px);
            object-fit: contain;
            border-radius: 4px;
        }

        @media (max-width: 1100px) {
            .exam-header {
                padding: 8px 14px;
                gap: 10px;
            }
            .header-left p {
                max-width: 210px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .exam-body {
                grid-template-columns: 1fr 255px;
                padding: 12px;
                gap: 12px;
            }
            .number-grid {
                grid-template-columns: repeat(4, 1fr);
                gap: 6px;
                padding: 12px;
            }
        }

        @media (max-width: 920px) {
            .exam-header {
                flex-wrap: wrap;
                padding: 10px 14px;
                gap: 8px;
            }
            .header-left {
                flex: 1;
                min-width: 0;
                gap: 10px;
            }
            .header-left img {
                width: 30px;
                height: 30px;
            }
            .header-left h1 {
                font-size: 12px;
            }
            .header-left p {
                font-size: 11px;
                max-width: none;
                white-space: normal;
                word-break: break-word;
            }
            .header-right {
                gap: 8px;
                flex-shrink: 0;
            }
            #sync-status-badge {
                padding: 4px 8px !important;
                font-size: 11px !important;
            }
            .timer-box {
                padding: 4px 10px;
                gap: 6px;
            }
            .timer-box span.label {
                display: none;
            }
            .timer-box strong {
                font-size: 15px;
            }
            .header-center {
                order: 3;
                width: 100%;
                border-top: 1px solid rgba(255, 255, 255, 0.14);
                padding-top: 6px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 10px;
                text-align: left;
            }
            .header-center .exam-name {
                font-size: 12px;
            }
            .header-center .exam-sub {
                font-size: 11px;
                flex-shrink: 0;
            }
            .exam-body {
                grid-template-columns: 1fr;
                padding: 10px 12px;
                gap: 12px;
            }
            .question-header {
                padding: 10px 14px;
                flex-wrap: wrap;
                gap: 8px;
            }
            .question-content {
                padding: 16px 14px;
            }
            .question-text {
                font-size: 15.5px;
                margin-bottom: 18px;
            }
            .option-item {
                padding: 13px 14px;
                gap: 12px;
                min-height: 52px;
            }
            .option-letter {
                width: 32px;
                height: 32px;
                font-size: 14px;
            }
            .exam-action-bar {
                padding: 12px 14px;
                flex-wrap: wrap;
                gap: 8px;
            }
            .exam-action-bar .btn-nav {
                min-height: 44px;
                padding: 10px 14px;
                font-size: 13.5px;
                justify-content: center;
            }
            .btn-drawer-toggle {
                display: inline-flex !important;
            }
            .nav-panel {
                order: 2;
            }
            .number-grid {
                grid-template-columns: repeat(auto-fill, minmax(48px, 1fr));
                gap: 8px;
                padding: 14px;
                max-height: none;
            }
            .num-btn {
                min-height: 46px;
                max-height: 54px;
                font-size: 13.5px;
            }
            .nav-panel.drawer-open {
                position: fixed;
                inset: 0;
                z-index: 1000;
                height: 100dvh;
                max-height: 100dvh;
                border-radius: 0;
                border: none;
            }
            .nav-panel.drawer-open .nav-panel-close-btn {
                display: inline-flex !important;
            }
            .nav-panel.drawer-open .number-grid {
                flex: 1;
                overflow-y: auto;
                align-content: start;
            }
        }

        @media (max-width: 640px) {
            .exam-action-bar {
                display: grid;
                grid-template-columns: 1fr 1fr;
            }
            .exam-action-bar > div {
                grid-column: 1 / -1;
                display: grid !important;
                grid-template-columns: 1fr 1fr;
                gap: 8px;
            }
            #sync-status-text {
                display: none;
            }
        }
    </style>
</head>
<body>

    <header class="exam-header">
        <div class="header-left">
            <img src="{{ asset('images/logo_smk.png') }}" alt="Logo SMK">
            <div>
                <h1>SMKN 1 KUPANG BARAT</h1>
                <p>{{ $siswa->nama_lengkap }} ({{ $siswa->nisn }})</p>
            </div>
        </div>

        <div class="header-center">
            <div class="exam-name">{{ $jadwal->nama_ujian }}</div>
            <div class="exam-sub">{{ $jadwal->bankSoal->mataPelajaran->nama_mapel ?? 'Asesmen' }}</div>
        </div>

        <div class="header-right">
            <div id="sync-status-badge" style="display: inline-flex; align-items: center; gap: 6px; font-size: 11.5px; padding: 4px 10px; border-radius: 4px; background: rgba(34, 197, 94, 0.15); color: #86efac; border: 1px solid rgba(34, 197, 94, 0.3); transition: all 0.3s ease;">
                <span id="sync-status-dot" style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #22c55e;"></span>
                <span id="sync-status-text" style="font-weight: 600;">Semua Tersimpan</span>
            </div>
            <div class="timer-box" id="timer-box">
                <span class="label">Sisa Waktu:</span>
                <strong id="timer-display">--:--:--</strong>
            </div>
        </div>
    </header>

    <div class="exam-body">
        <!-- Area Soal -->
        <div class="question-card">
            <div class="question-header">
                <div class="question-number-badge" id="current-question-title">SOAL NOMOR 1</div>
                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                    <button type="button" class="btn-size btn-drawer-toggle" onclick="toggleNavDrawer()" style="display: none; align-items: center; gap: 5px; background: #e0f2fe; border-color: #7dd3fc; color: #0369a1; padding: 5px 10px;">
                        &#9776; Daftar Soal (<span id="drawer-answered-count">0</span>/{{ $soalList->count() }})
                    </button>
                    <div class="font-sizer">
                        <span>Ukuran Teks:</span>
                        <button type="button" class="btn-size" onclick="changeFontSize(-1)">A-</button>
                        <button type="button" class="btn-size" onclick="changeFontSize(0)">A</button>
                        <button type="button" class="btn-size" onclick="changeFontSize(1)">A+</button>
                    </div>
                </div>
            </div>

            <div class="question-content" id="question-content-area">
                <div class="question-text" id="question-text-el"></div>

                <div class="options-list" id="options-container">
                    <!-- Dinamis via JS -->
                </div>
            </div>

            <div class="exam-action-bar">
                <button type="button" class="btn-nav btn-prev" id="btn-prev" onclick="prevQuestion()">
                    &larr; Soal Sebelumnya
                </button>

                <button type="button" class="btn-nav btn-doubt" id="btn-doubt" onclick="toggleDoubt()">
                    <input type="checkbox" id="chk-doubt" style="margin-right: 4px; pointer-events: none;">
                    Ragu-ragu
                </button>

                <div style="display: flex; gap: 8px;">
                    <button type="button" class="btn-nav btn-next" id="btn-next" onclick="nextQuestion()">
                        Soal Berikutnya &rarr;
                    </button>
                    <button type="button" class="btn-nav btn-finish" onclick="openFinishModal()">
                        Selesai Ujian
                    </button>
                </div>
            </div>
        </div>

        <!-- Panel Daftar Nomor Soal -->
        <aside class="nav-panel" id="nav-panel-drawer">
            <div class="nav-panel-header">
                <span>Daftar Nomor Soal</span>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="font-size: 11px; font-weight: normal; color: var(--text-muted);">Total: {{ $soalList->count() }}</span>
                    <button type="button" class="nav-panel-close-btn" onclick="closeNavDrawer()" style="display: none; align-items: center; gap: 4px; background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; border-radius: 4px; padding: 4px 10px; font-size: 12px; font-weight: 700; cursor: pointer;">&#10005; Tutup</button>
                </div>
            </div>

            <div class="number-grid" id="number-grid">
                <!-- Dinamis via JS -->
            </div>

            <div class="legend-box">
                <div class="legend-row">
                    <span class="legend-dot" style="background: var(--color-answered);"></span>
                    <span>Sudah Dijawab</span>
                </div>
                <div class="legend-row">
                    <span class="legend-dot" style="background: var(--color-doubt);"></span>
                    <span>Ragu-ragu</span>
                </div>
                <div class="legend-row">
                    <span class="legend-dot" style="background: #ffffff; border: 1.5px solid var(--border);"></span>
                    <span>Belum Dijawab</span>
                </div>
            </div>
        </aside>
    </div>

    <!-- Modal Zoom Gambar In-Page -->
    <div class="image-zoom-overlay" id="image-zoom-modal" onclick="if (event.target === this) closeImageZoom();">
        <div class="image-zoom-box">
            <img id="image-zoom-preview" src="" alt="Perbesaran Gambar Soal">
            <button type="button" onclick="closeImageZoom()" style="margin-top: 10px; background: #0f2b48; color: #ffffff; border: none; padding: 8px 20px; border-radius: 4px; font-weight: 700; font-size: 13px; cursor: pointer;">&#10005; Tutup Gambar</button>
        </div>
    </div>

    <!-- Modal Konfirmasi Selesai Ujian -->
    <div class="modal-overlay" id="finish-modal">
        <div class="modal-box">
            <h3 class="modal-title">Konfirmasi Selesai Ujian</h3>
            <p style="font-size: 13.5px; color: var(--text-muted);">
                Periksa kembali rekapitulasi jawaban Anda sebelum mengakhiri sesi pengerjaan ujian.
            </p>

            <div class="summary-grid">
                <div class="summary-item">
                    <span style="font-size: 11px; color: #166534;">Terjawab</span>
                    <strong style="color: #15803d;" id="sum-answered">0</strong>
                </div>
                <div class="summary-item">
                    <span style="font-size: 11px; color: #b45309;">Ragu-ragu</span>
                    <strong style="color: #d97706;" id="sum-doubt">0</strong>
                </div>
                <div class="summary-item">
                    <span style="font-size: 11px; color: #64748b;">Belum Dijawab</span>
                    <strong style="color: #64748b;" id="sum-unanswered">0</strong>
                </div>
            </div>

            <p style="font-size: 12.5px; color: #b91c1c; margin-bottom: 20px;">
                * Peringatan: Setelah menekan tombol "Selesai Sekarang", Anda tidak dapat lagi mengubah jawaban Anda.
            </p>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn-nav btn-prev" onclick="closeFinishModal()">Batal & Periksa Lagi</button>
                <form action="{{ route('siswa.selesai', $jadwal->id) }}" method="POST" onsubmit="isExamFinished = true; try { localStorage.removeItem(storageKey); } catch(e){}">
                    @csrf
                    <button type="submit" class="btn-nav btn-finish">Ya, Selesai Sekarang</button>
                </form>
            </div>
        </div>
    </div>

    <!-- OVERLAY FULLSCREEN LOCK (WAJIB LAYAR PENUH) -->
    <div id="fullscreen-overlay" style="display: flex; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.96); z-index: 99999; align-items: center; justify-content: center; backdrop-filter: blur(6px);">
        <div style="background: #ffffff; border-radius: 8px; max-width: 520px; width: 90%; padding: 32px 28px; text-align: center; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); border-top: 5px solid #0284c7;">
            <div style="width: 64px; height: 64px; border-radius: 50%; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto;">
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"></path></svg>
            </div>
            <h2 style="font-size: 18px; font-weight: 800; color: #0f172a; margin-bottom: 8px;">MODE UJIAN LAYAR PENUH (FULL-SCREEN LOCK)</h2>
            <p style="font-size: 13.5px; color: #475569; line-height: 1.6; margin-bottom: 20px;">
                Demi menjaga integritas ujian dan sesuai standar CBT Pusmendik, Anda <strong>WAJIB</strong> berada dalam mode layar penuh (Full-Screen). Seluruh akses ke jendela lain dinonaktifkan selama asesmen berlangsung.
            </p>
            <button type="button" id="btn-request-fullscreen" style="background: #0284c7; color: #ffffff; border: none; padding: 13px 28px; font-size: 14.5px; font-weight: 800; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.35);">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"></path></svg>
                MASUK MODE LAYAR PENUH & LANJUTKAN
            </button>
        </div>
    </div>

    <!-- MODAL PERINGATAN KECURANGAN: PINDAH TAB / MINIMIZE -->
    <div id="warning-switch-modal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(153, 27, 27, 0.90); z-index: 99998; align-items: center; justify-content: center; backdrop-filter: blur(5px);">
        <div style="background: #ffffff; border-radius: 8px; max-width: 480px; width: 90%; padding: 28px; text-align: center; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); border-top: 5px solid #dc2626;">
            <div style="width: 60px; height: 60px; border-radius: 50%; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px auto;">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
            </div>
            <h3 style="font-size: 17px; font-weight: 800; color: #991b1b; margin-bottom: 8px;">PERINGATAN: TERDETEKSI PINDAH TAB / APLIKASI!</h3>
            <p style="font-size: 13px; color: #475569; line-height: 1.6; margin-bottom: 14px;">
                Sistem keamanan CBT mendeteksi Anda meninggalkan lembar ujian atau membuka aplikasi lain. Aktivitas ini telah <strong>DICATAT OLEH SISTEM</strong> dan dilaporkan ke Proktor Ujian!
            </p>
            <div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; font-weight: 700; padding: 10px; border-radius: 6px; font-size: 13.5px; margin-bottom: 20px;">
                Total Pelanggaran Anda: <span id="violation-count-badge" style="font-size: 16px;">1</span> kali
            </div>
            <button type="button" onclick="closeWarningSwitchModal()" style="background: #dc2626; color: #ffffff; border: none; padding: 11px 24px; font-weight: 700; font-size: 13.5px; border-radius: 4px; cursor: pointer;">
                SAYA MENGERTI & KEMBALI MENGERJAKAN
            </button>
        </div>
    </div>

    <script>
        // Data Soal & Jawaban dari Server
        const questions = @json($soalList);
        const initialAnswers = @json($jawabanList);
        let remainingSeconds = {{ $peserta->sisa_detik }};
        const jadwalId = {{ $jadwal->id }};
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // State Penyimpanan Lokal Siswa (Zero Data Loss Protection)
        const storageKey = `cbt_ans_${jadwalId}_{{ $siswa->id }}`;
        let localAnswers = {};
        try {
            const rawLocal = localStorage.getItem(storageKey);
            if (rawLocal) {
                localAnswers = JSON.parse(rawLocal);
            }
        } catch (e) {
            console.warn('localStorage tidak tersedia:', e);
        }

        // State Jawaban Siswa (Prioritaskan server, fallback ke local storage jika ada jawaban yang tertunda)
        let answersState = {};
        let pendingSyncQueue = [];

        questions.forEach(q => {
            const serverSaved = initialAnswers[q.id];
            const localSaved = localAnswers[q.id];

            let answerVal = serverSaved ? serverSaved.jawaban_siswa : null;
            let doubtVal = serverSaved ? Boolean(serverSaved.ragu_ragu) : false;

            if (!answerVal && localSaved && localSaved.jawaban) {
                answerVal = localSaved.jawaban;
                doubtVal = Boolean(localSaved.ragu_ragu);
                pendingSyncQueue.push({
                    soal_id: q.id,
                    jawaban: answerVal,
                    ragu_ragu: doubtVal
                });
            }

            answersState[q.id] = {
                jawaban: answerVal,
                ragu_ragu: doubtVal
            };
        });

        let currentIndex = 0;
        let baseFontSize = 16;

        function initApp() {
            renderNumberGrid();
            loadQuestion(0);
            startTimer();
        }

        function safeRenderText(text) {
            if (!text) return '';
            const temp = document.createElement('textarea');
            temp.innerHTML = text;
            const raw = temp.value;

            const div = document.createElement('div');
            div.textContent = raw;
            return div.innerHTML.replace(/\n/g, '<br>');
        }

        function loadQuestion(index) {
            if (index < 0 || index >= questions.length) return;
            currentIndex = index;

            const q = questions[currentIndex];
            const jenisBadge = q.jenis_soal === 'benar_salah' ? ' (BENAR/SALAH)' : (q.jenis_soal === 'uraian' ? ' (URAIAN)' : '');
            document.getElementById('current-question-title').textContent = 'SOAL NOMOR ' + (currentIndex + 1) + jenisBadge;
            document.getElementById('question-text-el').innerHTML = safeRenderText(q.konten_soal);

            // Update Media Jika Ada
            let mediaHtml = '';
            if (q.gambar_soal) {
                let imgSrc = q.gambar_soal;
                if (!imgSrc.startsWith('http://') && !imgSrc.startsWith('https://')) {
                    imgSrc = '/' + imgSrc.replace(/^\/+/, '');
                }
                mediaHtml += `<div style="margin-bottom: 14px; text-align: center;"><img src="${imgSrc}" alt="Gambar Soal" onclick="openImageZoom('${imgSrc}')" title="Klik untuk memperbesar gambar" style="max-height: 280px; max-width: 100%; object-fit: contain; border: 1px solid var(--border); border-radius: var(--radius-sm); cursor: zoom-in; box-shadow: 0 1px 3px rgba(0,0,0,0.08);"><div style="font-size: 11px; color: #64748b; margin-top: 4px;">&#128269; Ketuk/klik gambar untuk memperbesar</div></div>`;
            }
            if (q.audio_file) {
                mediaHtml += `<div style="margin-bottom: 14px;"><audio controls style="max-width: 100%; width: 280px;"><source src="/${q.audio_file}"></audio></div>`;
            }

            // Update Status Ragu-Ragu Checkbox
            const isDoubt = answersState[q.id]?.ragu_ragu || false;
            document.getElementById('chk-doubt').checked = isDoubt;
            const btnDoubt = document.getElementById('btn-doubt');
            if (isDoubt) {
                btnDoubt.classList.add('active');
            } else {
                btnDoubt.classList.remove('active');
            }

            // Render Opsi
            const container = document.getElementById('options-container');
            container.innerHTML = mediaHtml;

            const currentAns = answersState[q.id]?.jawaban;

            if (q.jenis_soal === 'pg') {
                const options = [
                    { key: 'A', text: q.opsi_a },
                    { key: 'B', text: q.opsi_b },
                    { key: 'C', text: q.opsi_c },
                    { key: 'D', text: q.opsi_d },
                    { key: 'E', text: q.opsi_e }
                ];

                options.forEach(opt => {
                    if (!opt.text) return;

                    const isSelected = (currentAns === opt.key);
                    const optDiv = document.createElement('div');
                    optDiv.className = 'option-item' + (isSelected ? ' selected' : '');
                    optDiv.onclick = () => selectOption(opt.key);

                    optDiv.innerHTML = `
                        <div class="option-letter">${opt.key}</div>
                        <div class="option-text">${safeRenderText(opt.text)}</div>
                    `;
                    container.appendChild(optDiv);
                });
            } else if (q.jenis_soal === 'benar_salah') {
                const bsOptions = [
                    { key: 'BENAR', label: 'A', text: q.opsi_a || 'Benar' },
                    { key: 'SALAH', label: 'B', text: q.opsi_b || 'Salah' },
                ];

                bsOptions.forEach(opt => {
                    const isSelected = (currentAns === opt.key);
                    const optDiv = document.createElement('div');
                    optDiv.className = 'option-item' + (isSelected ? ' selected' : '');
                    optDiv.onclick = () => selectOption(opt.key);

                    optDiv.innerHTML = `
                        <div class="option-letter">${opt.label}</div>
                        <div class="option-text"><strong>${opt.key}:</strong> ${safeRenderText(opt.text)}</div>
                    `;
                    container.appendChild(optDiv);
                });
            } else {
                // Uraian
                const uraianBox = document.createElement('div');
                uraianBox.style.cssText = 'background: #f8fafc; border: 1.5px solid var(--border); border-radius: var(--radius-sm); padding: 12px; margin-top: 10px;';
                uraianBox.innerHTML = `
                    <label style="display: block; font-weight: 600; font-size: 13px; color: var(--text-main); margin-bottom: 6px;">Ketikkan Lembar Jawaban Uraian Anda:</label>
                    <textarea rows="6" placeholder="Ketikkan uraian jawaban secara lengkap di sini..." style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13.5px; font-family: inherit; resize: vertical;" onblur="saveAnswerToServer(${q.id}, this.value, answersState[${q.id}].ragu_ragu)" oninput="answersState[${q.id}].jawaban = this.value; renderNumberGrid();">${currentAns || ''}</textarea>
                    <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Jawaban tersimpan otomatis saat Anda berpindah soal atau menekan tombol berikutnya.</div>
                `;
                container.appendChild(uraianBox);
            }

            // Update Nav Buttons
            document.getElementById('btn-prev').disabled = (currentIndex === 0);
            document.getElementById('btn-prev').style.opacity = (currentIndex === 0) ? '0.5' : '1';

            updateGridActiveState();

            // Render rumus matematika KaTeX
            if (window.renderMathInElement) {
                renderMathInElement(document.getElementById('question-content-area'), {
                    delimiters: [
                        {left: '$$', right: '$$', display: true},
                        {left: '$', right: '$', display: false}
                    ]
                });
            }
        }

        function selectOption(key) {
            const q = questions[currentIndex];
            answersState[q.id].jawaban = key;

            // Re-render Opsi
            loadQuestion(currentIndex);
            renderNumberGrid();

            // Simpan ke Server secara Async
            saveAnswerToServer(q.id, key, answersState[q.id].ragu_ragu);
        }

        function toggleDoubt() {
            const q = questions[currentIndex];
            answersState[q.id].ragu_ragu = !answersState[q.id].ragu_ragu;

            loadQuestion(currentIndex);
            renderNumberGrid();

            saveAnswerToServer(q.id, answersState[q.id].jawaban, answersState[q.id].ragu_ragu);
        }

        function prevQuestion() {
            if (currentIndex > 0) {
                loadQuestion(currentIndex - 1);
            }
        }

        function nextQuestion() {
            if (currentIndex < questions.length - 1) {
                loadQuestion(currentIndex + 1);
            } else {
                openFinishModal();
            }
        }

        function renderNumberGrid() {
            const grid = document.getElementById('number-grid');
            grid.innerHTML = '';
            let answeredCount = 0;

            questions.forEach((q, idx) => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'num-btn';

                const state = answersState[q.id];
                const hasAns = Boolean(state.jawaban && String(state.jawaban).trim() !== '');
                if (state.ragu_ragu) {
                    btn.classList.add('doubt');
                    answeredCount++;
                } else if (hasAns) {
                    btn.classList.add('answered');
                    answeredCount++;
                }

                if (idx === currentIndex) {
                    btn.classList.add('active');
                }

                btn.textContent = (idx + 1);
                if (hasAns && !state.ragu_ragu) {
                    const code = document.createElement('span');
                    code.className = 'ans-code';
                    const ansStr = String(state.jawaban).trim();
                    code.textContent = ansStr.length > 5 ? '✓' : ansStr;
                    btn.appendChild(code);
                }

                btn.onclick = () => {
                    closeNavDrawer();
                    loadQuestion(idx);
                };
                grid.appendChild(btn);
            });

            const drawerCounter = document.getElementById('drawer-answered-count');
            if (drawerCounter) {
                drawerCounter.textContent = answeredCount;
            }
        }

        function updateGridActiveState() {
            const btns = document.querySelectorAll('.num-btn');
            btns.forEach((btn, idx) => {
                if (idx === currentIndex) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        }

        function updateSyncBadge(status, text) {
            const badge = document.getElementById('sync-status-badge');
            const dot = document.getElementById('sync-status-dot');
            const label = document.getElementById('sync-status-text');
            if (!badge || !dot || !label) return;

            if (status === 'saving') {
                badge.style.background = 'rgba(14, 165, 233, 0.15)';
                badge.style.borderColor = 'rgba(14, 165, 233, 0.4)';
                badge.style.color = '#7dd3fc';
                dot.style.background = '#0ea5e9';
                label.textContent = text || 'Menyimpan...';
            } else if (status === 'saved') {
                badge.style.background = 'rgba(34, 197, 94, 0.15)';
                badge.style.borderColor = 'rgba(34, 197, 94, 0.3)';
                badge.style.color = '#86efac';
                dot.style.background = '#22c55e';
                label.textContent = text || 'Semua Tersimpan';
            } else if (status === 'offline') {
                badge.style.background = 'rgba(239, 68, 68, 0.2)';
                badge.style.borderColor = 'rgba(239, 68, 68, 0.4)';
                badge.style.color = '#fca5a5';
                dot.style.background = '#ef4444';
                label.textContent = text || 'Offline (Aman di Komputer)';
            }
        }

        function saveAnswerToServer(soalId, jawaban, ragu) {
            // Amankan data ke penyimpanan lokal browser siswa
            try {
                localStorage.setItem(storageKey, JSON.stringify(answersState));
            } catch (e) {}

            updateSyncBadge('saving', 'Menyimpan jawaban...');

            fetch(`/siswa/ujian/${jadwalId}/simpan-jawaban`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    soal_id: soalId,
                    jawaban: jawaban,
                    ragu_ragu: ragu,
                    sisa_detik: remainingSeconds
                })
            })
            .then(res => {
                if (res.ok) {
                    updateSyncBadge('saved', 'Semua Tersimpan');
                    pendingSyncQueue = pendingSyncQueue.filter(item => item.soal_id !== soalId);
                } else {
                    throw new Error('HTTP ' + res.status);
                }
            })
            .catch(err => {
                console.warn('Simpan server tertunda. Jawaban aman di penyimpanan lokal:', err);
                updateSyncBadge('offline', 'Offline (Aman di Komputer)');
                if (!pendingSyncQueue.some(item => item.soal_id === soalId)) {
                    pendingSyncQueue.push({
                        soal_id: soalId,
                        jawaban: jawaban,
                        ragu_ragu: ragu
                    });
                }
            });
        }

        // Background Auto-Sync Loop untuk item pending (tiap 4 detik)
        setInterval(function () {
            if (pendingSyncQueue.length === 0) return;
            const item = pendingSyncQueue[0];
            fetch(`/siswa/ujian/${jadwalId}/simpan-jawaban`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    soal_id: item.soal_id,
                    jawaban: item.jawaban,
                    ragu_ragu: item.ragu_ragu,
                    sisa_detik: remainingSeconds
                })
            })
            .then(res => {
                if (res.ok) {
                    pendingSyncQueue.shift();
                    if (pendingSyncQueue.length === 0) {
                        updateSyncBadge('saved', 'Semua Tersimpan');
                    }
                }
            })
            .catch(() => {});
        }, 4000);

        function startTimer() {
            const display = document.getElementById('timer-display');
            const timerBox = document.getElementById('timer-box');

            function updateDisplay() {
                if (remainingSeconds <= 0) {
                    display.textContent = '00:00:00';
                    isExamFinished = true;
                    try { localStorage.removeItem(storageKey); } catch(e) {}
                    alert('Waktu ujian telah habis! Sistem akan menyelesaikan lembar jawaban Anda.');
                    document.querySelector('#finish-modal form').submit();
                    return;
                }

                const hrs = Math.floor(remainingSeconds / 3600);
                const mins = Math.floor((remainingSeconds % 3600) / 60);
                const secs = remainingSeconds % 60;

                display.textContent = 
                    String(hrs).padStart(2, '0') + ':' +
                    String(mins).padStart(2, '0') + ':' +
                    String(secs).padStart(2, '0');

                if (remainingSeconds <= 300) {
                    timerBox.classList.add('timer-warning');
                }

                remainingSeconds--;
            }

            updateDisplay();
            setInterval(updateDisplay, 1000);
        }

        function changeFontSize(direction) {
            if (direction === 0) baseFontSize = 16;
            else baseFontSize += direction * 2;
            baseFontSize = Math.max(12, Math.min(24, baseFontSize));
            document.getElementById('question-content-area').style.fontSize = baseFontSize + 'px';
            document.getElementById('question-text-el').style.fontSize = baseFontSize + 'px';
        }

        function openFinishModal() {
            let answered = 0;
            let doubt = 0;
            let unanswered = 0;

            questions.forEach(q => {
                const s = answersState[q.id];
                const hasAns = Boolean(s.jawaban && String(s.jawaban).trim() !== '');
                if (s.ragu_ragu) doubt++;
                else if (hasAns) answered++;
                else unanswered++;
            });

            document.getElementById('sum-answered').textContent = answered;
            document.getElementById('sum-doubt').textContent = doubt;
            document.getElementById('sum-unanswered').textContent = unanswered;

            document.getElementById('finish-modal').style.display = 'flex';
        }

        function closeFinishModal() {
            document.getElementById('finish-modal').style.display = 'none';
        }

        function toggleNavDrawer() {
            const drawer = document.getElementById('nav-panel-drawer');
            if (drawer) {
                drawer.classList.toggle('drawer-open');
            }
        }

        function closeNavDrawer() {
            const drawer = document.getElementById('nav-panel-drawer');
            if (drawer) {
                drawer.classList.remove('drawer-open');
            }
        }

        function openImageZoom(src) {
            const modal = document.getElementById('image-zoom-modal');
            const preview = document.getElementById('image-zoom-preview');
            if (modal && preview) {
                preview.src = src;
                modal.style.display = 'flex';
            }
        }

        function closeImageZoom() {
            const modal = document.getElementById('image-zoom-modal');
            if (modal) {
                modal.style.display = 'none';
            }
        }

        // ==========================================
        // SISTEM ANTI-CHEAT & FULL-SCREEN LOCK
        // ==========================================
        let violationCount = {{ $peserta->jumlah_pelanggaran ?? 0 }};
        let isExamFinished = false;
        const isTouchDevice = ('ontouchstart' in window) || (navigator.maxTouchPoints > 0);
        const supportsFullscreen = Boolean(
            document.documentElement.requestFullscreen ||
            document.documentElement.webkitRequestFullscreen ||
            document.documentElement.mozRequestFullScreen ||
            document.documentElement.msRequestFullscreen
        );

        function checkFullscreen() {
            if (isExamFinished) return;
            const overlay = document.getElementById('fullscreen-overlay');
            // Pada tablet iOS/browser tertentu yang tidak mendukung Fullscreen API, jangan blokir akses ujian
            if (!supportsFullscreen || isTouchDevice) {
                if (overlay) overlay.style.display = 'none';
                return;
            }
            const isFull = Boolean(document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement);
            if (!isFull) {
                overlay.style.display = 'flex';
            } else {
                overlay.style.display = 'none';
            }
        }

        document.getElementById('btn-request-fullscreen').addEventListener('click', function () {
            const el = document.documentElement;
            const rfs = el.requestFullscreen || el.webkitRequestFullscreen || el.mozRequestFullScreen || el.msRequestFullscreen;
            if (rfs) {
                rfs.call(el).then(() => {
                    document.getElementById('fullscreen-overlay').style.display = 'none';
                }).catch(() => {
                    document.getElementById('fullscreen-overlay').style.display = 'none';
                });
            } else {
                document.getElementById('fullscreen-overlay').style.display = 'none';
            }
        });

        document.addEventListener('fullscreenchange', checkFullscreen);
        document.addEventListener('webkitfullscreenchange', checkFullscreen);
        document.addEventListener('mozfullscreenchange', checkFullscreen);
        document.addEventListener('MSFullscreenChange', checkFullscreen);

        function triggerTabViolation() {
            if (isExamFinished) return;
            violationCount++;
            document.getElementById('violation-count-badge').textContent = violationCount;
            document.getElementById('warning-switch-modal').style.display = 'flex';

            fetch('{{ route("siswa.pelanggaran", $jadwal->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ type: 'tab_switch', count: violationCount })
            }).catch(() => {});
        }

        document.addEventListener('visibilitychange', function () {
            if (document.hidden) {
                triggerTabViolation();
            }
        });

        window.addEventListener('blur', function () {
            // Hindari false-alarm di tablet saat keyboard virtual / dialog browser muncul
            const activeTag = document.activeElement ? document.activeElement.tagName : '';
            if (isTouchDevice || activeTag === 'TEXTAREA' || activeTag === 'INPUT') {
                return;
            }
            if (!document.hidden) {
                triggerTabViolation();
            }
        });

        window.closeWarningSwitchModal = function () {
            document.getElementById('warning-switch-modal').style.display = 'none';
            checkFullscreen();
        };

        // Blokir klik kanan
        document.addEventListener('contextmenu', function (e) {
            e.preventDefault();
            return false;
        });

        // Blokir kombinasi tombol curang (F12, DevTools, Copy, Paste, Print, View Source)
        document.addEventListener('keydown', function (e) {
            if (e.key === 'F12' ||
                (e.ctrlKey && e.shiftKey && ['I', 'i', 'J', 'j', 'C', 'c'].includes(e.key)) ||
                (e.ctrlKey && ['u', 'U', 'p', 'P', 's', 'S'].includes(e.key))
            ) {
                e.preventDefault();
                return false;
            }
        });

        // Proteksi accidental tab close / reload
        window.addEventListener('beforeunload', function (e) {
            if (!isExamFinished) {
                e.preventDefault();
                e.returnValue = 'Ujian Anda sedang berlangsung. Jawaban Anda tersimpan otomatis.';
                return e.returnValue;
            }
        });

        window.onload = function() {
            initApp();
            checkFullscreen();
        };
    </script>
</body>
</html>
