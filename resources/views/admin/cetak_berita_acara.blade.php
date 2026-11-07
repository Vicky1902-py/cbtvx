<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara Pelaksanaan &bull; {{ $kelas->nama_kelas ?? 'Kelas' }} - {{ $beritaAcara->sesi ?? 'Sesi 1' }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 16mm 14mm 16mm;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9.5pt;
            line-height: 1.35;
            color: #000;
            background: #fff;
        }

        .no-print {
            padding: 10px 16px;
            background: #0f2b48;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            border-radius: 4px;
            font-size: 13px;
        }

        .header-top {
            display: flex;
            align-items: flex-start;
            margin-bottom: 16px;
            position: relative;
        }

        .logo-left {
            width: 65px;
            height: 65px;
            object-fit: contain;
            margin-right: 15px;
        }

        .judul-center {
            flex: 1;
            text-align: center;
            padding-right: 80px;
        }

        .judul-center h2 {
            font-size: 11pt;
            font-weight: bold;
            line-height: 1.3;
            letter-spacing: 0.3px;
        }

        .paragraf-pembuka {
            text-align: justify;
            margin-bottom: 12px;
            line-height: 1.4;
        }

        /* POIN 1: DATA PELAKSANAAN */
        .poin-section {
            margin-bottom: 10px;
        }

        .table-rincian {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
            margin-bottom: 6px;
        }

        .table-rincian td {
            padding: 2px 0;
            vertical-align: top;
        }

        .underline-line {
            display: inline-block;
            border-bottom: 1px solid #000;
            min-width: 180px;
            padding-bottom: 1px;
        }

        /* POIN 2: KOTAK CATATAN TES */
        .box-catatan {
            border: 1.5px solid #000;
            padding: 8px 10px;
            min-height: 68px;
            font-size: 8.5pt;
            line-height: 1.45;
            margin-top: 4px;
            margin-bottom: 16px;
        }

        /* TANDA TANGAN 3 PIHAK */
        .section-ttd-heading {
            display: flex;
            justify-content: space-between;
            font-size: 9pt;
            margin-bottom: 8px;
            padding-right: 70px;
        }

        .table-ttd {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
            margin-bottom: 14px;
            page-break-inside: avoid;
        }

        .table-ttd td {
            padding: 4px 0;
            vertical-align: middle;
        }

        .garis-ttd {
            border-bottom: 1px solid #000;
            width: 220px;
            display: inline-block;
            height: 18px;
        }

        /* KOTAK CATATAN RANGKAP 3 */
        .box-rangkap {
            border: 1.5px solid #000;
            padding: 6px 10px;
            width: 100%;
            max-width: 480px;
            font-size: 8pt;
            line-height: 1.35;
            margin-bottom: 16px;
            page-break-inside: avoid;
        }

        /* FOOTER RESMI KEMENDIKDASMEN */
        .footer-kementerian {
            margin-top: 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            page-break-inside: avoid;
        }

        .footer-box-border {
            border: 1.5px solid #000;
            padding: 4px 10px;
            flex: 1;
            margin: 0 8px;
            text-align: center;
            font-size: 8.5pt;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .footer-square {
            width: 26px;
            height: 22px;
            border: 1.5px solid #000;
            flex-shrink: 0;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <div>
            <strong>Pratinjau Berita Acara Pelaksanaan A4 (3 TTD)</strong> &bull; {{ $kelas->nama_kelas ?? 'Kelas' }} &bull; Sesi {{ $beritaAcara->sesi ?? '1' }}
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('admin.berita_acara.form', ['id' => $jadwal->id, 'kelas_id' => $kelas->id ?? '', 'sesi' => $beritaAcara->sesi ?? '1']) }}" style="background: #475569; color: #fff; padding: 5px 12px; text-decoration: none; border-radius: 4px; font-weight: bold;">
                &larr; Form Berita Acara
            </a>
            <button onclick="window.print()" style="background: #0077b6; color: #fff; border: none; padding: 5px 16px; font-weight: bold; border-radius: 4px; cursor: pointer;">
                Cetak Lembar Berita Acara (Print / PDF)
            </button>
        </div>
    </div>

    <!-- HEADER: LOGO SEKOLAH & JUDUL RESMI DINAMIS -->
    <div class="header-top">
        <img src="{{ $sekolah->logo_sekolah_url }}" alt="Logo Sekolah" class="logo-left">
        <div class="judul-center">
            <h2>BERITA ACARA PELAKSANAAN</h2>
            <h2>{{ strtoupper($beritaAcara->nama_tes ?? $jadwal->nama_ujian) }}</h2>
            <h2>TAHUN {{ date('Y') }}</h2>
        </div>
    </div>

    @php
        $rawDate = $beritaAcara->hari_tanggal ?? '';
        $hariStr = 'Senin';
        $tglStr = '02';
        $blnStr = 'November';
        $thnStr = date('Y');
        if (!empty($rawDate)) {
            if (preg_match('/([a-zA-Z]+),\s*(\d{1,2})\s+([a-zA-Z]+)\s+(\d{4})/', $rawDate, $m)) {
                $hariStr = $m[1];
                $tglStr = sprintf('%02d', $m[2]);
                $blnStr = $m[3];
                $thnStr = $m[4];
            } else {
                try {
                    $c = \Carbon\Carbon::parse($rawDate);
                    $hariStr = $c->isoFormat('dddd');
                    $tglStr = sprintf('%02d', $c->day);
                    $blnStr = $c->isoFormat('MMMM');
                    $thnStr = $c->year;
                } catch (\Exception $e) {
                    // fallback default
                }
            }
        }
    @endphp

    <!-- NARASI PEMBUKA -->
    <p class="paragraf-pembuka">
        Pada hari ini <strong>{{ $hariStr }}</strong> tanggal <strong>{{ $tglStr }}</strong> bulan <strong>{{ $blnStr }}</strong> tahun <strong>{{ $thnStr }}</strong>, di <strong>{{ strtoupper($sekolah->nama_sekolah ?? 'SMKN 1 KUPANG BARAT') }}</strong> telah diselenggarakan <strong>{{ strtoupper($beritaAcara->nama_tes ?? $jadwal->nama_ujian) }}</strong>, dari pukul {{ $beritaAcara->waktu_mulai ?? '08:00' }} sampai dengan pukul {{ $beritaAcara->waktu_selesai ?? '09:45' }}
    </p>

    <!-- POIN 1: IDENTITAS & JUMLAH PESERTA -->
    <div class="poin-section">
        <table class="table-rincian">
            <tr>
                <td style="width: 25px;">1.</td>
                <td style="width: 215px;">Username</td>
                <td style="width: 15px;">:</td>
                <td>
                    <span class="underline-line">{{ $beritaAcara->id_server ?? 'K24020010' }}</span>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>Sekolah/Madrasah</td>
                <td>:</td>
                <td>
                    <span class="underline-line" style="min-width: 380px;">{{ strtoupper($sekolah->nama_sekolah ?? 'SMKN 1 KUPANG BARAT') }}</span>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>ID Server</td>
                <td>:</td>
                <td>
                    <span class="underline-line" style="min-width: 140px;">{{ $beritaAcara->id_server ?? 'K24020010-AS4K' }}</span>
                    &nbsp;&nbsp;&nbsp;&nbsp;Ruang : 
                    <span class="underline-line" style="min-width: 240px;">{{ $beritaAcara->ruangan ?? 'TKA 1' }}</span>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>Sesi</td>
                <td>:</td>
                <td>
                    <span class="underline-line" style="min-width: 380px;">{{ str_replace(['Sesi ', 'sesi '], '', $beritaAcara->sesi ?? '1') }}</span>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>Jumlah Peserta Seharusnya</td>
                <td>:</td>
                <td>
                    <span class="underline-line" style="min-width: 380px;">{{ $beritaAcara->jumlah_peserta_seharusnya ?? 18 }}</span>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>Jumlah Hadir (Ikut Ujian)</td>
                <td>:</td>
                <td>
                    <span class="underline-line" style="min-width: 380px;">{{ $beritaAcara->jumlah_peserta_hadir ?? 18 }}</span>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>Jumlah Tidak Mengerjakan</td>
                <td>:</td>
                <td>
                    <span class="underline-line" style="min-width: 380px;">{{ $beritaAcara->jumlah_peserta_tidak_hadir ?? 0 }}</span>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>Username Tidak Mengerjakan</td>
                <td>:</td>
                <td>
                    <span class="underline-line" style="min-width: 380px;">{{ $beritaAcara->daftar_tidak_hadir ?? '-' }}</span>
                </td>
            </tr>
        </table>
    </div>

    <!-- POIN 2: CATATAN SELAMA TES -->
    <div style="font-size: 9pt; font-weight: normal; margin-top: 4px;">
        2. &nbsp;&nbsp;Catatan selama Tes :
    </div>
    <div class="box-catatan">
        {{ $beritaAcara->catatan_pelaksanaan ?? 'UJIAN BERJALAN AMAN DAN LANCAR' }}
    </div>

    <!-- PENGESAHAN: YANG MEMBUAT BERITA ACARA -->
    <div class="section-ttd-heading">
        <div>Yang membuat berita acara :</div>
        <div style="font-weight: bold;">TTD</div>
    </div>

    <table class="table-ttd">
        <!-- 1. PROKTOR -->
        <tr>
            <td style="width: 25px; vertical-align: top;">1.</td>
            <td style="width: 140px; vertical-align: top;">Proktor</td>
            <td style="width: 250px; vertical-align: top;">
                <strong>{{ $beritaAcara->nama_proktor ?? 'Izak Robinson Koroh, S.Pd.,Gr' }}</strong>
            </td>
            <td style="width: 30px; vertical-align: top;">1.</td>
            <td style="vertical-align: top;">
                <span class="garis-ttd"></span>
            </td>
        </tr>
        <tr>
            <td></td>
            <td style="font-size: 8.5pt;">NIP</td>
            <td style="font-size: 8.5pt;">{{ $beritaAcara->nip_proktor ?? '199002192020121003' }}</td>
            <td colspan="2"></td>
        </tr>

        <!-- 2. PENGAWAS -->
        <tr>
            <td style="vertical-align: top; padding-top: 8px;">2.</td>
            <td style="vertical-align: top; padding-top: 8px;">Pengawas</td>
            <td style="vertical-align: top; padding-top: 8px;">
                <strong>{{ $beritaAcara->nama_pengawas ?? 'Mike Amin Imanuel Modok' }}</strong>
            </td>
            <td style="vertical-align: top; padding-top: 8px;">2.</td>
            <td style="vertical-align: top; padding-top: 8px;">
                <span class="garis-ttd"></span>
            </td>
        </tr>
        <tr>
            <td></td>
            <td style="font-size: 8.5pt;">NIP</td>
            <td style="font-size: 8.5pt;">{{ $beritaAcara->nip_pengawas ?? '8843778679130052' }}</td>
            <td colspan="2"></td>
        </tr>

        <!-- 3. PENANGGUNG JAWAB -->
        <tr>
            <td style="vertical-align: top; padding-top: 8px;">3.</td>
            <td style="vertical-align: top; padding-top: 8px;">Penanggung Jawab</td>
            <td style="vertical-align: top; padding-top: 8px;">
                <strong>{{ $beritaAcara->penanggung_jawab_nama ?? ($sekolah->kepala_sekolah ?: 'Apolos Kofan, S.Pd.,MM') }}</strong>
            </td>
            <td style="vertical-align: top; padding-top: 8px;">3.</td>
            <td style="vertical-align: top; padding-top: 8px;">
                <span class="garis-ttd"></span>
            </td>
        </tr>
        <tr>
            <td></td>
            <td style="font-size: 8.5pt;">NIP</td>
            <td style="font-size: 8.5pt;">{{ $beritaAcara->penanggung_jawab_nip ?? ($sekolah->nip_kepala_sekolah ?: '196704062006041009') }}</td>
            <td colspan="2"></td>
        </tr>
    </table>

    <!-- KOTAK CATATAN RANGKAP 3 -->
    <div class="box-rangkap">
        <div style="font-weight: bold; margin-bottom: 2px;">Catatan:</div>
        <div>- Dibuat rangkap 3 (tiga), masing-masing untuk Sekolah, Kota/Kabupaten dan Provinsi</div>
        <div>- Untuk pusat di upload melalui web TKA</div>
    </div>

    <!-- FOOTER RESMI KEMENDIKDASMEN -->
    <div class="footer-kementerian">
        <div class="footer-square"></div>
        <div class="footer-box-border">
            KEMENTERIAN PENDIDIKAN DASAR DAN MENENGAH
        </div>
        <div class="footer-square"></div>
    </div>

</body>
</html>
