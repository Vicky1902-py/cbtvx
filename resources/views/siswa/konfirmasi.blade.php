<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Asesmen &bull; SMKN 1 Kupang Barat</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <style>
        :root {
            --primary: #0f2b48;
            --accent: #0077b6;
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
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .confirm-card {
            background: var(--surface);
            border: 1px solid var(--border-light);
            border-radius: var(--radius);
            box-shadow: 0 4px 16px rgba(0,0,0,0.06);
            width: 100%;
            max-width: 580px;
            overflow: hidden;
        }

        .confirm-header {
            background: var(--primary);
            color: #ffffff;
            padding: 18px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .confirm-header img {
            width: 36px;
            height: 36px;
            object-fit: contain;
        }

        .confirm-header h2 {
            font-size: 14px;
            font-weight: 700;
        }

        .confirm-header p {
            font-size: 11px;
            color: #94a3b8;
        }

        .confirm-body {
            padding: 24px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 13.5px;
        }

        .info-table td {
            padding: 8px 0;
            border-bottom: 1px solid var(--border-light);
        }

        .info-table td.label {
            color: var(--text-muted);
            width: 160px;
        }

        .token-input {
            width: 100%;
            padding: 12px;
            border: 2px solid var(--border);
            border-radius: var(--radius);
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 4px;
            text-align: center;
            text-transform: uppercase;
            font-family: monospace;
            color: var(--primary);
            margin-top: 8px;
            margin-bottom: 16px;
        }

        .token-input:focus {
            border-color: var(--accent);
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 119, 182, 0.15);
        }

        .btn-start {
            background: var(--accent);
            color: #ffffff;
            border: none;
            padding: 12px;
            font-size: 14px;
            font-weight: 700;
            border-radius: var(--radius);
            width: 100%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-start:hover {
            background: #005b8c;
        }

        .btn-back {
            display: block;
            text-align: center;
            margin-top: 14px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 13px;
        }

        .btn-back:hover {
            color: var(--text-main);
        }
    </style>
</head>
<body>

    <div class="confirm-card">
        <div class="confirm-header">
            <img src="{{ asset('images/logo_smk.png') }}" alt="Logo SMK">
            <div>
                <h2>KONFIRMASI TES ASESMEN CBT</h2>
                <p>SMK Negeri 1 Kupang Barat &bull; Standar TKA Pusmendik</p>
            </div>
        </div>

        <div class="confirm-body">
            @if ($errors->any())
                <div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 10px 14px; border-radius: var(--radius); font-size: 13px; margin-bottom: 16px;">
                    {{ $errors->first() }}
                </div>
            @endif

            <table class="info-table">
                <tr>
                    <td class="label">Nama Peserta</td>
                    <td><strong>{{ $siswa->nama_lengkap }}</strong></td>
                </tr>
                <tr>
                    <td class="label">NISN / Nomor Peserta</td>
                    <td>{{ $siswa->nisn }} &bull; {{ $siswa->nomor_peserta ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Kelas / Rombel</td>
                    <td><strong>{{ $siswa->kelas->nama_kelas ?? '-' }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Nama Asesmen</td>
                    <td><strong style="color: var(--accent);">{{ $jadwal->nama_ujian }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Mata Pelajaran</td>
                    <td>{{ $jadwal->bankSoal->mataPelajaran->nama_mapel ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Alokasi Waktu</td>
                    <td><strong>{{ $jadwal->durasi_menit }} Menit</strong></td>
                </tr>
                <tr>
                    <td class="label">Jumlah Butir Soal</td>
                    <td>{{ $jadwal->bankSoal->butirSoal->count() }} Butir Soal (Pilihan Ganda)</td>
                </tr>
            </table>

            <form action="{{ route('siswa.mulai', $jadwal->id) }}" method="POST">
                @csrf
                <label style="display: block; font-weight: 600; font-size: 13px; text-align: center;">
                    Masukkan Token Ujian dari Pengawas:
                </label>
                <input type="text" name="token" class="token-input" placeholder="TOKEN" required autocomplete="off" autofocus maxlength="10">

                <button type="submit" class="btn-start">
                    <span>Mulai Mengerjakan Sekarang</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </button>
            </form>

            <a href="{{ route('siswa.dashboard') }}" class="btn-back">&larr; Kembali ke Daftar Ujian</a>
        </div>
    </div>

</body>
</html>
