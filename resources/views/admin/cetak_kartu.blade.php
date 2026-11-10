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
            font-size: 8.5px;
            font-weight: 700;
            margin-bottom: 0px;
        }

        .header-text h5 {
            font-size: 8px;
            font-weight: 700;
            margin-bottom: 1px;
            color: #1e293b;
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

    <div class="no-print" style="padding: 12px 20px; background: #0f2b48; color: #ffffff; display: flex; justify-content: space-between; align-items: center; gap: 14px; flex-wrap: wrap; margin-bottom: 16px;">
        <div>
            <strong style="font-size: 13.5px;">Cetak Kartu Peserta {{ $sekolah->nama_ujian_aktif ?? 'Ujian' }} &bull; Format A4 Dinas</strong>
            <div style="font-size: 11px; opacity: 0.8; margin-top: 2px;">
                Menampilkan: <strong>{{ $daftarSiswa->count() }} Siswa</strong>
                @if (!empty($kelasId) && isset($kelasList) && ($kObj = $kelasList->firstWhere('id', $kelasId)))
                    &bull; Kelas: <span style="color: #38bdf8; font-weight: 700;">{{ $kObj->nama_kelas }} ({{ $kObj->jurusan }})</span>
                @else
                    &bull; Kelas: <span style="color: #38bdf8;">Semua Kelas</span>
                @endif
                @if (!empty($sesi))
                    &bull; Sesi: <span style="color: #facc15; font-weight: 700;">{{ $sesi }}</span>
                @endif
            </div>
        </div>

        <form action="{{ route('admin.cetak_kartu') }}" method="GET" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <select name="kelas_id" onchange="this.form.submit()" style="padding: 6px 12px; border-radius: 4px; border: 1px solid #334155; background: #1e293b; color: #ffffff; font-size: 12px;">
                <option value="">-- Semua Kelas / Rombel --</option>
                @if (isset($kelasList))
                    @foreach ($kelasList as $k)
                        <option value="{{ $k->id }}" {{ (!empty($kelasId) && $kelasId == $k->id) ? 'selected' : '' }}>
                            {{ $k->nama_kelas }} ({{ $k->jurusan }})
                        </option>
                    @endforeach
                @endif
            </select>

            <select name="sesi" onchange="this.form.submit()" style="padding: 6px 12px; border-radius: 4px; border: 1px solid #334155; background: #1e293b; color: #ffffff; font-size: 12px;">
                <option value="">-- Semua Sesi --</option>
                <option value="Sesi 1" {{ (!empty($sesi) && $sesi == 'Sesi 1') ? 'selected' : '' }}>Sesi 1</option>
                <option value="Sesi 2" {{ (!empty($sesi) && $sesi == 'Sesi 2') ? 'selected' : '' }}>Sesi 2</option>
                <option value="Sesi 3" {{ (!empty($sesi) && $sesi == 'Sesi 3') ? 'selected' : '' }}>Sesi 3</option>
            </select>

            <button type="submit" style="background: #334155; color: #ffffff; border: none; padding: 6px 12px; font-size: 12px; font-weight: 600; border-radius: 4px; cursor: pointer;">
                Terapkan
            </button>
            @if (!empty($kelasId) || !empty($sesi))
                <a href="{{ route('admin.cetak_kartu') }}" style="color: #94a3b8; font-size: 12px; text-decoration: underline;">Reset</a>
            @endif

            <button type="button" onclick="window.print()" style="background: #0077b6; color: #ffffff; border: none; padding: 8px 18px; font-weight: bold; cursor: pointer; border-radius: 4px; display: inline-flex; align-items: center; gap: 6px; margin-left: 8px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Cetak Dokumen A4 Sekarang
            </button>
        </form>
    </div>

    <div class="cards-grid">
        @foreach ($daftarSiswa as $siswa)
        <div class="card-item">
            <div class="card-header">
                <img src="{{ $sekolah->logo_pemprov_url }}" alt="Logo Pemprov">
                <div class="header-text">
                    <h4>{{ $sekolah->kop_baris1 }}</h4>
                    <h5>{{ $sekolah->kop_baris2 }}</h5>
                    <h3>{{ $sekolah->kop_baris3 }}</h3>
                    <p>{{ $sekolah->kop_alamat_lengkap }}</p>
                </div>
                <img src="{{ $sekolah->logo_sekolah_url }}" alt="Logo SMK">
            </div>

            <div class="card-title">KARTU PESERTA {{ strtoupper($sekolah->nama_ujian_aktif ?? 'SIMULASI SUMATIF AKHIR SEMESTER') }} TP. {{ $sekolah->tahun_ajaran_aktif ?? '2026/2027' }}</div>

            <div class="card-body">
                <div class="photo-box">
                    @if ($siswa->foto && file_exists(public_path($siswa->foto)))
                        <img src="{{ asset($siswa->foto) }}" alt="Foto {{ $siswa->nama_lengkap }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 2px;">
                    @elseif (file_exists(public_path('storage/foto_siswa/' . $siswa->nisn . '.jpg')))
                        <img src="{{ asset('storage/foto_siswa/' . $siswa->nisn . '.jpg') }}" alt="Foto {{ $siswa->nama_lengkap }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 2px;">
                    @elseif (file_exists(public_path('storage/foto_siswa/' . $siswa->nisn . '.png')))
                        <img src="{{ asset('storage/foto_siswa/' . $siswa->nisn . '.png') }}" alt="Foto {{ $siswa->nama_lengkap }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 2px;">
                    @else
                        FOTO<br>2 x 3
                    @endif
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
                    <div>{{ $sekolah->titimangsa_lengkap }}</div>
                    <div>Kepala Sekolah,</div>
                    <img src="{{ $sekolah->ttd_kepala_sekolah_url }}" alt="TTD Kepsek" class="ttd-img"><br>
                    <strong><u>{{ $sekolah->kepala_sekolah }}</u></strong><br>
                    <span>NIP. {{ $sekolah->nip_kepala_sekolah }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</body>
</html>
