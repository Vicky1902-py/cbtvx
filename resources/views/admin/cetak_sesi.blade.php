<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Rekap Sesi & Ruangan Lab Siswa &bull; {{ $sekolah->nama_sekolah ?? 'SMKN 1 Kupang Barat' }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm 12mm;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f1f5f9;
            color: #000000;
            font-size: 9.5pt;
            line-height: 1.3;
        }

        .no-print {
            background: #0f2b48;
            color: #ffffff;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .print-container {
            background: #ffffff;
            max-width: 210mm;
            margin: 0 auto;
            padding: 15mm 15mm;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        /* KOP RESMI DINAS */
        .kop-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3px double #000000;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .kop-logo {
            width: 58px;
            height: 58px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .kop-text {
            flex: 1;
            text-align: center;
            padding: 0 10px;
        }

        .kop-text h4 {
            font-size: 10pt;
            font-weight: 700;
            margin-bottom: 1px;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        .kop-text h5 {
            font-size: 9.5pt;
            font-weight: 700;
            margin-bottom: 2px;
            text-transform: uppercase;
        }

        .kop-text h3 {
            font-size: 13pt;
            font-weight: 800;
            margin-bottom: 2px;
            color: #000000;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .kop-text p {
            font-size: 7.5pt;
            color: #222222;
            line-height: 1.25;
        }

        /* JUDUL DOKUMEN */
        .doc-title-block {
            text-align: center;
            margin-bottom: 14px;
        }

        .doc-title-block h2 {
            font-size: 11pt;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            line-height: 1.3;
        }

        .doc-title-block h3 {
            font-size: 10pt;
            font-weight: 800;
            color: #0f2b48;
            margin-top: 1px;
            text-transform: uppercase;
        }

        .doc-title-block p {
            font-size: 8.5pt;
            font-weight: 600;
            color: #475569;
            margin-top: 2px;
        }

        /* METADATA */
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
            margin-bottom: 10px;
        }

        .meta-table td {
            padding: 2px 0;
            vertical-align: top;
        }

        /* TABEL PESERTA */
        .data-table-print {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
            margin-bottom: 16px;
        }

        .data-table-print th,
        .data-table-print td {
            border: 1px solid #000000;
            padding: 4px 6px;
            vertical-align: middle;
        }

        .data-table-print th {
            background-color: #e2e8f0 !important;
            font-weight: 700;
            text-align: center;
            font-size: 8.5pt;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* TANDA TANGAN */
        .signature-section {
            margin-top: 18px;
            page-break-inside: avoid;
        }

        .sig-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .sig-box {
            text-align: center;
            width: 220px;
            font-size: 8.5pt;
            line-height: 1.3;
        }

        .sig-box .role-title {
            font-weight: 600;
            margin-bottom: 45px;
        }

        .sig-box .person-name {
            font-weight: 700;
            text-decoration: underline;
        }

        .sig-box .person-nip {
            font-size: 8pt;
            color: #333333;
        }

        .sig-center-box {
            text-align: center;
            margin: 0 auto;
            width: 240px;
            font-size: 8.5pt;
            line-height: 1.3;
        }

        .sig-center-box img {
            height: 48px;
            margin: 1px auto;
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
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

    <!-- BAR NAVIGASI & FILTER CETAK (TIDAK TERCETAK DI KERTAS) -->
    <div class="no-print">
        <div>
            <strong style="font-size: 13.5px;">Cetak Rekap Sesi & Ruangan Lab Ujian Siswa</strong> &bull; Total {{ $daftarSiswa->count() }} Siswa
            <div style="font-size: 11px; opacity: 0.85; margin-top: 2px;">
                Filter: {{ $kelasFilter ? $kelasFilter->nama_kelas : 'Semua Kelas' }} &bull; {{ $sesiFilter }} &bull; {{ $ruanganFilter }}
            </div>
        </div>

        <form method="GET" action="{{ route('admin.cetak_sesi') }}" style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
            <select name="kelas_id" onchange="this.form.submit()" style="padding: 5px 8px; border-radius: 4px; border: 1px solid #334155; background: #1e293b; color: #ffffff; font-size: 11.5px;">
                <option value="">-- Semua Kelas --</option>
                @foreach ($kelasList as $k)
                    <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                        {{ $k->nama_kelas }} ({{ $k->jurusan }})
                    </option>
                @endforeach
            </select>

            <select name="sesi" onchange="this.form.submit()" style="padding: 5px 8px; border-radius: 4px; border: 1px solid #334155; background: #1e293b; color: #ffffff; font-size: 11.5px;">
                <option value="">-- Semua Sesi --</option>
                <option value="Sesi 1" {{ request('sesi') == 'Sesi 1' ? 'selected' : '' }}>Sesi 1</option>
                <option value="Sesi 2" {{ request('sesi') == 'Sesi 2' ? 'selected' : '' }}>Sesi 2</option>
                <option value="Sesi 3" {{ request('sesi') == 'Sesi 3' ? 'selected' : '' }}>Sesi 3</option>
            </select>

            <select name="ruangan" onchange="this.form.submit()" style="padding: 5px 8px; border-radius: 4px; border: 1px solid #334155; background: #1e293b; color: #ffffff; font-size: 11.5px;">
                <option value="">-- Semua Lab --</option>
                <option value="Lab Komputer 1" {{ request('ruangan') == 'Lab Komputer 1' ? 'selected' : '' }}>Lab Komputer 1</option>
                <option value="Lab Komputer 2" {{ request('ruangan') == 'Lab Komputer 2' ? 'selected' : '' }}>Lab Komputer 2</option>
                <option value="Lab Komputer 3" {{ request('ruangan') == 'Lab Komputer 3' ? 'selected' : '' }}>Lab Komputer 3</option>
            </select>

            <button type="button" onclick="window.print()" style="background: #0077b6; color: #ffffff; border: none; padding: 6px 16px; font-weight: bold; cursor: pointer; border-radius: 4px; font-size: 12.5px; display: inline-flex; align-items: center; gap: 5px;">
                🖨️ Cetak Lembar Sesi (Print / PDF)
            </button>
        </form>
    </div>

    <div class="print-container">
        <!-- KOP RESMI DINAS & SEKOLAH -->
        <div class="kop-header">
            <img src="{{ $sekolah->logo_pemprov_url }}" alt="Logo Pemprov" class="kop-logo">
            <div class="kop-text">
                <h4>{{ $sekolah->kop_baris1 }}</h4>
                <h5>{{ $sekolah->kop_baris2 }}</h5>
                <h3>{{ $sekolah->kop_baris3 }}</h3>
                <p>{{ $sekolah->kop_alamat_lengkap }}</p>
            </div>
            <img src="{{ $sekolah->logo_sekolah_url }}" alt="Logo SMK" class="kop-logo">
        </div>

        <!-- JUDUL DOKUMEN CETAK -->
        <div class="doc-title-block">
            <h2>DAFTAR PEMBAGIAN SESI &amp; RUANGAN LAB UJIAN PESERTA</h2>
            <h3>{{ strtoupper($sekolah->nama_ujian_aktif ?? 'SIMULASI SUMATIF AKHIR SEMESTER') }}</h3>
            <p>TAHUN AJARAN {{ $sekolah->tahun_ajaran_aktif ?? '2026/2027' }} &bull; SEMESTER {{ strtoupper($sekolah->semester_aktif ?? 'GENAP') }}</p>
        </div>

        <!-- METADATA RINGKAS -->
        <table class="meta-table">
            <tr>
                <td style="width: 14%;"><strong>Rombel / Kelas</strong></td>
                <td style="width: 2%;">:</td>
                <td style="width: 38%;">{{ $kelasFilter ? $kelasFilter->nama_kelas . ' (' . $kelasFilter->jurusan . ')' : 'Semua Kelas / Rombel' }}</td>
                <td style="width: 14%;"><strong>Sesi Pelaksanaan</strong></td>
                <td style="width: 2%;">:</td>
                <td style="width: 30%;"><strong style="color: #0f2b48;">{{ $sesiFilter }}</strong></td>
            </tr>
            <tr>
                <td><strong>Ruangan Lab</strong></td>
                <td>:</td>
                <td><strong style="color: #0369a1;">{{ $ruanganFilter }}</strong></td>
                <td><strong>Total Peserta</strong></td>
                <td>:</td>
                <td><strong>{{ $daftarSiswa->count() }} Orang Siswa</strong></td>
            </tr>
        </table>

        <!-- TABEL DATA SESI & RUANG SISWA -->
        <table class="data-table-print">
            <thead>
                <tr>
                    <th style="width: 28px;">NO</th>
                    <th style="width: 105px;">NO. PESERTA</th>
                    <th style="width: 90px;">NISN</th>
                    <th>NAMA LENGKAP SISWA</th>
                    <th style="width: 75px;">KELAS</th>
                    <th style="width: 105px;">RUANGAN LAB</th>
                    <th style="width: 60px;">SESI</th>
                    <th style="width: 110px;">JAM UJIAN</th>
                    <th style="width: 70px;">PARAF</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($daftarSiswa as $idx => $s)
                    @php
                        $jamUjian = '07.30 - 09.30 WITA';
                        if ($s->sesi_ujian === 'Sesi 2') {
                            $jamUjian = '10.00 - 12.00 WITA';
                        } elseif ($s->sesi_ujian === 'Sesi 3') {
                            $jamUjian = '13.00 - 15.00 WITA';
                        }
                    @endphp
                    <tr>
                        <td style="text-align: center;">{{ $idx + 1 }}</td>
                        <td style="font-family: monospace; font-size: 8pt; text-align: center;">{{ $s->nomor_peserta ?: '-' }}</td>
                        <td style="font-family: monospace; font-size: 8pt; text-align: center;">{{ $s->nisn }}</td>
                        <td>
                            <strong style="font-size: 8.5pt;">{{ strtoupper($s->nama_lengkap) }}</strong>
                        </td>
                        <td style="text-align: center;">{{ $s->kelas->nama_kelas ?? '-' }}</td>
                        <td style="text-align: center; font-weight: 600;">{{ $s->ruangan_ujian ?: 'Lab Komputer 1' }}</td>
                        <td style="text-align: center; font-weight: 700;">{{ $s->sesi_ujian ?: 'Sesi 1' }}</td>
                        <td style="text-align: center; font-size: 8pt; font-weight: 600;">{{ $jamUjian }}</td>
                        <td style="text-align: left; font-size: 7.5pt; color: #475569; padding-left: 6px;">{{ $idx + 1 }}.</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 24px; color: #64748b;">
                            Tidak ada data siswa ditemukan untuk kriteria filter ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- PENGESAHAN TANDA TANGAN (KETUA PANITIA & PROKTOR, MENGETAHUI KEPALA SEKOLAH) -->
        <div class="signature-section">
            <div style="text-align: right; font-size: 8.5pt; margin-bottom: 8px;">
                {{ $sekolah->titimangsa_lengkap }}
            </div>

            <div class="sig-row">
                <!-- SISI KIRI: KETUA PANITIA -->
                <div class="sig-box">
                    <div class="role-title">
                        Ketua Panitia Pelaksana,
                    </div>
                    <div class="person-name">
                        {{ $namaKetua }}
                    </div>
                    <div class="person-nip">
                        NIP. {{ $nipKetua }}
                    </div>
                </div>

                <!-- SISI KANAN: KOORDINATOR PROKTOR -->
                <div class="sig-box">
                    <div class="role-title">
                        Koordinator Proktor CBT,
                    </div>
                    <div class="person-name">
                        {{ $namaProktor }}
                    </div>
                    <div class="person-nip">
                        NIP. {{ $nipProktor }}
                    </div>
                </div>
            </div>

            <!-- SISI TENGAH: KEPALA SEKOLAH (MENGETAHUI) -->
            <div class="sig-center-box">
                <div style="font-weight: 600; margin-bottom: 2px;">
                    Mengetahui,<br>Kepala Sekolah
                </div>
                <img src="{{ $sekolah->ttd_kepala_sekolah_url }}" alt="TTD & Cap Kepsek">
                <div class="person-name">
                    {{ $sekolah->kepala_sekolah }}
                </div>
                <div class="person-nip">
                    NIP. {{ $sekolah->nip_kepala_sekolah }}
                </div>
            </div>
        </div>
    </div>

</body>
</html>
