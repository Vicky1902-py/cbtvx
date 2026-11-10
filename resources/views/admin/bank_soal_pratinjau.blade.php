<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pratinjau Asesmen Siswa (Live Preview) &bull; {{ $bank->kode_bank }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <!-- KaTeX Rumus Matematika -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/contrib/auto-render.min.js"></script>

    <style>
        :root {
            --primary: #0f2b48;
            --accent: #0077b6;
            --accent-hover: #005b8c;
            --bg-page: #f1f5f9;
            --surface: #ffffff;
            --border: #cbd5e1;
            --border-light: #e2e8f0;
            --text-main: #0f172a;
            --text-body: #334155;
            --radius-sm: 4px;
            --radius: 6px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
            background: var(--bg-page);
            color: var(--text-body);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Banner Pratinjau Administrator */
        .preview-banner {
            background: #f59e0b;
            color: #ffffff;
            padding: 6px 16px;
            font-size: 12px;
            font-weight: 700;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .exam-header {
            background: var(--primary);
            color: #ffffff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid var(--accent);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .header-left img {
            width: 38px;
            height: 38px;
            object-fit: contain;
        }
        .header-left h1 {
            font-size: 14px;
            font-weight: 700;
            line-height: 1.2;
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

        .exam-body {
            flex: 1;
            display: grid;
            grid-template-columns: 1fr 300px;
            max-width: 1360px;
            width: 100%;
            margin: 0 auto;
            padding: 16px;
            gap: 16px;
        }

        .question-card {
            background: var(--surface);
            border: 1px solid var(--border-light);
            border-radius: var(--radius);
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            display: flex;
            flex-direction: column;
        }

        .question-header {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-light);
            background: #fafbfc;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .question-number-badge {
            font-weight: 700;
            font-size: 14px;
            color: var(--primary);
        }

        .font-sizer {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            color: #64748b;
        }

        .btn-size {
            background: #ffffff;
            border: 1px solid var(--border);
            padding: 2px 7px;
            border-radius: 3px;
            font-size: 11px;
            cursor: pointer;
            font-weight: bold;
        }

        .question-content {
            padding: 20px;
            flex: 1;
        }

        .question-text {
            font-size: 14px;
            line-height: 1.6;
            color: var(--text-main);
            margin-bottom: 20px;
        }

        .options-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .option-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 10px 14px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            background: #ffffff;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .option-item:hover {
            border-color: var(--accent);
            background: #f0f9ff;
        }

        .option-item.selected {
            border-color: var(--accent);
            background: #e0f2fe;
        }

        .option-letter {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: #f1f5f9;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 12px;
            color: var(--text-main);
            flex-shrink: 0;
        }

        .option-item.selected .option-letter {
            background: var(--accent);
            color: #ffffff;
            border-color: var(--accent);
        }

        .option-text {
            font-size: 13.5px;
            line-height: 1.5;
            flex: 1;
            padding-top: 2px;
        }

        .exam-action-bar {
            padding: 12px 20px;
            border-top: 1px solid var(--border-light);
            background: #fafbfc;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-nav {
            padding: 8px 14px;
            font-size: 12.5px;
            font-weight: 600;
            border-radius: var(--radius-sm);
            cursor: pointer;
            border: 1px solid transparent;
        }

        .btn-prev { background: #ffffff; border-color: var(--border); color: #334155; }
        .btn-doubt { background: #fef3c7; border-color: #fde68a; color: #b45309; }
        .btn-doubt.active { background: #f59e0b; color: #ffffff; }
        .btn-next { background: var(--accent); color: #ffffff; }

        .nav-panel {
            background: var(--surface);
            border: 1px solid var(--border-light);
            border-radius: var(--radius);
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            display: flex;
            flex-direction: column;
            height: fit-content;
        }

        .nav-panel-header {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-light);
            background: #fafbfc;
            font-weight: 700;
            font-size: 13px;
            color: var(--text-main);
            display: flex;
            justify-content: space-between;
        }

        .number-grid {
            padding: 14px;
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 8px;
            max-height: 420px;
            overflow-y: auto;
        }

        .num-btn {
            aspect-ratio: 1;
            border: 1.5px solid var(--border);
            background: #ffffff;
            border-radius: var(--radius-sm);
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .num-btn.active { box-shadow: 0 0 0 2px var(--accent); }
        .num-btn.answered { background: var(--accent); border-color: var(--accent); color: #ffffff; }
        .num-btn.doubt { background: #f59e0b; border-color: #d97706; color: #ffffff; }
    </style>
</head>
<body>

    <div class="preview-banner">
        <div>
            <strong>MODE PRATINJAU SISWA:</strong> Tampilan simulator pengerjaan ujian standar ANBK Pusmendik untuk paket <code>{{ $bank->kode_bank }}</code>
        </div>
        <div>
            <a href="{{ route('admin.bank_soal.detail', $bank->id) }}" style="color: #ffffff; text-decoration: underline; font-size: 11.5px;">&larr; Tutup Pratinjau</a>
        </div>
    </div>

    <header class="exam-header">
        <div class="header-left">
            <img src="{{ asset('images/logo_smk.png') }}" alt="Logo SMK">
            <div>
                <h1>SMK NEGERI 1 KUPANG BARAT</h1>
                <p>SIMULASI ASESMEN CBT &bull; SISWA CONTOH (NISN: 0012345678)</p>
            </div>
        </div>

        <div class="header-center">
            <div class="exam-name">{{ $bank->nama_bank ?: $bank->kode_bank }}</div>
            <div class="exam-sub">{{ $bank->mataPelajaran->nama_mapel ?? 'Mata Pelajaran' }} ({{ $bank->mataPelajaran->jurusan ?? 'Semua Jurusan' }})</div>
        </div>

        <div style="text-align: right; font-family: monospace; font-size: 16px; color: #38bdf8; background: #07172c; padding: 4px 10px; border-radius: 4px; border: 1px solid rgba(255,255,255,0.2);">
            01:29:45
        </div>
    </header>

    <div class="exam-body">
        <div class="question-card">
            <div class="question-header">
                <div class="question-number-badge" id="q-title">SOAL NOMOR 1</div>
                <div class="font-sizer">
                    <span>Ukuran Teks:</span>
                    <button type="button" class="btn-size" onclick="changeFontSize(-1)">A-</button>
                    <button type="button" class="btn-size" onclick="changeFontSize(0)">A</button>
                    <button type="button" class="btn-size" onclick="changeFontSize(1)">A+</button>
                </div>
            </div>

            <div class="question-content" id="q-content-box">
                <div class="question-text" id="q-text"></div>
                <div id="q-media-box" style="margin-bottom: 16px;"></div>
                <div class="options-list" id="q-options"></div>
            </div>

            <div class="exam-action-bar">
                <button type="button" class="btn-nav btn-prev" id="btn-prev" onclick="prevQ()">&larr; Soal Sebelumnya</button>
                <button type="button" class="btn-nav btn-doubt" id="btn-doubt" onclick="toggleDoubt()">Ragu-ragu</button>
                <button type="button" class="btn-nav btn-next" id="btn-next" onclick="nextQ()">Soal Berikutnya &rarr;</button>
            </div>
        </div>

        <aside class="nav-panel">
            <div class="nav-panel-header">
                <span>Daftar Soal</span>
                <span style="font-size: 11px; color: #64748b;">Total: {{ $soalList->count() }} Butir</span>
            </div>
            <div class="number-grid" id="number-grid"></div>
        </aside>
    </div>

    <script>
        const questions = @json($soalList);
        let currentIndex = 0;
        let answersState = {};

        questions.forEach(q => {
            answersState[q.id] = { jawaban: '', ragu: false };
        });

        let currentFontSize = 14;
        function changeFontSize(dir) {
            if (dir === 0) currentFontSize = 14;
            else currentFontSize += (dir * 2);
            if (currentFontSize < 12) currentFontSize = 12;
            if (currentFontSize > 22) currentFontSize = 22;
            document.getElementById('q-content-box').style.fontSize = currentFontSize + 'px';
            document.getElementById('q-text').style.fontSize = currentFontSize + 'px';
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

        function loadQuestion(idx) {
            if (questions.length === 0) {
                document.getElementById('q-text').textContent = 'Belum ada butir soal pada bank soal ini.';
                return;
            }
            currentIndex = idx;
            const q = questions[idx];

            document.getElementById('q-title').textContent = 'SOAL NOMOR ' + q.nomor_urut + ' (' + q.jenis_soal.toUpperCase() + ' - ' + q.bobot + ' Poin)';
            document.getElementById('q-text').innerHTML = safeRenderText(q.konten_soal);

            // Media
            const mediaBox = document.getElementById('q-media-box');
            mediaBox.innerHTML = '';
            if (q.gambar_soal) {
                mediaBox.innerHTML += `<div style="margin-bottom: 10px;"><img src="/${q.gambar_soal}" style="max-height: 240px; max-width: 100%; border: 1px solid var(--border); border-radius: 4px;"></div>`;
            }
            if (q.audio_file) {
                mediaBox.innerHTML += `<div style="margin-bottom: 10px;"><audio controls style="width: 280px;"><source src="/${q.audio_file}"></audio></div>`;
            }

            // Opsi Jawaban
            const optBox = document.getElementById('q-options');
            optBox.innerHTML = '';

            const currentAns = answersState[q.id].jawaban;

            if (q.jenis_soal === 'pg') {
                const choices = [
                    { k: 'A', t: q.opsi_a },
                    { k: 'B', t: q.opsi_b },
                    { k: 'C', t: q.opsi_c },
                    { k: 'D', t: q.opsi_d },
                    { k: 'E', t: q.opsi_e },
                ];
                choices.forEach(c => {
                    if (!c.t) return;
                    const isSel = (currentAns === c.k);
                    const isKey = (q.kunci_jawaban && q.kunci_jawaban.toUpperCase() === c.k);
                    const div = document.createElement('div');
                    div.className = 'option-item' + (isSel ? ' selected' : '');
                    if (isKey) {
                        div.style.borderColor = '#16a34a';
                        div.style.backgroundColor = '#f0fdf4';
                    }
                    div.onclick = () => selectAns(c.k);
                    div.innerHTML = `
                        <div class="option-letter" style="${isKey ? 'background:#16a34a;color:#ffffff;border-color:#16a34a;' : ''}">${c.k}</div>
                        <div class="option-text">${safeRenderText(c.t)}</div>
                        ${isKey ? '<span style="font-size:11px;font-weight:700;color:#16a34a;background:#dcfce7;border:1px solid #bbf7d0;padding:2px 8px;border-radius:4px;white-space:nowrap;margin-left:auto;">✓ Kunci Jawaban</span>' : ''}
                    `;
                    optBox.appendChild(div);
                });
            } else if (q.jenis_soal === 'benar_salah') {
                const choices = [
                    { k: 'BENAR', t: q.opsi_a || 'Benar' },
                    { k: 'SALAH', t: q.opsi_b || 'Salah' },
                ];
                choices.forEach(c => {
                    const isSel = (currentAns === c.k);
                    const isKey = (q.kunci_jawaban && q.kunci_jawaban.toUpperCase() === c.k);
                    const div = document.createElement('div');
                    div.className = 'option-item' + (isSel ? ' selected' : '');
                    if (isKey) {
                        div.style.borderColor = '#16a34a';
                        div.style.backgroundColor = '#f0fdf4';
                    }
                    div.onclick = () => selectAns(c.k);
                    div.innerHTML = `
                        <div class="option-letter" style="${isKey ? 'background:#16a34a;color:#ffffff;border-color:#16a34a;' : ''}">${c.k === 'BENAR' ? 'A' : 'B'}</div>
                        <div class="option-text"><strong>${c.k}:</strong> ${safeRenderText(c.t)}</div>
                        ${isKey ? '<span style="font-size:11px;font-weight:700;color:#16a34a;background:#dcfce7;border:1px solid #bbf7d0;padding:2px 8px;border-radius:4px;white-space:nowrap;margin-left:auto;">✓ Kunci Jawaban</span>' : ''}
                    `;
                    optBox.appendChild(div);
                });
            } else {
                optBox.innerHTML = `
                    <div style="background: #f8fafc; border: 1px solid var(--border); padding: 12px; border-radius: 4px;">
                        <label style="display: block; font-weight: 600; font-size: 12px; margin-bottom: 6px;">Simulasi Lembar Jawaban Uraian:</label>
                        <textarea rows="4" placeholder="Ketikkan uraian jawaban..." style="width: 100%; padding: 8px; border: 1px solid var(--border); border-radius: 4px; font-size: 13px;" oninput="selectAns(this.value)">${currentAns}</textarea>
                    </div>
                `;
            }

            // KOTAK INFORMASI KUNCI JAWABAN & RUBRIK RESMI (KHUSUS PRATINJAU GURU / ADMIN)
            const infoDiv = document.createElement('div');
            infoDiv.style.cssText = 'margin-top: 20px; padding: 12px 16px; background: #f8fafc; border: 1px solid #cbd5e1; border-left: 4px solid #0284c7; border-radius: var(--radius-sm); font-size: 12.5px;';
            infoDiv.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                    <strong style="color: #0369a1; text-transform: uppercase; font-size: 11.5px;">Kunci Jawaban & Rubrik Penilaian Resmi:</strong>
                    <span style="font-weight: 700; background: #e0f2fe; color: #0369a1; padding: 2px 8px; border-radius: 4px; font-size: 11px;">Bobot: ${q.bobot} Poin</span>
                </div>
                ${q.kunci_jawaban ? `<div style="margin-bottom: 6px;"><strong>Kunci Jawaban:</strong> <span style="display: inline-block; background: #dcfce7; color: #166534; font-weight: 700; padding: 1px 8px; border-radius: 4px; border: 1px solid #bbf7d0;">${q.kunci_jawaban}</span></div>` : ''}
                ${q.rubrik_uraian ? `<div><strong>Pembahasan / Rubrik Penilaian:</strong><div style="margin-top: 4px; color: #334155; line-height: 1.5; background: #ffffff; padding: 8px 12px; border-radius: 4px; border: 1px solid #e2e8f0; white-space: pre-line;">${safeRenderText(q.rubrik_uraian)}</div></div>` : ''}
            `;
            optBox.appendChild(infoDiv);

            // Nav buttons state
            document.getElementById('btn-prev').disabled = (idx === 0);
            document.getElementById('btn-prev').style.opacity = (idx === 0) ? '0.5' : '1';
            document.getElementById('btn-next').disabled = (idx === questions.length - 1);
            document.getElementById('btn-next').style.opacity = (idx === questions.length - 1) ? '0.5' : '1';

            const doubtBtn = document.getElementById('btn-doubt');
            if (answersState[q.id].ragu) {
                doubtBtn.classList.add('active');
            } else {
                doubtBtn.classList.remove('active');
            }

            renderGrid();

            // Render KaTeX
            if (window.renderMathInElement) {
                renderMathInElement(document.getElementById('q-content-box'), {
                    delimiters: [
                        {left: '$$', right: '$$', display: true},
                        {left: '$', right: '$', display: false}
                    ]
                });
            }
        }

        function selectAns(val) {
            const q = questions[currentIndex];
            answersState[q.id].jawaban = val;
            loadQuestion(currentIndex);
        }

        function toggleDoubt() {
            const q = questions[currentIndex];
            answersState[q.id].ragu = !answersState[q.id].ragu;
            loadQuestion(currentIndex);
        }

        function prevQ() {
            if (currentIndex > 0) loadQuestion(currentIndex - 1);
        }

        function nextQ() {
            if (currentIndex < questions.length - 1) loadQuestion(currentIndex + 1);
        }

        function renderGrid() {
            const grid = document.getElementById('number-grid');
            grid.innerHTML = '';
            questions.forEach((q, idx) => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'num-btn';
                if (idx === currentIndex) btn.classList.add('active');
                if (answersState[q.id].ragu) btn.classList.add('doubt');
                else if (answersState[q.id].jawaban) btn.classList.add('answered');

                btn.textContent = (idx + 1);
                btn.onclick = () => loadQuestion(idx);
                grid.appendChild(btn);
            });
        }

        document.addEventListener("DOMContentLoaded", function() {
            loadQuestion(0);
        });
    </script>
</body>
</html>
