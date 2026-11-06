@extends('layouts.admin')

@section('title', 'Pengaturan Sekolah & TTD Kepala Sekolah')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">Pengaturan Identitas Sekolah & Pejabat</h2>
        <p class="page-subtitle">Sesuaikan data identitas resmi sekolah, tahun ajaran aktif, dan tanda tangan Kepala Sekolah untuk dokumen cetak dinas.</p>
    </div>
</div>

<div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 24px; max-width: 800px;">
    <form action="{{ route('admin.pengaturan.simpan') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 6px;">Nama Sekolah Resmi</label>
            <input type="text" name="nama_sekolah" value="{{ old('nama_sekolah', $sekolah->nama_sekolah ?? 'SMK NEGERI 1 KUPANG BARAT') }}" required style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: var(--radius); font-size: 13px;">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
            <div>
                <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 6px;">NPSN Sekolah</label>
                <input type="text" name="npsn" value="{{ old('npsn', $sekolah->npsn ?? '69984954') }}" required style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: var(--radius); font-size: 13px;">
            </div>
            <div>
                <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 6px;">Alamat / Desa</label>
                <input type="text" name="alamat" value="{{ old('alamat', $sekolah->alamat ?? 'Desa Kuanheun') }}" required style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: var(--radius); font-size: 13px;">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
            <div>
                <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 6px;">Kecamatan</label>
                <input type="text" name="kecamatan" value="{{ old('kecamatan', $sekolah->kecamatan ?? 'Kupang Barat') }}" required style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: var(--radius); font-size: 13px;">
            </div>
            <div>
                <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 6px;">Kabupaten</label>
                <input type="text" name="kabupaten" value="{{ old('kabupaten', $sekolah->kabupaten ?? 'Kabupaten Kupang') }}" required style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: var(--radius); font-size: 13px;">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
            <div>
                <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 6px;">Pemerintah Provinsi (Kop Baris 1)</label>
                <input type="text" name="provinsi" value="{{ old('provinsi', $sekolah->provinsi ?? 'Nusa Tenggara Timur') }}" required style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: var(--radius); font-size: 13px;">
            </div>
            <div>
                <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 6px;">Dinas Pendidikan (Kop Baris 2)</label>
                <input type="text" name="dinas_pendidikan" value="{{ old('dinas_pendidikan', $sekolah->dinas_pendidikan ?? 'DINAS PENDIDIKAN DAN KEBUDAYAAN') }}" required style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: var(--radius); font-size: 13px;">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
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

        <hr style="border: none; border-top: 1px solid var(--border-light); margin: 24px 0;">

        <h3 style="font-size: 14px; font-weight: 700; margin-bottom: 14px; color: var(--primary);">Identitas Pejabat Kepala Sekolah</h3>

        <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 16px; margin-bottom: 20px;">
            <div>
                <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 6px;">Nama Kepala Sekolah (Lengkap dengan Gelar)</label>
                <input type="text" name="kepala_sekolah" value="{{ old('kepala_sekolah', $sekolah->kepala_sekolah ?? 'Vicky Koroh, S.Pd., M.Kom.') }}" required style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: var(--radius); font-size: 13px;">
            </div>
            <div>
                <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 6px;">NIP Kepala Sekolah</label>
                <input type="text" name="nip_kepala_sekolah" value="{{ old('nip_kepala_sekolah', $sekolah->nip_kepala_sekolah ?? '198501192011011005') }}" style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: var(--radius); font-size: 13px;">
            </div>
        </div>

        <div style="margin-bottom: 24px;">
            <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 6px;">Tanda Tangan Kepala Sekolah (Format PNG Transparan)</label>
            <div style="display: flex; align-items: center; gap: 16px; margin-top: 8px;">
                <div style="width: 140px; height: 80px; border: 1px dashed var(--border); background: #f8fafc; border-radius: var(--radius); display: flex; align-items: center; justify-content: center; overflow: hidden;">
                    <img src="{{ asset($sekolah->ttd_kepala_sekolah ?? 'images/ttd_kepsek.png') }}" alt="TTD Kepsek" style="max-height: 70px; max-width: 130px; object-fit: contain;">
                </div>
                <div style="flex: 1;">
                    <input type="file" name="ttd_kepala_sekolah" accept="image/png,image/jpeg" style="font-size: 13px;">
                    <p style="font-size: 11px; color: #64748b; margin-top: 4px;">Pilih berkas gambar PNG tanpa latar belakang agar tanda tangan menyatu rapi pada kartu ujian dan berita acara dinas.</p>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary" style="padding: 10px 20px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
            Simpan Perubahan Pengaturan
        </button>
    </form>
</div>
@endsection
