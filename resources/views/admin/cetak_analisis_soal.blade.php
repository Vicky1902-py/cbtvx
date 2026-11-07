<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Analisis Butir Soal &bull; {{ $jadwal->nama_ujian }} &bull; SMKN 1 Kupang Barat</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 15mm;
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
            font-size: 10.5px;
            line-height: 1.35;
        }

        .header {
            display: flex;
            align-items: center;
            border-bottom: 2.5px solid #000000;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .header-logo {
            width: 65px;
            height: 65px;
            object-fit: contain;
        }

        .header-center {
            flex: 1;
            text-align: center;
            padding: 0 10px;
        }

        .header-center h4 {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .header-center h3 {
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            margin: 2px 0;
        }

        .header-center h2 {
            font-size: 15px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .header-center p {
            font-size: 9.5px;
            margin-top: 2px;
        }

        .doc-title {
            text-align: center;
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            margin-bottom: 12px;
            text-decoration: underline;
        }

        .meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-bottom: 12px;
            font-size: 10.5px;
        }

        .meta-table td {
            padding: 2px 4px;
            vertical-align: top;
        }

        .meta-table td.label {
            width: 120px;
            font-weight: 600;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin-bottom: 16px;
        }

        .data-table th, .data-table td {
            border: 1px solid #000000;
            padding: 5px 6px;
        }

        .data-table th {
            background: #f1f5f9;
            font-weight: 700;
            text-align: center;
            text-transform: uppercase;
            font-size: 9px;
        }

        .footer-ttd {
            display: flex;
            justify-content: space-between;
            margin-top: 18px;
            page-break-inside: avoid;
            font-size: 11px;
        }

        .ttd-box {
            width: 230px;
            text-align: center;
        }

        .ttd-space {
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .ttd-img {
            max-height: 55px;
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

    <div class="no-print" style="padding: 12px 20px; background: #0f2b48; color: #ffffff; display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <div>
            <strong>Pratinjau Cetak Analisis Butir Soal (A4 Resmi)</strong>
            <span style="margin-left: 10px; opacity: 0.8; font-size: 12px;">Total Soal: {{ count($analisisItems) }} Butir</span>
        </div>
        <button onclick="window.print()" style="background: #0077b6; color: #ffffff; border: none; padding: 8px 16px; font-weight: bold; cursor: pointer; border-radius: 4px;">
            Cetak Sekarang (Print / PDF)
        </button>
    </div>

    <!-- KOP RESMI SEKOLAH -->
    <div class="header">
        <img src="{{ $sekolah->logo_pemprov_url }}" alt="Logo Pemprov" class="header-logo">
        <div class="header-center">
            <h4>{{ $sekolah->kop_baris1 }}</h4>
            <h3>{{ $sekolah->kop_baris2 }}</h3>
            <h2>{{ $sekolah->kop_baris3 }}</h2>
            <p>{{ $sekolah->kop_alamat_lengkap }}</p>
        </div>
        <img src="{{ $sekolah->logo_sekolah_url }}" alt="Logo SMK" class="header-logo">
    </div>

    <div class="doc-title">LAPORAN ANALISIS BUTIR SOAL ASESMEN</div>

    <div class="meta-grid">
        <table class="meta-table">
            <tr>
                <td class="label">Nama Ujian</td>
                <td style="width: 5px;">:</td>
                <td><strong>{{ strtoupper($jadwal->nama_ujian) }}</strong></td>
            </tr>
            <tr>
                <td class="label">Mata Pelajaran</td>
                <td>:</td>
                <td>{{ $jadwal->bankSoal->mataPelajaran->nama_mapel ?? 'Semua Mapel' }}</td>
            </tr>
            <tr>
                <td class="label">Kode Bank Soal</td>
                <td>:</td>
                <td>{{ $jadwal->bankSoal->kode_bank ?? '-' }}</td>
            </tr>
        </table>
        <table class="meta-table">
            <tr>
                <td class="label">Tahun Pelajaran</td>
                <td style="width: 5px;">:</td>
                <td>{{ $sekolah->tahun_ajaran_aktif ?? '2026/2027' }} &bull; Semester {{ $sekolah->semester_aktif ?? 'Genap' }}</td>
            </tr>
            <tr>
                <td class="label">Jumlah Peserta Uji</td>
                <td>:</td>
                <td><strong>{{ $totalPeserta }} Siswa</strong></td>
            </tr>
            <tr>
                <td class="label">Standar KKM</td>
                <td>:</td>
                <td>{{ $jadwal->bankSoal->kkm ?? 75 }}</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 35px;">No.</th>
                <th style="width: 55px;">Tipe</th>
                <th style="width: 45px;">Kunci</th>
                <th style="width: 50px;">Benar</th>
                <th style="width: 50px;">Salah</th>
                <th style="width: 50px;">Kosong</th>
                <th style="width: 80px;">Taraf Kesukaran (P)</th>
                <th style="width: 85px;">Kategori Kesukaran</th>
                <th style="width: 80px;">Daya Pembeda (D)</th>
                <th>Rekomendasi Butir</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($analisisItems as $item)
            <tr>
                <td style="text-align: center; font-weight: 700;">{{ $item['nomor'] }}</td>
                <td style="text-align: center;">{{ $item['tipe'] }}</td>
                <td style="text-align: center; font-weight: 700;">{{ $item['kunci'] }}</td>
                <td style="text-align: center;">{{ $item['benar'] }}</td>
                <td style="text-align: center;">{{ $item['salah'] }}</td>
                <td style="text-align: center;">{{ $item['kosong'] }}</td>
                <td style="text-align: center;">{{ $item['p_index'] }}</td>
                <td style="text-align: center;">{{ $item['kesukaran'] }}</td>
                <td style="text-align: center;">{{ $item['d_index'] }}</td>
                <td style="text-align: center; font-weight: 600;">{{ $item['rekomendasi'] }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" style="text-align: center; padding: 20px;">Belum ada butir soal yang dianalisis.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TANDA TANGAN RESMI -->
    <div class="footer-ttd">
        <div class="ttd-box">
            <div>Mengetahui,</div>
            <div>Kepala SMKN 1 Kupang Barat,</div>
            <div class="ttd-space">
                <img src="{{ $sekolah->ttd_kepala_sekolah_url }}" alt="TTD Kepsek" class="ttd-img">
            </div>
            <div><strong><u>{{ $sekolah->kepala_sekolah }}</u></strong></div>
            <div>NIP. {{ $sekolah->nip_kepala_sekolah }}</div>
        </div>

        <div class="ttd-box">
            <div>Kupang Barat, {{ date('d F Y') }}</div>
            <div>Guru Pengampu Mata Pelajaran,</div>
            <div class="ttd-space">
                <div style="font-size: 10px; color: #94a3b8; font-style: italic;">(Tanda Tangan Asli)</div>
            </div>
            <div><strong><u>{{ $jadwal->bankSoal->guru->name ?? 'Guru Mata Pelajaran' }}</u></strong></div>
            <div>NIP. {{ $jadwal->bankSoal->guru->nip ?? '-' }}</div>
        </div>
    </div>

</body>
</html>
