<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Hadir Ujian CBT &bull; SMKN 1 Kupang Barat</title>
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
            font-size: 10pt;
            line-height: 1.3;
            color: #000;
        }

        .kop-surat {
            display: flex;
            align-items: center;
            border-bottom: 2.5px solid #000;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }

        .kop-surat img {
            width: 50px;
            height: 50px;
            object-fit: contain;
        }

        .kop-text {
            flex: 1;
            text-align: center;
            padding: 0 8px;
        }

        .kop-text h4 {
            font-size: 9pt;
            font-weight: normal;
        }

        .kop-text h2 {
            font-size: 12pt;
            font-weight: bold;
        }

        .kop-text p {
            font-size: 8pt;
            color: #333;
        }

        .judul-dokumen {
            text-align: center;
            margin-bottom: 14px;
        }

        .judul-dokumen h3 {
            font-size: 11pt;
            font-weight: bold;
            text-decoration: underline;
        }

        .judul-dokumen p {
            font-size: 9pt;
        }

        .table-info {
            width: 100%;
            margin-bottom: 12px;
            font-size: 9pt;
        }

        .table-info td {
            padding: 2px 0;
            vertical-align: top;
        }

        .table-hadir {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
            margin-bottom: 16px;
        }

        .table-hadir th {
            background: #f0f0f0;
            border: 1px solid #000;
            padding: 6px 4px;
            text-align: center;
            font-weight: bold;
        }

        .table-hadir td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: middle;
        }

        .ttd-container {
            display: flex;
            justify-content: space-between;
            font-size: 9pt;
            margin-top: 14px;
            page-break-inside: avoid;
        }

        .ttd-box {
            width: 200px;
            text-align: center;
        }

        .ttd-img {
            height: 38px;
            margin: 2px auto;
            display: block;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="no-print" style="padding: 10px; background: #0f2b48; color: #fff; display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <span>Pratinjau Daftar Hadir Resmi Ujian (Format A4)</span>
        <button onclick="window.print()" style="background: #0077b6; color: #fff; border: none; padding: 6px 14px; font-weight: bold; cursor: pointer; border-radius: 4px;">
            Cetak Daftar Hadir (Print / PDF)
        </button>
    </div>

    <div class="kop-surat">
        <img src="{{ asset('images/logo_pemprov_ntt.svg') }}" alt="Logo Pemprov">
        <div class="kop-text">
            <h4>PEMERINTAH PROVINSI NUSA TENGGARA TIMUR</h4>
            <h4>DINAS PENDIDIKAN DAN KEBUDAYAAN</h4>
            <h2>{{ $sekolah->nama_sekolah ?? 'SMK NEGERI 1 KUPANG BARAT' }}</h2>
            <p>{{ $sekolah->alamat ?? 'Desa Kuanheun, RT 02 / RW 02' }}, Kec. {{ $sekolah->kecamatan ?? 'Kupang Barat' }}, {{ $sekolah->kabupaten ?? 'Kab. Kupang' }} &bull; NPSN: {{ $sekolah->npsn ?? '69727541' }}</p>
        </div>
        <img src="{{ asset('images/logo_smk.png') }}" alt="Logo SMK">
    </div>

    <div class="judul-dokumen">
        <h3>DAFTAR HADIR PESERTA ASESMEN / UJIAN CBT</h3>
        <p>Tahun Pelajaran: {{ $sekolah->tahun_ajaran_aktif ?? '2026/2027' }} - Semester {{ $sekolah->semester_aktif ?? 'Genap' }}</p>
    </div>

    <table class="table-info">
        <tr>
            <td style="width: 140px;">Mata Pelajaran</td>
            <td style="width: 10px;">:</td>
            <td><strong>{{ $jadwal->bankSoal->mataPelajaran->nama_mapel ?? '-' }}</strong></td>
            <td style="width: 140px;">Hari / Tanggal</td>
            <td style="width: 10px;">:</td>
            <td>{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</td>
        </tr>
        <tr>
            <td>Nama Asesmen</td>
            <td>:</td>
            <td>{{ $jadwal->nama_ujian }}</td>
            <td>Alokasi Waktu</td>
            <td>:</td>
            <td>{{ $jadwal->durasi_menit }} Menit</td>
        </tr>
        <tr>
            <td>Token Ujian</td>
            <td>:</td>
            <td><strong>{{ $jadwal->token_ujian }}</strong></td>
            <td>Total Peserta</td>
            <td>:</td>
            <td>{{ $siswaList->count() }} Orang</td>
        </tr>
    </table>

    <table class="table-hadir">
        <thead>
            <tr>
                <th style="width: 30px;">No.</th>
                <th style="width: 110px;">No. Peserta</th>
                <th style="width: 80px;">NISN</th>
                <th>Nama Lengkap Peserta</th>
                <th style="width: 80px;">Kelas</th>
                <th style="width: 140px;" colspan="2">Tanda Tangan</th>
                <th style="width: 70px;">Ket.</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($siswaList as $idx => $s)
            <tr>
                <td style="text-align: center;">{{ $idx + 1 }}</td>
                <td>{{ $s->nomor_peserta ?? '-' }}</td>
                <td>{{ $s->nisn }}</td>
                <td><strong>{{ strtoupper($s->nama_lengkap) }}</strong></td>
                <td style="text-align: center;">{{ $s->kelas->nama_kelas ?? '-' }}</td>
                @if (($idx + 1) % 2 != 0)
                    <td style="width: 70px; height: 26px; vertical-align: top; font-size: 7.5pt; color: #555;">{{ $idx + 1 }}. ......</td>
                    <td style="width: 70px;"></td>
                @else
                    <td style="width: 70px;"></td>
                    <td style="width: 70px; height: 26px; vertical-align: top; font-size: 7.5pt; color: #555;">{{ $idx + 1 }}. ......</td>
                @endif
                <td style="text-align: center; font-size: 8pt;">Hadir</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="ttd-container">
        <div class="ttd-box">
            <p>Pengawas Ruang,</p>
            <div style="height: 45px;"></div>
            <p><strong><u>{{ $beritaAcara->nama_pengawas ?? 'Potifera Buan, S.Pd' }}</u></strong></p>
            <p style="font-size: 8pt;">NIP. {{ $beritaAcara->nip_pengawas ?? '198911042024212044' }}</p>
        </div>

        <div class="ttd-box">
            <p>Proktor Ujian,</p>
            <div style="height: 45px;"></div>
            <p><strong><u>{{ $beritaAcara->nama_proktor ?? 'Izak Robinson Koroh, S.Pd' }}</u></strong></p>
            <p style="font-size: 8pt;">NIP. {{ $beritaAcara->nip_proktor ?? '199002192020121003' }}</p>
        </div>

        <div class="ttd-box">
            <p>Mengetahui,</p>
            <p>Kepala Sekolah,</p>
            <img src="{{ asset($sekolah->ttd_kepala_sekolah ?? 'images/ttd_kepsek.png') }}" alt="TTD Kepsek" class="ttd-img">
            <p><strong><u>{{ $sekolah->kepala_sekolah ?? 'Vicky Koroh, S.Pd., M.Kom.' }}</u></strong></p>
            <p style="font-size: 8pt;">NIP. {{ $sekolah->nip_kepala_sekolah ?? '198501192011011005' }}</p>
        </div>
    </div>

</body>
</html>
