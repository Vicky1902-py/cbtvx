<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Hadir Peserta &bull; {{ $kelas->nama_kelas ?? 'Kelas' }} - {{ $sesiFormatted }}</title>
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
            font-size: 9.5pt;
            line-height: 1.25;
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
            margin-bottom: 14px;
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
            padding-right: 80px; /* balance logo on left */
        }

        .judul-center h2 {
            font-size: 11pt;
            font-weight: bold;
            line-height: 1.3;
            letter-spacing: 0.3px;
        }

        /* METADATA 2 KOLOM BERGARIS */
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .meta-table td {
            padding: 2.5px 0;
            vertical-align: middle;
        }

        .underline-field {
            display: inline-block;
            border-bottom: 1px solid #000;
            min-width: 180px;
            padding-bottom: 1px;
        }

        .underline-field-sm {
            display: inline-block;
            border-bottom: 1px solid #000;
            min-width: 90px;
            padding-bottom: 1px;
        }

        /* TABEL DAFTAR HADIR */
        .table-peserta {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
            margin-bottom: 10px;
        }

        .table-peserta th, .table-peserta td {
            border: 1px solid #000;
            padding: 3.5px 5px;
        }

        .table-peserta th {
            background-color: #d8cdb8;
            font-weight: bold;
            text-align: center;
            height: 24px;
        }

        .col-ttd-left {
            width: 130px;
            height: 22px;
            vertical-align: middle;
            font-size: 8pt;
            padding-left: 8px;
        }

        .col-ttd-right {
            width: 130px;
            height: 22px;
            vertical-align: middle;
            font-size: 8pt;
            padding-left: 60px;
        }

        /* AREA BAWAH: KETERANGAN & TTD */
        .bottom-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 6px;
            font-size: 8pt;
            page-break-inside: avoid;
        }

        .left-notes {
            width: 52%;
        }

        .left-notes ol {
            padding-left: 14px;
            margin-bottom: 8px;
            line-height: 1.35;
        }

        .rekap-box {
            border: 1px solid #000;
            padding: 4px 8px;
            font-size: 8pt;
            width: 100%;
            max-width: 310px;
            line-height: 1.45;
        }

        .right-ttd {
            width: 44%;
            display: flex;
            justify-content: space-around;
            text-align: center;
            margin-top: 18px;
        }

        .ttd-person {
            width: 48%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            min-height: 90px;
        }

        .ttd-title {
            font-weight: normal;
        }

        .ttd-name {
            margin-top: 45px;
            font-weight: bold;
        }

        .ttd-nip {
            font-size: 7.5pt;
        }

        /* FOOTER RESMI KEMENDIKDASMEN */
        .footer-kementerian {
            margin-top: 16px;
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
            <strong>Pratinjau Daftar Hadir Asesmen Terstandar A4</strong> &bull; {{ $kelas->nama_kelas ?? 'Kelas' }} &bull; {{ $sesiFormatted }}
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('admin.daftar_hadir.form', ['id' => $jadwal->id, 'kelas_id' => $kelas->id ?? '', 'sesi' => $sesiFormatted]) }}" style="background: #475569; color: #fff; padding: 5px 12px; text-decoration: none; border-radius: 4px; font-weight: bold;">
                &larr; Form Presensi
            </a>
            <button onclick="window.print()" style="background: #0077b6; color: #fff; border: none; padding: 5px 16px; font-weight: bold; border-radius: 4px; cursor: pointer;">
                Cetak Lembar Daftar Hadir (Print / PDF)
            </button>
        </div>
    </div>

    <!-- HEADER: LOGO TUT WURI & JUDUL RESMI -->
    <div class="header-top">
        <img src="{{ $sekolah->logo_tutwuri_url }}" alt="Logo Tut Wuri Handayani" class="logo-left">
        <div class="judul-center">
            <h2>DAFTAR HADIR PESERTA</h2>
            <h2>TES KEMAMPUAN AKADEMIK SMK</h2>
            <h2>TAHUN {{ date('Y') }}</h2>
        </div>
    </div>

    @php
        $rawDate = $beritaAcara->hari_tanggal ?? '';
        $hari = 'Senin';
        $tanggal = '02 November 2026';
        if (!empty($rawDate)) {
            if (strpos($rawDate, ',') !== false) {
                $parts = explode(',', $rawDate, 2);
                $hari = trim($parts[0]);
                $tanggal = trim($parts[1]);
            } else {
                try {
                    $c = \Carbon\Carbon::parse($rawDate);
                    $hari = $c->isoFormat('dddd');
                    $tanggal = $c->isoFormat('D MMMM Y');
                } catch (\Exception $e) {
                    $tanggal = $rawDate;
                }
            }
        }
    @endphp

    <!-- METADATA 2 KOLOM PERSIS DOKUMEN TKA -->
    <table class="meta-table">
        <tr>
            <td style="width: 145px;">KOTA/KABUPATEN</td>
            <td style="width: 12px;">:</td>
            <td style="width: 250px;">
                <span class="underline-field">{{ strtoupper($sekolah->kabupaten ?? 'KABUPATEN KUPANG') }}</span>
            </td>
            <td style="width: 65px;">KODE</td>
            <td style="width: 12px;">:</td>
            <td>
                <span class="underline-field-sm">{{ $beritaAcara->kode_kabupaten ?? '02' }}</span>
            </td>
        </tr>
        <tr>
            <td>SEKOLAH/MADRASAH</td>
            <td>:</td>
            <td>
                <span class="underline-field">{{ strtoupper($sekolah->nama_sekolah ?? 'SMKN 1 KUPANG BARAT') }}</span>
            </td>
            <td>KODE</td>
            <td>:</td>
            <td>
                <span class="underline-field-sm">{{ $beritaAcara->kode_sekolah ?? '0010' }}</span>
            </td>
        </tr>
        <tr>
            <td>ID SERVER / RUANG</td>
            <td>:</td>
            <td>
                <span class="underline-field">{{ $beritaAcara->id_server ?? 'K24020010-AS4K' }} / {{ $beritaAcara->ruangan ?? 'TKA 1' }}</span>
            </td>
            <td>SESI</td>
            <td>:</td>
            <td>
                <span class="underline-field-sm">{{ $sesiNumeric }}</span>
            </td>
        </tr>
        <tr>
            <td>HARI</td>
            <td>:</td>
            <td colspan="4">
                <span style="border-bottom: 1px solid #000; min-width: 90px; display: inline-block; padding-bottom: 1px;">
                    {{ $hari }}
                </span>
                &nbsp;&nbsp;&nbsp;&nbsp;TANGGAL : 
                <span style="border-bottom: 1px solid #000; min-width: 150px; display: inline-block; padding-bottom: 1px;">
                    {{ $tanggal }}
                </span>
                &nbsp;&nbsp;&nbsp;&nbsp;PUKUL : 
                <span style="border-bottom: 1px solid #000; min-width: 110px; display: inline-block; padding-bottom: 1px;">
                    {{ $beritaAcara->waktu_mulai ?? '08:00' }}-{{ $beritaAcara->waktu_selesai ?? '09:45' }}
                </span>
            </td>
        </tr>
    </table>

    <!-- TABEL DAFTAR HADIR PESERTA -->
    <table class="table-peserta">
        <thead>
            <tr>
                <th style="width: 32px;">No.</th>
                <th style="width: 135px;">Username</th>
                <th>Nama Peserta</th>
                <th style="width: 140px;">Tanda Tangan</th>
                <th style="width: 150px;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $presensi = $beritaAcara->presensi_data ?? [];
            @endphp
            @forelse ($siswaList as $idx => $s)
            @php
                $rowNum = $idx + 1;
                $status = $presensi[$s->id]['status'] ?? 'hadir';
                $ket = $presensi[$s->id]['keterangan'] ?? '';
                $usr = $s->user->username ?? $s->nomor_peserta ?? $s->nisn;
            @endphp
            <tr>
                <td style="text-align: center;">{{ $rowNum }}</td>
                <td style="font-family: Arial, sans-serif;">{{ $usr }}</td>
                <td>{{ ucwords(strtolower($s->nama_lengkap)) }}</td>
                @if ($rowNum % 2 != 0)
                    <td class="col-ttd-left">{{ $rowNum }}.</td>
                @else
                    <td class="col-ttd-right">{{ $rowNum }}.</td>
                @endif
                <td style="font-size: 8pt; color: {{ $status !== 'hadir' ? '#b91c1c' : '#000' }}; font-weight: {{ $status !== 'hadir' ? 'bold' : 'normal' }};">
                    @if ($status !== 'hadir')
                        Tidak Hadir{{ $ket ? ' (' . $ket . ')' : '' }}
                    @else
                        {{ $ket }}
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 20px;">Belum ada siswa pada rombel atau sesi ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- AREA BAWAH: KETERANGAN PUSMENDIK & TANDA TANGAN 2 PETUGAS -->
    <div class="bottom-section">
        <div class="left-notes">
            <div style="font-weight: bold; margin-bottom: 2px;">Keterangan :</div>
            <ol>
                <li>Dibuat rangkap 3 (tiga), masing-masing untuk sekolah, kota/kab dan Provinsi.</li>
                <li>Pengawas ruang menyilang Nama Peserta yang tidak hadir.</li>
                <li>Daftar hadir untuk pusat di upload melalui laman TKA.</li>
            </ol>

            <div class="rekap-box">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 220px;">Jumlah Peserta yang Seharusnya Hadir</td>
                        <td style="width: 10px;">:</td>
                        <td><span style="border-bottom: 1px dotted #000; min-width: 30px; display: inline-block; text-align: center; font-weight: bold;">{{ $beritaAcara->jumlah_peserta_seharusnya ?? $siswaList->count() }}</span> peserta</td>
                    </tr>
                    <tr>
                        <td>Jumlah Peserta yang Tidak Hadir</td>
                        <td>:</td>
                        <td><span style="border-bottom: 1px dotted #000; min-width: 30px; display: inline-block; text-align: center; font-weight: bold;">{{ $beritaAcara->jumlah_peserta_tidak_hadir ?? 0 }}</span> peserta</td>
                    </tr>
                    <tr>
                        <td>Jumlah Peserta Hadir</td>
                        <td>:</td>
                        <td><span style="border-bottom: 1px dotted #000; min-width: 30px; display: inline-block; text-align: center; font-weight: bold;">{{ $beritaAcara->jumlah_peserta_hadir ?? $siswaList->count() }}</span> peserta</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="right-ttd">
            <!-- PROKTOR -->
            <div class="ttd-person">
                <div class="ttd-title">Proktor</div>
                <div class="ttd-name">
                    ( {{ $beritaAcara->nama_proktor ?? '....................................' }} )
                </div>
                <div class="ttd-nip">
                    NIP. {{ $beritaAcara->nip_proktor ?? '.................................' }}
                </div>
            </div>

            <!-- PENGAWAS -->
            <div class="ttd-person">
                <div class="ttd-title">Pengawas</div>
                <div class="ttd-name">
                    ( {{ $beritaAcara->nama_pengawas ?? '....................................' }} )
                </div>
                <div class="ttd-nip">
                    NIP. {{ $beritaAcara->nip_pengawas ?? '.................................' }}
                </div>
            </div>
        </div>
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
