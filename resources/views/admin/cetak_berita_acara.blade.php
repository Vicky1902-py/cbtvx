<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara Pelaksanaan Ujian &bull; SMKN 1 Kupang Barat</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 20mm;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
        }

        .kop-surat {
            display: flex;
            align-items: center;
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 20px;
        }

        .kop-surat img {
            width: 70px;
            height: 70px;
            object-fit: contain;
        }

        .kop-text {
            flex: 1;
            text-align: center;
            padding: 0 10px;
        }

        .kop-text h4 {
            font-size: 12pt;
            font-weight: normal;
        }

        .kop-text h2 {
            font-size: 14pt;
            font-weight: bold;
        }

        .kop-text p {
            font-size: 10pt;
            font-style: italic;
        }

        .judul-dokumen {
            text-align: center;
            margin-bottom: 24px;
        }

        .judul-dokumen h3 {
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
        }

        .judul-dokumen p {
            font-size: 11pt;
        }

        .paragraf {
            text-align: justify;
            margin-bottom: 14px;
        }

        .table-info {
            width: 100%;
            margin-bottom: 16px;
        }

        .table-info td {
            padding: 3px 0;
            vertical-align: top;
        }

        .table-info td.label {
            width: 220px;
        }

        .table-info td.colon {
            width: 15px;
        }

        .ttd-container {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }

        .ttd-box {
            width: 220px;
            text-align: center;
        }

        .ttd-img {
            height: 60px;
            margin: 5px auto;
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

    <div class="no-print" style="padding: 10px; background: #0f2b48; color: #fff; display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; font-family: sans-serif; font-size: 13px;">
        <span>Pratinjau Dokumen Berita Acara Resmi Ujian</span>
        <button onclick="window.print()" style="background: #0077b6; color: #fff; border: none; padding: 6px 14px; font-weight: bold; cursor: pointer; border-radius: 4px;">
            Cetak Berita Acara (Print / PDF)
        </button>
    </div>

    <div class="kop-surat">
        <img src="{{ asset('images/logo_pemprov_ntt.svg') }}" alt="Logo Pemprov">
        <div class="kop-text">
            <h4>PEMERINTAH PROVINSI NUSA TENGGARA TIMUR</h4>
            <h4>DINAS PENDIDIKAN DAN KEBUDAYAAN</h4>
            <h2>{{ $sekolah->nama_sekolah ?? 'SMK NEGERI 1 KUPANG BARAT' }}</h2>
            <p>Alamat: {{ $sekolah->alamat ?? 'Desa Kuanheun' }}, Kec. {{ $sekolah->kecamatan ?? 'Kupang Barat' }}, {{ $sekolah->kabupaten ?? 'Kab. Kupang' }}</p>
        </div>
        <img src="{{ asset('images/logo_smk.png') }}" alt="Logo SMK">
    </div>

    <div class="judul-dokumen">
        <h3>BERITA ACARA PELAKSANAAN ASESMEN / UJIAN CBT</h3>
        <p>Nomor: 421.5 / &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; / SMKN1.KB / {{ date('Y') }}</p>
    </div>

    <p class="paragraf">
        Pada hari ini <strong>{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</strong>, bertempat di SMK Negeri 1 Kupang Barat, telah diselenggarakan Asesmen Berbasis Komputer (CBT) dengan rincian pelaksanaan sebagai berikut:
    </p>

    <table class="table-info">
        <tr>
            <td class="label">Nama Asesmen / Tes</td>
            <td class="colon">:</td>
            <td><strong>{{ $jadwal->nama_ujian }}</strong></td>
        </tr>
        <tr>
            <td class="label">Mata Pelajaran</td>
            <td class="colon">:</td>
            <td>{{ $jadwal->bankSoal->mataPelajaran->nama_mapel ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Alokasi Waktu Pengerjaan</td>
            <td class="colon">:</td>
            <td>{{ $jadwal->durasi_menit }} Menit</td>
        </tr>
        <tr>
            <td class="label">Token Ujian</td>
            <td class="colon">:</td>
            <td><strong>{{ $jadwal->token_ujian }}</strong></td>
        </tr>
        <tr>
            <td class="label">Jumlah Peserta Terdaftar</td>
            <td class="colon">:</td>
            <td>{{ $jadwal->peserta->count() }} Siswa</td>
        </tr>
        <tr>
            <td class="label">Jumlah Peserta Hadir / Selesai</td>
            <td class="colon">:</td>
            <td>{{ $jadwal->peserta->where('status', 'selesai')->count() }} Siswa</td>
        </tr>
        <tr>
            <td class="label">Catatan Selama Pelaksanaan</td>
            <td class="colon">:</td>
            <td>Asesmen terlaksana secara tertib, lancar, dan aman dengan dukungan koneksi LAN/WAN sekolah.</td>
        </tr>
    </table>

    <p class="paragraf">
        Demikian Berita Acara ini dibuat dengan sebenarnya dan penuh tanggung jawab untuk dipergunakan sebagaimana mestinya.
    </p>

    <div class="ttd-container">
        <div class="ttd-box">
            <p>Proktor / Pengawas Ujian,</p>
            <div style="height: 65px;"></div>
            <p><strong>_______________________</strong></p>
            <p>NIP. -</p>
        </div>

        <div class="ttd-box">
            <p>Kupang Barat, {{ date('d F Y') }}</p>
            <p>Kepala Sekolah,</p>
            <img src="{{ asset($sekolah->ttd_kepala_sekolah ?? 'images/ttd_kepsek.png') }}" alt="TTD Kepsek" class="ttd-img">
            <p><strong><u>{{ $sekolah->kepala_sekolah ?? 'Vicky Koroh, S.Pd., M.Kom.' }}</u></strong></p>
            <p>NIP. {{ $sekolah->nip_kepala_sekolah ?? '198501192011011005' }}</p>
        </div>
    </div>

</body>
</html>
