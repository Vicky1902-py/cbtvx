@extends('layouts.admin')

@section('title', 'Pengaturan Sekolah, Kop Dinas & TTD Pejabat')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">Pengaturan Identitas Sekolah & Kop Dinas Resmi</h2>
        <p class="page-subtitle">Pusat konfigurasi hierarki KOP dinas 3 baris, logo instansi, logo sekolah, serta pengesahan tanda tangan Kepala Sekolah yang berlaku serentak pada seluruh dokumen cetak.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; align-items: start;">
    <!-- FORM PENGATURAN -->
    <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 24px;">
        <form action="{{ route('admin.pengaturan.simpan') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <h3 style="font-size: 14px; font-weight: 700; margin-bottom: 14px; color: var(--primary);">1. Hierarki Lembaga & Kop Resmi Dinas (3 Baris)</h3>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 6px;">Kop Baris 1: Pemerintah Provinsi</label>
                <input type="text" name="provinsi" value="{{ old('provinsi', $sekolah->provinsi ?? 'Nusa Tenggara Timur') }}" required style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: var(--radius); font-size: 13px;">
                <p style="font-size: 11px; color: #64748b; margin-top: 4px;">Otomatis diformat menjadi: <strong>{{ $sekolah->kop_baris1 }}</strong></p>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 6px;">Kop Baris 2: Dinas Pendidikan</label>
                <input type="text" name="dinas_pendidikan" value="{{ old('dinas_pendidikan', $sekolah->dinas_pendidikan ?? 'DINAS PENDIDIKAN DAN KEBUDAYAAN') }}" required style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: var(--radius); font-size: 13px;">
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 6px;">Kop Baris 3: Nama Resmi Sekolah</label>
                <input type="text" name="nama_sekolah" value="{{ old('nama_sekolah', $sekolah->nama_sekolah ?? 'SMK NEGERI 1 KUPANG BARAT') }}" required style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: var(--radius); font-size: 13px;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 6px;">NPSN Sekolah</label>
                    <input type="text" name="npsn" value="{{ old('npsn', $sekolah->npsn ?? '69727541') }}" required style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: var(--radius); font-size: 13px;">
                </div>
                <div>
                    <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 6px;">Alamat / Desa</label>
                    <input type="text" name="alamat" value="{{ old('alamat', $sekolah->alamat ?? 'Desa Kuanheun, RT 02 / RW 02') }}" required style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: var(--radius); font-size: 13px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 6px;">Kecamatan (Titimangsa)</label>
                    <input type="text" name="kecamatan" value="{{ old('kecamatan', $sekolah->kecamatan ?? 'Kupang Barat') }}" required style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: var(--radius); font-size: 13px;">
                </div>
                <div>
                    <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 6px;">Kabupaten / Kota</label>
                    <input type="text" name="kabupaten" value="{{ old('kabupaten', $sekolah->kabupaten ?? 'Kabupaten Kupang') }}" required style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: var(--radius); font-size: 13px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 6px;">Tahun Ajaran Aktif</label>
                    <input type="text" name="tahun_ajaran_aktif" value="{{ old('tahun_ajaran_aktif', $sekolah->tahun_ajaran_aktif ?? '2026/2027') }}" required style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: var(--radius); font-size: 13px;">
                </div>
                <div>
                    <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 6px;">Semester Aktif</label>
                    <select name="semester_aktif" style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: var(--radius); font-size: 13px;">
                        <option value="Ganjil" {{ ($sekolah->semester_aktif ?? '') === 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                        <option value="Genap" {{ ($sekolah->semester_aktif ?? 'Genap') === 'Genap' ? 'selected' : '' }}>Genap</option>
                    </select>
                </div>
            </div>

            <hr style="border: none; border-top: 1px solid var(--border-light); margin: 20px 0;">

            <h3 style="font-size: 14px; font-weight: 700; margin-bottom: 14px; color: var(--primary);">2. Logo Kop Dokumen (Kiri & Kanan)</h3>

            <!-- LOGO PEMPROV (KIRI) -->
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 6px;">Logo Pemprov / Dinas (Sisi Kiri Kop)</label>
                <div style="display: flex; align-items: center; gap: 14px;">
                    <div style="width: 60px; height: 60px; border: 1px solid var(--border); background: #f8fafc; border-radius: var(--radius); display: flex; align-items: center; justify-content: center; padding: 4px;">
                        <img src="{{ $sekolah->logo_pemprov_url }}" alt="Logo Pemprov" style="max-height: 50px; max-width: 50px; object-fit: contain;">
                    </div>
                    <div style="flex: 1;">
                        <input type="file" name="logo_pemprov" accept="image/svg+xml,image/png,image/jpeg" style="font-size: 12px;">
                        <p style="font-size: 11px; color: #64748b; margin-top: 3px;">Format SVG atau PNG transparan (Default: Lambang Pemprov NTT).</p>
                    </div>
                </div>
            </div>

            <!-- LOGO SEKOLAH (KANAN) -->
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 6px;">Logo Resmi Sekolah (Sisi Kanan Kop)</label>
                <div style="display: flex; align-items: center; gap: 14px;">
                    <div style="width: 60px; height: 60px; border: 1px solid var(--border); background: #f8fafc; border-radius: var(--radius); display: flex; align-items: center; justify-content: center; padding: 4px;">
                        <img src="{{ $sekolah->logo_sekolah_url }}" alt="Logo SMK" style="max-height: 50px; max-width: 50px; object-fit: contain;">
                    </div>
                    <div style="flex: 1;">
                        <input type="file" name="logo_sekolah" accept="image/png,image/jpeg,image/svg+xml" style="font-size: 12px;">
                        <p style="font-size: 11px; color: #64748b; margin-top: 3px;">Format PNG transparan resolusi tinggi (Default: Logo SMKN 1 Kupang Barat).</p>
                    </div>
                </div>
            </div>

            <hr style="border: none; border-top: 1px solid var(--border-light); margin: 20px 0;">

            <h3 style="font-size: 14px; font-weight: 700; margin-bottom: 14px; color: var(--primary);">3. Pengesahan Kepala Sekolah & Tanda Tangan</h3>

            <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 12px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 6px;">Nama Kepala Sekolah (Lengkap & Gelar)</label>
                    <input type="text" name="kepala_sekolah" value="{{ old('kepala_sekolah', $sekolah->kepala_sekolah) }}" required style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: var(--radius); font-size: 13px;">
                </div>
                <div>
                    <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 6px;">NIP Kepala Sekolah</label>
                    <input type="text" name="nip_kepala_sekolah" value="{{ old('nip_kepala_sekolah', $sekolah->nip_kepala_sekolah) }}" style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: var(--radius); font-size: 13px;">
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 6px;">Berkas Tanda Tangan & Cap Stempel Kepala Sekolah</label>
                <div style="display: flex; align-items: center; gap: 14px;">
                    <div style="width: 110px; height: 65px; border: 1px dashed var(--border); background: #f8fafc; border-radius: var(--radius); display: flex; align-items: center; justify-content: center; overflow: hidden; padding: 4px;">
                        <img src="{{ $sekolah->ttd_kepala_sekolah_url }}" alt="TTD Kepsek" style="max-height: 55px; max-width: 100px; object-fit: contain;">
                    </div>
                    <div style="flex: 1;">
                        <input type="file" name="ttd_kepala_sekolah" accept="image/png,image/jpeg" style="font-size: 12px;">
                        <p style="font-size: 11px; color: #64748b; margin-top: 3px;">Gunakan berkas PNG transparan dengan stempel agar tanda tangan menyatu alami pada lembar cetak dinas.</p>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="padding: 10px 24px; font-weight: 600; width: 100%;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                Simpan & Sinkronkan ke Seluruh Dokumen Cetak
            </button>
        </form>
    </div>

    <!-- PRATINJAU LANGSUNG KOP & PENGESAHAN DOKUMEN CETAK DINAS -->
    <div style="position: sticky; top: 20px;">
        <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; border-bottom: 1px solid var(--border-light); padding-bottom: 10px;">
                <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #0284c7; background: #e0f2fe; padding: 4px 8px; border-radius: 4px;">
                    Pratinjau Standar Kop & Pengesahan Dokumen
                </span>
                <span style="font-size: 11px; color: #64748b;">Live Standard A4</span>
            </div>

            <!-- SIMULASI KOP RESMI DINAS -->
            <div style="border: 1px dashed #cbd5e1; border-radius: 6px; padding: 16px; background: #fdfdfd; margin-bottom: 20px;">
                <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 3px double #000; padding-bottom: 10px;">
                    <img src="{{ $sekolah->logo_pemprov_url }}" alt="Logo Pemprov" style="width: 50px; height: 50px; object-fit: contain;">
                    <div style="flex: 1; text-align: center; padding: 0 10px; font-family: 'Times New Roman', Times, serif;">
                        <h4 style="margin: 0; font-size: 11.5px; font-weight: 700; letter-spacing: 0.5px; color: #000; text-transform: uppercase;">
                            {{ $sekolah->kop_baris1 }}
                        </h4>
                        <h5 style="margin: 2px 0 0 0; font-size: 11px; font-weight: 700; letter-spacing: 0.5px; color: #000; text-transform: uppercase;">
                            {{ $sekolah->kop_baris2 }}
                        </h5>
                        <h3 style="margin: 3px 0 0 0; font-size: 13.5px; font-weight: 800; letter-spacing: 0.8px; color: #000; text-transform: uppercase;">
                            {{ $sekolah->kop_baris3 }}
                        </h3>
                        <p style="margin: 4px 0 0 0; font-size: 8.5px; color: #333; line-height: 1.3;">
                            {{ $sekolah->kop_alamat_lengkap }}
                        </p>
                    </div>
                    <img src="{{ $sekolah->logo_sekolah_url }}" alt="Logo SMK" style="width: 50px; height: 50px; object-fit: contain;">
                </div>

                <!-- SIMULASI PENGESAHAN TTD KEPALA SEKOLAH -->
                <div style="margin-top: 20px; display: flex; justify-content: flex-end;">
                    <div style="text-align: center; width: 220px; font-family: 'Times New Roman', Times, serif; font-size: 11px;">
                        <p style="margin: 0 0 4px 0;">{{ $sekolah->titimangsa_lengkap }}</p>
                        <p style="margin: 0 0 6px 0;">Kepala Sekolah,</p>
                        <div style="height: 55px; display: flex; align-items: center; justify-content: center; margin: 4px 0;">
                            <img src="{{ $sekolah->ttd_kepala_sekolah_url }}" alt="TTD & Cap" style="max-height: 55px; max-width: 140px; object-fit: contain;">
                        </div>
                        <p style="margin: 0; font-weight: bold; text-decoration: underline;">{{ $sekolah->kepala_sekolah }}</p>
                        <p style="margin: 2px 0 0 0; font-size: 10px;">NIP. {{ $sekolah->nip_kepala_sekolah }}</p>
                    </div>
                </div>
            </div>

            <!-- DOKUMEN YANG TERSINKRONISASI -->
            <div style="background: #f8fafc; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 12px;">
                <p style="font-size: 11.5px; font-weight: 600; color: #334155; margin-bottom: 8px;">
                    Dokumen resmi yang otomatis tersinkronisasi 100%:
                </p>
                <ul style="font-size: 11px; color: #64748b; margin: 0; padding-left: 18px; line-height: 1.6;">
                    <li><strong>Kartu Login Peserta A4</strong> (cetak massal per kelas dengan kop, foto, akun & TTD kepsek)</li>
                    <li><strong>Kartu Tanda Pengenal Proktor & Pengawas A4</strong> (kop resmi, foto 3x4, nomor SK & TTD kepsek)</li>
                    <li><strong>Berita Acara Pelaksanaan CBT A4</strong> (kop dinas 3 baris, rekap kehadiran & 3 tanda tangan: Pengawas, Proktor, Kepsek)</li>
                    <li><strong>Daftar Hadir Peserta Ujian A4</strong> (kop dinas 3 baris, tabel absensi & tanda tangan pengesahan)</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
