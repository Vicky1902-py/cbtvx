<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ujian CBT &bull; {{ $jadwal->nama_ujian }} &bull; SMKN 1 Kupang Barat</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <!-- KaTeX Rumus Matematika (ANBK / TKA) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.css">
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

        @media (max-width: 900px) {
            .exam-body {
                grid-template-columns: 1fr;
            }
            .nav-panel {
                order: 2;
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
                <div class="font-sizer">
                    <span>Ukuran Teks:</span>
                    <button type="button" class="btn-size" onclick="changeFontSize(-1)">A-</button>
                    <button type="button" class="btn-size" onclick="changeFontSize(0)">A</button>
                    <button type="button" class="btn-size" onclick="changeFontSize(1)">A+</button>
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
        <aside class="nav-panel">
            <div class="nav-panel-header">
                <span>Daftar Nomor Soal</span>
                <span style="font-size: 11px; font-weight: normal; color: var(--text-muted);">Total: {{ $soalList->count() }}</span>
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
                <form action="{{ route('siswa.selesai', $jadwal->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-nav btn-finish">Ya, Selesai Sekarang</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Data Soal & Jawaban dari Server
        const questions = @json($soalList);
        const initialAnswers = @json($jawabanList);
        let remainingSeconds = {{ $peserta->sisa_detik }};
        const jadwalId = {{ $jadwal->id }};
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // State Jawaban Siswa
        let answersState = {};
        questions.forEach(q => {
            const saved = initialAnswers[q.id];
            answersState[q.id] = {
                jawaban: saved ? saved.jawaban_siswa : null,
                ragu_ragu: saved ? Boolean(saved.ragu_ragu) : false
            };
        });

        let currentIndex = 0;
        let baseFontSize = 16;

        function initApp() {
            renderNumberGrid();
            loadQuestion(0);
            startTimer();
        }

        function loadQuestion(index) {
            if (index < 0 || index >= questions.length) return;
            currentIndex = index;

            const q = questions[currentIndex];
            const jenisBadge = q.jenis_soal === 'benar_salah' ? ' (BENAR/SALAH)' : (q.jenis_soal === 'uraian' ? ' (URAIAN)' : '');
            document.getElementById('current-question-title').textContent = 'SOAL NOMOR ' + (currentIndex + 1) + jenisBadge;
            document.getElementById('question-text-el').innerHTML = q.konten_soal.replace(/\n/g, '<br>');

            // Update Media Jika Ada
            let mediaHtml = '';
            if (q.gambar_soal) {
                mediaHtml += `<div style="margin-bottom: 14px;"><img src="/${q.gambar_soal}" alt="Gambar Soal" style="max-height: 260px; max-width: 100%; border: 1px solid var(--border); border-radius: var(--radius-sm);"></div>`;
            }
            if (q.audio_file) {
                mediaHtml += `<div style="margin-bottom: 14px;"><audio controls style="width: 280px;"><source src="/${q.audio_file}"></audio></div>`;
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
                        <div class="option-text">${opt.text}</div>
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
                        <div class="option-text"><strong>${opt.key}:</strong> ${opt.text}</div>
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

            questions.forEach((q, idx) => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'num-btn';

                const state = answersState[q.id];
                if (state.ragu_ragu) {
                    btn.classList.add('doubt');
                } else if (state.jawaban) {
                    btn.classList.add('answered');
                }

                if (idx === currentIndex) {
                    btn.classList.add('active');
                }

                btn.textContent = (idx + 1);
                if (state.jawaban && !state.ragu_ragu) {
                    const code = document.createElement('span');
                    code.className = 'ans-code';
                    code.textContent = state.jawaban;
                    btn.appendChild(code);
                }

                btn.onclick = () => loadQuestion(idx);
                grid.appendChild(btn);
            });
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

        function saveAnswerToServer(soalId, jawaban, ragu) {
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
            }).catch(err => console.log('Offline auto-saved locally'));
        }

        function startTimer() {
            const display = document.getElementById('timer-display');
            const timerBox = document.getElementById('timer-box');

            function updateDisplay() {
                if (remainingSeconds <= 0) {
                    display.textContent = '00:00:00';
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
                if (s.ragu_ragu) doubt++;
                else if (s.jawaban) answered++;
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

        window.onload = initApp;
    </script>
</body>
</html>
