<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Nilai Asesmen &bull; {{ $jadwal->nama_ujian }} &bull; SMKN 1 Kupang Barat</title>
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
            font-size: 11px;
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
            margin-bottom: 14px;
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

        .stats-box {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            border: 1px solid #000000;
            margin-bottom: 14px;
            text-align: center;
            font-size: 10px;
        }

        .stats-item {
            padding: 6px;
            border-right: 1px solid #000000;
        }

        .stats-item:last-child {
            border-right: none;
        }

        .stats-item .val {
            font-size: 14px;
            font-weight: 800;
            margin-top: 2px;
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
            font-size: 9.5px;
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
            <strong>Pratinjau Cetak Rekap Nilai Ujian (A4 Resmi)</strong>
            <span style="margin-left: 10px; opacity: 0.8; font-size: 12px;">Total Peserta: {{ $pesertaList->count() }} Siswa</span>
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

    <div class="doc-title">DAFTAR NILAI DAN REKAPITULASI HASIL ASESMEN</div>

    <div class="meta-grid">
        <table class="meta-table">
            <tr>
                <td class="label">Nama Asesmen</td>
                <td style="width: 5px;">:</td>
                <td><strong>{{ strtoupper($jadwal->nama_ujian) }}</strong></td>
            </tr>
            <tr>
                <td class="label">Mata Pelajaran</td>
                <td>:</td>
                <td>{{ $jadwal->bankSoal->mataPelajaran->nama_mapel ?? 'Semua Mapel' }}</td>
            </tr>
            <tr>
                <td class="label">Tahun Pelajaran</td>
                <td>:</td>
                <td>{{ $sekolah->tahun_ajaran_aktif ?? '2026/2027' }} &bull; Semester {{ $sekolah->semester_aktif ?? 'Genap' }}</td>
            </tr>
        </table>
        <table class="meta-table">
            <tr>
                <td class="label">Rombel / Kelas</td>
                <td style="width: 5px;">:</td>
                <td><strong>{{ $filterKelas ? $filterKelas->nama_kelas . ' (' . $filterKelas->jurusan . ')' : 'Semua Kelas Peserta' }}</strong></td>
            </tr>
            <tr>
                <td class="label">Standar KKM</td>
                <td>:</td>
                <td><strong>{{ $stats['kkm'] }}</strong></td>
            </tr>
            <tr>
                <td class="label">Tanggal Pelaksanaan</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->translatedFormat('d F Y') }}</td>
            </tr>
        </table>
    </div>

    <!-- STATISTIK RINGKAS -->
    <div class="stats-box">
        <div class="stats-item">
            <div>TOTAL PESERTA</div>
            <div class="val">{{ $stats['total'] }} Siswa</div>
        </div>
        <div class="stats-item">
            <div>NILAI TERTINGGI</div>
            <div class="val" style="color: #166534;">{{ $stats['tertinggi'] }}</div>
        </div>
        <div class="stats-item">
            <div>NILAI TERENDAH</div>
            <div class="val" style="color: #991b1b;">{{ $stats['terendah'] }}</div>
        </div>
        <div class="stats-item">
            <div>RATA-RATA NILAI</div>
            <div class="val">{{ $stats['rata_rata'] }}</div>
        </div>
        <div class="stats-item">
            <div>KETUNTASAN KLASIKAL</div>
            <div class="val">{{ $stats['persen_tuntas'] }}%</div>
        </div>
    </div>

    <!-- TABEL DATA NILAI -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 30px;">No.</th>
                <th style="width: 90px;">No. Peserta</th>
                <th style="width: 80px;">NISN</th>
                <th>Nama Lengkap Peserta</th>
                <th style="width: 90px;">Kelas / Rombel</th>
                <th style="width: 40px;">Benar</th>
                <th style="width: 40px;">Salah</th>
                <th style="width: 40px;">Kosong</th>
                <th style="width: 55px;">Skor PG</th>
                <th style="width: 55px;">Uraian</th>
                <th style="width: 65px;">Nilai Akhir</th>
                <th style="width: 75px;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pesertaList as $idx => $p)
            @php
                $isTuntas = $p->total_nilai >= $stats['kkm'];
            @endphp
            <tr>
                <td style="text-align: center;">{{ $idx + 1 }}</td>
                <td style="text-align: center; font-family: monospace;">{{ $p->siswa->nomor_peserta ?? '-' }}</td>
                <td style="text-align: center; font-family: monospace;">{{ $p->siswa->nisn }}</td>
                <td><strong>{{ strtoupper($p->siswa->nama_lengkap) }}</strong></td>
                <td>{{ $p->siswa->kelas->nama_kelas ?? '-' }}</td>
                <td style="text-align: center;">{{ $p->jawaban_benar }}</td>
                <td style="text-align: center;">{{ $p->jawaban_salah }}</td>
                <td style="text-align: center;">{{ $p->jawaban_kosong }}</td>
                <td style="text-align: right;">{{ number_format($p->nilai_pg, 2) }}</td>
                <td style="text-align: right;">{{ number_format($p->nilai_uraian, 2) }}</td>
                <td style="text-align: right; font-weight: 800;">{{ number_format($p->total_nilai, 2) }}</td>
                <td style="text-align: center; font-weight: 700;">
                    {{ $isTuntas ? 'TUNTAS' : 'BELUM TUNTAS' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="12" style="text-align: center; padding: 20px;">Belum ada data nilai peserta.</td>
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
