<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Kartu Peserta Ujian &bull; SMKN 1 Kupang Barat</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #ffffff;
            color: #000000;
            font-size: 11px;
            line-height: 1.3;
        }

        .cards-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .card-item {
            border: 1.5px solid #000000;
            padding: 8px 10px;
            border-radius: 4px;
            page-break-inside: avoid;
            background: #ffffff;
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 8px;
            border-bottom: 2px solid #000000;
            padding-bottom: 6px;
            margin-bottom: 6px;
        }

        .card-header img {
            width: 38px;
            height: 38px;
            object-fit: contain;
        }

        .header-text {
            flex: 1;
            text-align: center;
        }

        .header-text h4 {
            font-size: 9px;
            font-weight: 700;
            margin-bottom: 1px;
        }

        .header-text h3 {
            font-size: 11px;
            font-weight: 800;
            margin-bottom: 1px;
        }

        .header-text p {
            font-size: 8px;
            color: #333333;
        }

        .card-title {
            text-align: center;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: #eeeeee;
            padding: 3px 0;
            border: 1px solid #cccccc;
            margin-bottom: 6px;
        }

        .card-body {
            display: flex;
            gap: 8px;
        }

        .photo-box {
            width: 60px;
            height: 75px;
            border: 1px solid #999999;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            color: #666666;
            text-align: center;
            flex-shrink: 0;
            background: #fafafa;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5px;
        }

        .data-table td {
            padding: 1.5px 2px;
            vertical-align: top;
        }

        .data-table td.label {
            width: 80px;
            font-weight: 600;
        }

        .auth-box {
            margin-top: 5px;
            border: 1px dashed #444444;
            padding: 3px 6px;
            background: #fdfdfd;
            display: flex;
            justify-content: space-between;
            font-size: 9px;
        }

        .auth-box strong {
            font-family: monospace;
            font-size: 10.5px;
        }

        .card-footer {
            margin-top: 6px;
            display: flex;
            justify-content: flex-end;
            text-align: center;
            font-size: 8.5px;
        }

        .ttd-block {
            width: 140px;
        }

        .ttd-img {
            height: 32px;
            margin: 2px 0;
            object-fit: contain;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="no-print" style="padding: 12px; background: #0f2b48; color: #ffffff; display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
        <div>
            <strong>Pratinjau Kartu Peserta Asesmen &bull; Format Standar A4 Dinas</strong>
            <span style="font-size: 11px; opacity: 0.8; margin-left: 10px;">Total Peserta: {{ $daftarSiswa->count() }} Siswa</span>
        </div>
        <button onclick="window.print()" style="background: #0077b6; color: #ffffff; border: none; padding: 8px 16px; font-weight: bold; cursor: pointer; border-radius: 4px;">
            Cetak Dokumen Sekarang (Print / PDF)
        </button>
    </div>

    <div class="cards-grid">
        @foreach ($daftarSiswa as $siswa)
        <div class="card-item">
            <div class="card-header">
                <img src="{{ asset('images/logo_pemprov_ntt.svg') }}" alt="Logo Pemprov">
                <div class="header-text">
                    <h4>PEMERINTAH PROVINSI NUSA TENGGARA TIMUR</h4>
                    <h3>{{ $sekolah->nama_sekolah ?? 'SMK NEGERI 1 KUPANG BARAT' }}</h3>
                    <p>{{ $sekolah->alamat ?? 'Desa Kuanheun' }}, Kec. {{ $sekolah->kecamatan ?? 'Kupang Barat' }}</p>
                </div>
                <img src="{{ asset('images/logo_smk.png') }}" alt="Logo SMK">
            </div>

            <div class="card-title">KARTU PESERTA ASESMEN / CBT TP. {{ $sekolah->tahun_ajaran_aktif ?? '2026/2027' }}</div>

            <div class="card-body">
                <div class="photo-box">
                    FOTO<br>2 x 3
                </div>
                <div style="flex: 1;">
                    <table class="data-table">
                        <tr>
                            <td class="label">Nomor Peserta</td>
                            <td style="width: 5px;">:</td>
                            <td><strong>{{ $siswa->nomor_peserta ?? '-' }}</strong></td>
                        </tr>
                        <tr>
                            <td class="label">Nama Lengkap</td>
                            <td>:</td>
                            <td><strong>{{ strtoupper($siswa->nama_lengkap) }}</strong></td>
                        </tr>
                        <tr>
                            <td class="label">NISN / NIS</td>
                            <td>:</td>
                            <td>{{ $siswa->nisn }} / {{ $siswa->nis ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Kelas / Rombel</td>
                            <td>:</td>
                            <td>{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Ruang & Sesi</td>
                            <td>:</td>
                            <td>{{ $siswa->ruangan_ujian }} &bull; {{ $siswa->sesi_ujian }}</td>
                        </tr>
                    </table>

                    <div class="auth-box">
                        <div>User: <strong>{{ $siswa->nisn }}</strong></div>
                        <div>Pass: <strong>12345</strong></div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <div class="ttd-block">
                    <div>Kupang Barat, {{ date('d F Y') }}</div>
                    <div>Kepala Sekolah,</div>
                    <img src="{{ asset($sekolah->ttd_kepala_sekolah ?? 'images/ttd_kepsek.png') }}" alt="TTD Kepsek" class="ttd-img"><br>
                    <strong><u>{{ $sekolah->kepala_sekolah ?? 'Vicky Koroh, S.Pd., M.Kom.' }}</u></strong><br>
                    <span>NIP. {{ $sekolah->nip_kepala_sekolah ?? '198501192011011005' }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</body>
</html>
