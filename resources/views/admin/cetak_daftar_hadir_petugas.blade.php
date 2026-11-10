<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Hadir Petugas & Panitia Asesmen CBT &bull; SMKN 1 Kupang Barat</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm 15mm 12mm 15mm;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9pt;
            line-height: 1.25;
            color: #000;
            background: #f8fafc;
        }

        .no-print {
            padding: 10px 18px;
            background: #0f2b48;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            border-radius: 4px;
            font-size: 13px;
        }

        .print-container {
            max-width: 210mm;
            margin: 0 auto;
            background: #ffffff;
            padding: 15mm 18mm;
            box-shadow: 0 1px 4px rgba(0,0,0,0.1);
        }

        /* KOP RESMI SEKOLAH */
        .kop-table {
            width: 100%;
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .kop-table td {
            vertical-align: middle;
        }

        .kop-logo {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }

        .kop-text {
            text-align: center;
            padding: 0 10px;
        }

        .kop-text h4 {
            font-size: 10pt;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .kop-text h5 {
            font-size: 9.5pt;
            font-weight: 700;
            text-transform: uppercase;
        }

        .kop-text h3 {
            font-size: 12pt;
            font-weight: 800;
            letter-spacing: 0.8px;
            color: #000;
            margin: 2px 0;
            text-transform: uppercase;
        }

        .kop-text p {
            font-size: 7.5pt;
            color: #1e293b;
        }

        /* DOKUMEN TITLE */
        .doc-title {
            text-align: center;
            margin-bottom: 12px;
        }

        .doc-title h2 {
            font-size: 11pt;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            text-decoration: underline;
        }

        .doc-title p {
            font-size: 8.5pt;
            font-weight: bold;
            margin-top: 2px;
        }

        /* METADATA INFORMASI */
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
            margin-bottom: 12px;
            line-height: 1.4;
        }

        .meta-table td {
            padding: 2px 0;
            vertical-align: top;
        }

        .meta-table td.lbl {
            width: 140px;
            font-weight: bold;
        }

        .meta-table td.col {
            width: 10px;
            font-weight: bold;
        }

        .meta-table td.val {
            font-weight: 600;
        }

        /* TABEL PETUGAS */
        .table-petugas {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
            margin-bottom: 16px;
        }

        .table-petugas th, .table-petugas td {
            border: 1px solid #000;
            padding: 4px 6px;
        }

        .table-petugas th {
            background-color: #f1f5f9;
            font-weight: bold;
            text-align: center;
            height: 26px;
            font-size: 8pt;
            text-transform: uppercase;
        }

        .col-ttd {
            width: 140px;
            height: 24px;
            font-size: 7.5pt;
            vertical-align: middle;
        }

        .ttd-odd {
            padding-left: 8px;
        }

        .ttd-even {
            padding-left: 65px;
        }

        /* TANDA TANGAN PENGESAHAN */
        .ttd-section {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            font-size: 8.5pt;
            page-break-inside: avoid;
        }

        .ttd-box {
            width: 220px;
            text-align: center;
            line-height: 1.25;
        }

        .ttd-box img {
            height: 48px;
            margin: 2px auto;
            display: block;
        }

        @media print {
            body {
                background: #ffffff;
            }
            .no-print {
                display: none !important;
            }
            .print-container {
                padding: 0;
                box-shadow: none;
                max-width: 100%;
            }
            .table-petugas th {
                background-color: #e2e8f0 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <div>
            <strong>Daftar Hadir Panitia & Petugas Pengawas Asesmen CBT</strong> &bull; Total {{ count($daftarPetugas) }} Petugas Terdaftar
        </div>
        <div style="display: flex; gap: 8px;">
            <button onclick="window.print()" style="background: #0077b6; color: #fff; border: none; padding: 7px 16px; font-weight: bold; cursor: pointer; border-radius: 4px; font-size: 13px;">
                Cetak Dokumen (Print / PDF)
            </button>
        </div>
    </div>

    <div class="print-container">
        <!-- KOP RESMI -->
        <table class="kop-table">
            <tr>
                <td style="width: 70px; text-align: left;">
                    <img src="{{ $sekolah->logo_pemprov_url }}" class="kop-logo" alt="Logo Pemprov">
                </td>
                <td class="kop-text">
                    <h4>{{ $sekolah->kop_baris1 }}</h4>
                    <h5>{{ $sekolah->kop_baris2 }}</h5>
                    <h3>{{ $sekolah->kop_baris3 }}</h3>
                    <p>{{ $sekolah->kop_alamat_lengkap }}</p>
                </td>
                <td style="width: 70px; text-align: right;">
                    <img src="{{ $sekolah->logo_sekolah_url }}" class="kop-logo" alt="Logo SMK">
                </td>
            </tr>
        </table>

        <!-- JUDUL DOKUMEN -->
        <div class="doc-title">
            <h2>DAFTAR HADIR PANITIA & PETUGAS PENGAWAS ASESMEN</h2>
            <p>TAHUN PELAJARAN {{ $sekolah->tahun_ajaran_aktif ?? '2026/2027' }} &bull; SEMESTER {{ strtoupper($sekolah->semester_aktif ?? 'GENAP') }}</p>
        </div>

        <!-- METADATA INFORMASI -->
        <table class="meta-table">
            <tr>
                <td class="lbl">Satuan Pendidikan</td>
                <td class="col">:</td>
                <td class="val">{{ $sekolah->nama_sekolah }} (NPSN: {{ $sekolah->npsn }})</td>
                <td class="lbl" style="width: 100px;">Hari / Tanggal</td>
                <td class="col">:</td>
                <td class="val">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</td>
            </tr>
            <tr>
                <td class="lbl">Pelaksanaan Asesmen</td>
                <td class="col">:</td>
                <td class="val">{{ $jadwal->nama_ujian ?? 'Asesmen CBT Berbasis Komputer' }}</td>
                <td class="lbl">Lokasi / Ruang</td>
                <td class="col">:</td>
                <td class="val">Lab Komputer SMKN 1 Kupang Barat</td>
            </tr>
        </table>

        <!-- TABEL PETUGAS -->
        <table class="table-petugas">
            <thead>
                <tr>
                    <th style="width: 32px;">No.</th>
                    <th>Nama Lengkap Petugas</th>
                    <th style="width: 130px;">NIP / NUPTK</th>
                    <th style="width: 140px;">Jabatan / Penugasan</th>
                    <th style="width: 105px;">Ruangan / Sesi</th>
                    <th style="width: 140px; text-align: center;">Tanda Tangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($daftarPetugas as $idx => $p)
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}.</td>
                    <td>
                        <strong>{{ $p->user->name ?? '-' }}</strong>
                    </td>
                    <td style="text-align: center; font-family: monospace; font-size: 8pt;">
                        {{ $p->user->nip ?: ($p->user->nuptk ?: '-') }}
                    </td>
                    <td style="text-align: center;">
                        <span style="font-weight: 700; text-transform: uppercase;">{{ $p->jenis_petugas }}</span>
                    </td>
                    <td style="text-align: center; font-size: 8pt;">
                        {{ $p->ruangan }}<br>
                        <span style="color: #475569;">{{ $p->sesi }}</span>
                    </td>
                    <td class="col-ttd {{ ($idx % 2 == 0) ? 'ttd-odd' : 'ttd-even' }}">
                        {{ $idx + 1 }}. .............................
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px; color: #64748b;">
                        Belum ada data penugasan panitia atau pengawas ujian.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- PENGESAHAN DOKUMEN -->
        <div class="ttd-section">
            <div class="ttd-box">
                <div>Mengetahui,</div>
                <div>Ketua Panitia Asesmen,</div>
                <div style="height: 52px;"></div>
                <div><strong><u>....................................................</u></strong></div>
                <div>NIP. ..........................................</div>
            </div>

            <div class="ttd-box">
                <div>{{ $sekolah->titimangsa_lengkap }}</div>
                <div>Kepala Sekolah,</div>
                @if ($sekolah->ttd_kepala_sekolah && file_exists(public_path($sekolah->ttd_kepala_sekolah)))
                    <img src="{{ $sekolah->ttd_kepala_sekolah_url }}" alt="TTD">
                @else
                    <div style="height: 52px;"></div>
                @endif
                <div><strong><u>{{ $sekolah->kepala_sekolah }}</u></strong></div>
                <div>NIP. {{ $sekolah->nip_kepala_sekolah }}</div>
            </div>
        </div>
    </div>

</body>
</html>
