<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara Pelaksanaan Ujian CBT &bull; SMKN 1 Kupang Barat</title>
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
            font-size: 11.5pt;
            line-height: 1.45;
            color: #000;
        }

        .kop-surat {
            display: flex;
            align-items: center;
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 18px;
        }

        .kop-surat img {
            width: 68px;
            height: 68px;
            object-fit: contain;
        }

        .kop-text {
            flex: 1;
            text-align: center;
            padding: 0 10px;
        }

        .kop-text h4 {
            font-size: 11pt;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 1px;
        }

        .kop-text h2 {
            font-size: 14pt;
            font-weight: 800;
            letter-spacing: 0.8px;
            margin: 2px 0;
        }

        .kop-text p {
            font-size: 9.5pt;
            font-style: italic;
        }

        .judul-dokumen {
            text-align: center;
            margin-bottom: 20px;
        }

        .judul-dokumen h3 {
            font-size: 12.5pt;
            font-weight: bold;
            text-decoration: underline;
        }

        .judul-dokumen p {
            font-size: 10.5pt;
            margin-top: 3px;
        }

        .paragraf {
            text-align: justify;
            margin-bottom: 12px;
            text-indent: 30px;
        }

        .table-info {
            width: 100%;
            margin-bottom: 14px;
            border-collapse: collapse;
        }

        .table-info td {
            padding: 3px 0;
            vertical-align: top;
        }

        .table-info td.label {
            width: 230px;
        }

        .table-info td.colon {
            width: 15px;
        }

        .table-rekap {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            font-size: 10.5pt;
        }

        .table-rekap th, .table-rekap td {
            border: 1px solid #000;
            padding: 5px 8px;
            text-align: center;
        }

        .table-rekap th {
            background: #f1f5f9;
            font-weight: bold;
        }

        .ttd-grid {
            margin-top: 30px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
            page-break-inside: avoid;
        }

        .ttd-box {
            text-align: center;
            font-size: 10.5pt;
        }

        .ttd-img {
            height: 55px;
            margin: 2px auto;
            display: block;
        }

        .ttd-space {
            height: 60px;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="no-print" style="padding: 12px 20px; background: #0f2b48; color: #fff; display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; font-family: sans-serif; font-size: 13px; border-radius: 4px;">
        <div>
            <strong>Pratinjau Berita Acara Resmi Ujian CBT</strong> &bull; {{ $jadwal->nama_ujian }}
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.berita_acara.form', $jadwal->id) }}" style="background: #475569; color: #fff; text-decoration: none; padding: 6px 14px; font-weight: bold; border-radius: 4px;">
                &larr; Edit Data Berita Acara
            </a>
            <button onclick="window.print()" style="background: #0077b6; color: #fff; border: none; padding: 6px 16px; font-weight: bold; cursor: pointer; border-radius: 4px;">
                Cetak Dokumen (Print / PDF)
            </button>
        </div>
    </div>

    <!-- KOP RESMI RESMI DINAS & SEKOLAH -->
    <div class="kop-surat">
        <img src="{{ $sekolah->logo_pemprov_url }}" alt="Logo Pemprov">
        <div class="kop-text">
            <h4>{{ $sekolah->kop_baris1 }}</h4>
            <h4>{{ $sekolah->kop_baris2 }}</h4>
            <h2>{{ $sekolah->kop_baris3 }}</h2>
            <p>{{ $sekolah->kop_alamat_lengkap }}</p>
        </div>
        <img src="{{ $sekolah->logo_sekolah_url }}" alt="Logo SMK">
    </div>

    <div class="judul-dokumen">
        <h3>BERITA ACARA PELAKSANAAN ASESMEN / UJIAN CBT</h3>
        <p>Nomor: {{ $beritaAcara->nomor_surat ?? ('421.5/024/SMKN1.KB/' . date('Y')) }}</p>
    </div>

    <p class="paragraf">
        Pada hari ini <strong>{{ $beritaAcara->hari_tanggal ?? \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</strong>, bertempat di <strong>{{ $sekolah->nama_sekolah }}</strong>, telah diselenggarakan Asesmen Berbasis Komputer (CBT) dengan ketentuan dan rincian pelaksanaan sebagai berikut:
    </p>

    <table class="table-info">
        <tr>
            <td class="label">1. Nama Asesmen / Tes</td>
            <td class="colon">:</td>
            <td><strong>{{ $jadwal->nama_ujian }}</strong></td>
        </tr>
        <tr>
            <td class="label">2. Mata Pelajaran</td>
            <td class="colon">:</td>
            <td>{{ $jadwal->bankSoal->mataPelajaran->nama_mapel ?? 'Semua Mapel Terpadu' }}</td>
        </tr>
        <tr>
            <td class="label">3. Alokasi Waktu Ujian</td>
            <td class="colon">:</td>
            <td>{{ $beritaAcara->waktu_mulai ?? '08:00' }} s/d {{ $beritaAcara->waktu_selesai ?? '09:30' }} WITA ({{ $jadwal->durasi_menit }} Menit)</td>
        </tr>
        <tr>
            <td class="label">4. Ruangan & Sesi Ujian</td>
            <td class="colon">:</td>
            <td>{{ $beritaAcara->ruangan ?? 'Lab Komputer 1' }} / {{ $beritaAcara->sesi ?? 'Sesi 1' }}</td>
        </tr>
        <tr>
            <td class="label">5. Token Ujian Rilis</td>
            <td class="colon">:</td>
            <td><strong>{{ $jadwal->token_ujian }}</strong></td>
        </tr>
        <tr>
            <td class="label">6. Catatan Selama Pelaksanaan</td>
            <td class="colon">:</td>
            <td>{{ $beritaAcara->catatan_pelaksanaan ?? 'Asesmen CBT terlaksana secara tertib, lancar, dan aman didukung sarana komputer dan jaringan LAN sekolah.' }}</td>
        </tr>
        @if (!empty($beritaAcara->daftar_tidak_hadir) && $beritaAcara->daftar_tidak_hadir !== '-')
        <tr>
            <td class="label">7. Siswa Tidak Hadir</td>
            <td class="colon">:</td>
            <td>{{ $beritaAcara->daftar_tidak_hadir }}</td>
        </tr>
        @endif
    </table>

    <table class="table-rekap">
        <thead>
            <tr>
                <th>Jumlah Peserta Seharusnya</th>
                <th>Jumlah Peserta Hadir</th>
                <th>Jumlah Peserta Tidak Hadir</th>
                <th>Keterangan Pelaksanaan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>{{ $beritaAcara->jumlah_peserta_seharusnya ?? 89 }} Siswa</strong></td>
                <td><strong style="color: #047857;">{{ $beritaAcara->jumlah_peserta_hadir ?? 89 }} Siswa</strong></td>
                <td><strong>{{ $beritaAcara->jumlah_peserta_tidak_hadir ?? 0 }} Siswa</strong></td>
                <td>Berjalan Sesuai POS / Juknis CBT</td>
            </tr>
        </tbody>
    </table>

    <p class="paragraf">
        Demikian Berita Acara ini dibuat dengan sebenarnya dan ditandatangani oleh Pengawas Ruang, Proktor Ujian, serta diketahui oleh Kepala Sekolah untuk dipergunakan sebagaimana mestinya.
    </p>

    <!-- 3 KOLOM TANDA TANGAN OTOMATIS -->
    <div class="ttd-grid">
        <!-- 1. PENGAWAS RUANG -->
        <div class="ttd-box">
            <p>Pengawas Ruang,</p>
            <div class="ttd-space"></div>
            <p><strong><u>{{ $beritaAcara->nama_pengawas ?? 'Potifera Buan, S.Pd' }}</u></strong></p>
            <p>NIP. {{ $beritaAcara->nip_pengawas ?? '198911042024212044' }}</p>
        </div>

        <!-- 2. PROKTOR UJIAN -->
        <div class="ttd-box">
            <p>Proktor CBT,</p>
            <div class="ttd-space"></div>
            <p><strong><u>{{ $beritaAcara->nama_proktor ?? 'Izak Robinson Koroh, S.Pd' }}</u></strong></p>
            <p>NIP. {{ $beritaAcara->nip_proktor ?? '199002192020121003' }}</p>
        </div>

        <!-- 3. KEPALA SEKOLAH -->
        <div class="ttd-box">
            <p>{{ $sekolah->titimangsa_lengkap }}</p>
            <p>Mengetahui,<br><strong>Kepala Sekolah</strong></p>
            <img src="{{ $sekolah->ttd_kepala_sekolah_url }}" alt="TTD Kepsek" class="ttd-img">
            <p><strong><u>{{ $sekolah->kepala_sekolah }}</u></strong></p>
            <p>NIP. {{ $sekolah->nip_kepala_sekolah }}</p>
        </div>
    </div>

</body>
</html>
