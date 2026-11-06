<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Asesmen CBT &bull; SMK Negeri 1 Kupang Barat</title>
    <meta name="description" content="Aplikasi CBT Berstandar TKA Pusmendik Kemdikbud - SMK Negeri 1 Kupang Barat">
    <meta name="author" content="Vicky Koroh, S.Pd., M.Kom">
    <meta name="copyright" content="Hak Cipta &copy; Vicky Koroh">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <style>
        :root {
            --brand-primary: #0f2b48;
            --brand-secondary: #0077b6;
            --brand-accent: #00b4d8;
            --bg-dark-1: #061527;
            --bg-dark-2: #0c2b54;
            --bg-dark-3: #081d3a;
            --surface-white: #ffffff;
            --surface-muted: #f8fafc;
            --border-light: #e2e8f0;
            --border-focus: #0077b6;
            --text-heading: #0f172a;
            --text-body: #334155;
            --text-muted: #64748b;
            --radius-sm: 4px;
            --radius-md: 6px;
            --radius-lg: 8px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: linear-gradient(145deg, var(--bg-dark-1) 0%, var(--bg-dark-2) 50%, var(--bg-dark-3) 100%);
            color: var(--text-body);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px 16px;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }

        @keyframes subtleFadeUp {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-container {
            width: 100%;
            max-width: 980px;
            background: var(--surface-white);
            border-radius: var(--radius-lg);
            box-shadow: 0 12px 36px rgba(0, 0, 0, 0.35), 0 0 0 1px rgba(255, 255, 255, 0.1);
            display: grid;
            grid-template-columns: 1.15fr 1fr;
            overflow: hidden;
            position: relative;
            z-index: 2;
            animation: subtleFadeUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .auth-info-panel {
            background: linear-gradient(165deg, #091f38 0%, #0d2f59 60%, #07172c 100%);
            color: #ffffff;
            padding: clamp(28px, 4vw, 40px);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border-right: 1px solid rgba(255, 255, 255, 0.08);
            position: relative;
        }

        .school-logos {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
        }

        .logo-box {
            width: 52px;
            height: 52px;
            object-fit: contain;
            filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.3));
        }

        .kop-text h4 {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #93c5fd;
            line-height: 1.3;
        }

        .kop-text h2 {
            font-size: 18px;
            font-weight: 800;
            letter-spacing: -0.3px;
            color: #ffffff;
            margin-top: 3px;
        }

        .kop-text p {
            font-size: 12px;
            color: #cbd5e1;
            margin-top: 2px;
        }

        .system-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(0, 180, 216, 0.15);
            border: 1px solid rgba(0, 180, 216, 0.35);
            padding: 6px 12px;
            border-radius: var(--radius-sm);
            font-size: 12px;
            font-weight: 600;
            color: #e0f2fe;
            margin: 20px 0 24px 0;
        }

        .system-badge .dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #34d399;
            box-shadow: 0 0 6px #34d399;
        }

        .feature-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 24px;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            font-size: 13px;
            color: #e2e8f0;
            line-height: 1.45;
        }

        .feature-icon {
            width: 20px;
            height: 20px;
            color: #38bdf8;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .info-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 16px;
            font-size: 11px;
            color: #94a3b8;
            line-height: 1.5;
        }

        .info-footer b {
            color: #f1f5f9;
        }

        .auth-form-panel {
            padding: clamp(28px, 4vw, 40px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #ffffff;
        }

        .form-header {
            margin-bottom: 24px;
        }

        .form-header h3 {
            font-size: 20px;
            font-weight: 800;
            color: var(--text-heading);
            letter-spacing: -0.3px;
        }

        .form-header p {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .role-tabs {
            display: flex;
            background: var(--surface-muted);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-md);
            padding: 3px;
            margin-bottom: 22px;
        }

        .role-tab {
            flex: 1;
            padding: 8px 12px;
            font-size: 12px;
            font-weight: 700;
            text-align: center;
            border: none;
            background: transparent;
            color: var(--text-muted);
            cursor: pointer;
            border-radius: var(--radius-sm);
            transition: all 0.2s ease;
        }

        .role-tab.active {
            background: #ffffff;
            color: var(--brand-primary);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: var(--text-heading);
            margin-bottom: 6px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 12px;
            width: 18px;
            height: 18px;
            color: #94a3b8;
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            height: 44px;
            padding: 0 14px 0 38px;
            font-size: 14px;
            border: 1px solid var(--border-light);
            border-radius: var(--radius-md);
            background: #ffffff;
            color: var(--text-heading);
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--border-focus);
            box-shadow: 0 0 0 3px rgba(0, 119, 182, 0.12);
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            cursor: pointer;
            color: #94a3b8;
            padding: 4px;
            display: flex;
            align-items: center;
        }

        .toggle-password:hover {
            color: var(--text-body);
        }

        .form-footer-action {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            margin-bottom: 22px;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            color: var(--text-body);
            font-weight: 500;
        }

        .btn-submit {
            width: 100%;
            height: 44px;
            background: linear-gradient(135deg, #0077b6 0%, #0096c7 100%);
            border: none;
            border-radius: var(--radius-md);
            color: #ffffff;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 2px 6px rgba(0, 119, 182, 0.3);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 119, 182, 0.4);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .copyright-tag {
            margin-top: 24px;
            text-align: center;
            font-size: 11px;
            color: var(--text-muted);
            line-height: 1.5;
        }

        .copyright-tag strong {
            color: var(--text-heading);
            font-weight: 700;
        }

        @media (max-width: 768px) {
            .auth-container {
                grid-template-columns: 1fr;
            }
            .auth-info-panel {
                padding: 24px;
                border-right: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }
            .auth-form-panel {
                padding: 24px;
            }
        }
    </style>
</head>
<body>

    <div class="auth-container">
        <div class="auth-info-panel">
            <div>
                <div class="school-logos">
                    <img src="{{ asset('images/logo_smk.png') }}" alt="Logo SMKN 1 Kupang Barat" class="logo-box">
                    <img src="{{ asset('images/logo_pemprov_ntt.svg') }}" alt="Logo Pemprov NTT" class="logo-box" style="width: 46px; height: 46px;">
                    <div class="kop-text">
                        <h4>PEMPROV NUSA TENGGARA TIMUR</h4>
                        <h2>SMKN 1 KUPANG BARAT</h2>
                        <p>Desa Kuanheun, Kec. Kupang Barat</p>
                    </div>
                </div>

                <div class="system-badge">
                    <span class="dot"></span>
                    <span>Sistem CBT Standar TKA / ANBK Pusmendik</span>
                </div>

                <ul class="feature-list">
                    <li class="feature-item">
                        <svg class="feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        <div>
                            <strong>Paket Soal Terstandar</strong>
                            <p style="font-size: 12px; color: #94a3b8; margin-top: 2px;">Mendukung Pilihan Ganda, Benar/Salah, dan Uraian dengan bobot 25 & 40 soal presisi.</p>
                        </div>
                    </li>
                    <li class="feature-item">
                        <svg class="feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                        <div>
                            <strong>Penyimpanan Jawaban Otomatis</strong>
                            <p style="font-size: 12px; color: #94a3b8; margin-top: 2px;">Dilengkapi dual-storage auto-save lokal browser & server. Aman jika jaringan terputus.</p>
                        </div>
                    </li>
                    <li class="feature-item">
                        <svg class="feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                        <div>
                            <strong>Kartu & Berita Acara Dinas</strong>
                            <p style="font-size: 12px; color: #94a3b8; margin-top: 2px;">Cetak kartu ujian A4 resmi dengan TTD Kepala Sekolah Vicky Koroh, S.Pd., M.Kom.</p>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="info-footer">
                <div>Aplikasi Resmi Ujian Berbasis Komputer &bull; <b>SMK Negeri 1 Kupang Barat</b></div>
                <div>Dikembangkan secara khusus oleh <b>Vicky Koroh, S.Pd., M.Kom</b></div>
            </div>
        </div>

        <div class="auth-form-panel">
            <div class="form-header">
                <h3>Masuk Sesi Asesmen</h3>
                <p>Silahkan masukkan nomor peserta atau akun login Anda.</p>
            </div>

            <div class="role-tabs">
                <button type="button" class="role-tab active" onclick="setRole('siswa', this)">Peserta Ujian (Siswa)</button>
                <button type="button" class="role-tab" onclick="setRole('proktor', this)">Proktor / Pengawas / Guru</button>
            </div>

            <form action="{{ url('/login') }}" method="POST" id="form-login">
                @csrf
                <input type="hidden" name="role_type" id="role_type" value="siswa">

                <div class="form-group">
                    <label class="form-label" id="label-username">Nomor Peserta / NISN</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan nomor peserta" required autocomplete="username" autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Password / PIN Ujian</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan kata sandi ujian" required autocomplete="current-password">
                        <button type="button" class="toggle-password" onclick="togglePass()" title="Tampilkan sandi">
                            <svg id="eye-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        </button>
                    </div>
                </div>

                <div class="form-footer-action">
                    <label class="remember-label">
                        <input type="checkbox" name="remember" id="remember" style="accent-color: #0077b6;">
                        <span>Simpan Sesi di Perangkat Ini</span>
                    </label>
                    <span style="color: #64748b; font-size: 11px;">Koneksi Aman LAN/WAN</span>
                </div>

                <button type="submit" class="btn-submit">
                    <span>Mulai Sesi Ujian</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </button>
            </form>

            <div class="copyright-tag">
                Hak Cipta &copy; {{ date('Y') }} <strong>Vicky Koroh</strong>. Seluruh Hak Cipta Dilindungi.<br>
                SMK Negeri 1 Kupang Barat &bull; Kuanheun
            </div>
        </div>
    </div>

    <script>
        function setRole(role, btn) {
            document.querySelectorAll('.role-tab').forEach(t => t.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('role_type').value = role;

            var userLabel = document.getElementById('label-username');
            var userInput = document.getElementById('username');

            if (role === 'siswa') {
                userLabel.textContent = 'Nomor Peserta / NISN';
                userInput.placeholder = 'Contoh: 0054819201 atau joko';
            } else {
                userLabel.textContent = 'Nama Pengguna (Username Admin / Guru)';
                userInput.placeholder = 'Masukkan username operator';
            }
        }

        function togglePass() {
            var pass = document.getElementById('password');
            if (pass.type === 'password') {
                pass.type = 'text';
            } else {
                pass.type = 'password';
            }
        }
    </script>
</body>
</html>
