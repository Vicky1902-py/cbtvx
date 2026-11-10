<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Kartu Tanda Pengenal Proktor & Pengawas &bull; SMKN 1 Kupang Barat</title>
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
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }

        .no-print {
            background: #0f2b48;
            color: #ffffff;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            max-width: 190mm;
            margin: 0 auto;
        }

        .card-petugas {
            border: 2px solid #0f2b48;
            border-radius: 6px;
            padding: 12px 14px;
            background: #ffffff;
            page-break-inside: avoid;
            position: relative;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 6px;
            border-bottom: 2px solid #0f2b48;
            padding-bottom: 8px;
            margin-bottom: 8px;
        }

        .card-header img {
            width: 38px;
            height: 38px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .header-text {
            flex: 1;
            text-align: center;
            min-width: 0;
        }

        .header-text h4 {
            font-size: 7.2px;
            font-weight: 700;
            margin-bottom: 1px;
            letter-spacing: 0;
            white-space: nowrap;
        }

        .header-text h5 {
            font-size: 7.5px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1px;
            white-space: nowrap;
        }

        .header-text h3 {
            font-size: 10.5px;
            font-weight: 800;
            color: #0f2b48;
            margin-bottom: 1px;
            white-space: nowrap;
        }

        .header-text p {
            font-size: 7px;
            color: #475569;
            line-height: 1.2;
        }

        .badge-title {
            text-align: center;
            font-size: 11px;
            font-weight: 800;
            color: #ffffff !important;
            background-color: #0f2b48 !important;
            padding: 5px 8px;
            border-radius: 3px;
            letter-spacing: 0.8px;
            margin-bottom: 10px;
            text-transform: uppercase;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }

        .badge-title.pengawas {
            background: #065f46;
        }

        .card-body {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .photo-frame {
            width: 72px;
            height: 96px;
            border: 1.5px dashed #94a3b8;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 9px;
            color: #64748b;
            background: #f8fafc;
            border-radius: 4px;
            flex-shrink: 0;
            line-height: 1.2;
        }

        .data-table {
            flex: 1;
            border-collapse: collapse;
            font-size: 9.5px;
            line-height: 1.35;
        }

        .data-table td {
            padding: 2px 0;
            vertical-align: top;
        }

        .data-table td.label {
            width: 82px;
            color: #475569;
        }

        .data-table td.colon {
            width: 8px;
        }

        .data-table td.val {
            font-weight: 600;
            color: #0f172a;
        }

        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            border-top: 1px solid #cbd5e1;
            padding-top: 6px;
            font-size: 8px;
        }

        .barcode-box {
            font-family: monospace;
            font-size: 8px;
            color: #64748b;
        }

        .ttd-box {
            text-align: center;
            width: 140px;
            font-size: 8px;
            line-height: 1.2;
        }

        .ttd-box img {
            height: 38px;
            margin: 1px auto;
            display: block;
        }

        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
            body {
                background: #ffffff !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
            .no-print {
                display: none !important;
            }
            .cards-grid {
                gap: 14px;
            }
            .card-petugas {
                box-shadow: none;
                border-color: #000000;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
            .badge-title {
                color: #ffffff !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
            .photo-frame {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <div>
            <strong style="font-size: 14px;">Kartu Tanda Pengenal Panitia & Petugas Asesmen CBT</strong> &bull; Total {{ count($daftarPetugas) }} Petugas
            <div style="font-size: 11.5px; opacity: 0.9; margin-top: 3px;">
                💡 <em>Tips Cetak:</em> Pastikan opsi <strong>"Grafik latar belakang" (Background graphics)</strong> dicentang pada jendela cetak browser agar warna pita tugas tercetak jelas.
            </div>
        </div>
        <div>
            <button onclick="window.print()" style="background: #0077b6; color: #fff; border: none; padding: 8px 18px; font-weight: bold; cursor: pointer; border-radius: 4px; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
                🖨️ Cetak Semua Kartu Petugas (Print / PDF)
            </button>
        </div>
    </div>

    <div class="cards-grid">
        @forelse ($daftarPetugas as $p)
        <div class="card-petugas">
            <!-- KOP RESMI DINAS & SEKOLAH -->
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

            @php
                $roleLower = strtolower($p->jenis_petugas ?? 'pengawas');
                $badgeBg = '#0f2b48';
                if (str_contains($roleLower, 'pengawas')) {
                    $badgeBg = '#065f46';
                } elseif (str_contains($roleLower, 'proktor')) {
                    $badgeBg = '#b45309';
                } elseif (str_contains($roleLower, 'teknisi')) {
                    $badgeBg = '#0284c7';
                } elseif (str_contains($roleLower, 'ketua') || str_contains($roleLower, 'penanggung')) {
                    $badgeBg = '#1e1b4b';
                } elseif (str_contains($roleLower, 'sekretaris') || str_contains($roleLower, 'bendahara')) {
                    $badgeBg = '#581c87';
                } elseif (str_contains($roleLower, 'koordinator')) {
                    $badgeBg = '#0f766e';
                }
            @endphp

            <div class="badge-title" style="background-color: {{ $badgeBg }} !important; color: #ffffff !important; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; color-adjust: exact !important;">
                KARTU TANDA PENGENAL {{ strtoupper($p->jenis_petugas) }}
            </div>

            <div class="card-body">
                <div class="photo-frame">
                    FOTO<br>3 x 4
                </div>
                <table class="data-table">
                    <tr>
                        <td class="label">Nama Petugas</td>
                        <td class="colon">:</td>
                        <td class="val" style="font-size: 10px; color: #0f2b48;">{{ strtoupper($p->user->name ?? '-') }}</td>
                    </tr>
                    <tr>
                        <td class="label">NIP / NUPTK</td>
                        <td class="colon">:</td>
                        <td class="val">{{ $p->user->nip ?: ($p->user->nuptk ?: '-') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Peran Tugas</td>
                        <td class="colon">:</td>
                        <td class="val" style="color: #0f2b48; font-weight: 700;">
                            {{ ucwords(str_replace('_', ' ', $p->jenis_petugas)) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Ruangan & Sesi</td>
                        <td class="colon">:</td>
                        <td class="val">{{ $p->ruangan }} &bull; {{ $p->sesi }}</td>
                    </tr>
                    <tr>
                        <td class="label">Nomor SK</td>
                        <td class="colon">:</td>
                        <td class="val" style="font-size: 8.5px;">{{ $p->nomor_sk ?: '421.5/012/SMKN1.KB/2026' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Masa Tugas</td>
                        <td class="colon">:</td>
                        <td class="val">TP. {{ $sekolah->tahun_ajaran_aktif ?? '2026/2027' }}</td>
                    </tr>
                </table>
            </div>

            <div class="card-footer">
                <div class="barcode-box">
                    <div>* PETUGAS-{{ strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $p->jenis_petugas)) }}-{{ $p->id }} *</div>
                    <div style="font-size: 7px; color: #94a3b8; margin-top: 2px;">Wajib Dikenakan Selama Ujian</div>
                </div>

                <div class="ttd-box">
                    <div>{{ $sekolah->titimangsa_lengkap }}</div>
                    <div>Kepala Sekolah,</div>
                    <img src="{{ $sekolah->ttd_kepala_sekolah_url }}" alt="TTD">
                    <div><strong><u>{{ $sekolah->kepala_sekolah }}</u></strong></div>
                    <div>NIP. {{ $sekolah->nip_kepala_sekolah }}</div>
                </div>
            </div>
        </div>
        @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 50px; background: #fff; border-radius: 6px;">
            <h3>Belum Ada Petugas yang Ditugaskan</h3>
            <p style="color: #64748b; margin-top: 6px;">Silakan tambahkan Proktor atau Pengawas terlebih dahulu melalui menu Petugas & Pengawas di panel admin.</p>
        </div>
        @endforelse
    </div>

</body>
</html>
