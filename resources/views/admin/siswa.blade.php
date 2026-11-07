@extends('layouts.admin')

@section('title', 'Data Siswa Peserta Asesmen')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">Data Siswa Peserta Asesmen</h2>
        <p class="page-subtitle">Kelola master siswa, impor massal XLSX/CSV, kelola akun login ujian, dan status sesi.</p>
    </div>
    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
        <button type="button" class="btn btn-primary btn-sm" onclick="openModal('modalTambahSiswa')">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Tambah Siswa
        </button>
        <button type="button" class="btn btn-accent btn-sm" onclick="openModal('modalUploadFotoZip')" title="Unggah Foto Siswa Massal Berkas ZIP">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
            Upload Foto ZIP (NISN)
        </button>
        <button type="button" class="btn btn-secondary btn-sm" onclick="openModal('modalImportSiswa')">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
            Impor XLSX / CSV
        </button>
        <a href="{{ route('admin.siswa.template_xlsx') }}" class="btn btn-secondary btn-sm" title="Unduh format template Excel resmi">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
            Unduh Template XLSX
        </a>
        <a href="{{ route('admin.siswa.export_xlsx') }}" class="btn btn-secondary btn-sm" title="Ekspor seluruh data siswa ke berkas Excel">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
            Ekspor ke XLSX
        </a>
        <a href="{{ route('admin.cetak_kartu') }}" target="_blank" class="btn btn-secondary btn-sm">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
            Cetak Kartu Login A4
        </a>
    </div>
</div>

<!-- Filter & Pencarian -->
<div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 14px; margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
    <form action="{{ route('admin.siswa') }}" method="GET" style="display: flex; gap: 10px; align-items: center; flex: 1; flex-wrap: wrap;">
        <select name="kelas_id" onchange="this.form.submit()" style="padding: 7px 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
            <option value="">-- Semua Rombel Kelas --</option>
            @foreach ($kelasList as $k)
                <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }} ({{ $k->jurusan }})</option>
            @endforeach
        </select>

        <div style="position: relative; flex: 1; max-width: 320px;">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, NISN, no. peserta..." style="width: 100%; padding: 7px 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
        </div>

        <button type="submit" class="btn btn-secondary btn-sm">Filter / Cari</button>
        @if (request()->hasAny(['kelas_id', 'q']))
            <a href="{{ route('admin.siswa') }}" class="btn btn-secondary btn-sm" style="color: #64748b;">Reset</a>
        @endif
    </form>

    <div style="font-size: 12px; color: var(--text-muted);">
        Menampilkan <strong>{{ $siswaList->total() }}</strong> Siswa Terdaftar
    </div>
</div>

<div class="table-container">
    <table class="table-compact">
        <thead>
            <tr>
                <th style="width: 35px;">No.</th>
                <th>Nomor Peserta</th>
                <th>NISN / Username</th>
                <th>Nama Lengkap Siswa</th>
                <th>Kelas / Rombel</th>
                <th>L/P</th>
                <th>Sesi & Ruang</th>
                <th>Status Sesi</th>
                <th style="text-align: center; width: 140px;">Tindakan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($siswaList as $idx => $s)
            <tr>
                <td>{{ $siswaList->firstItem() + $idx }}</td>
                <td><code>{{ $s->nomor_peserta ?? '-' }}</code></td>
                <td><strong>{{ $s->nisn }}</strong></td>
                <td>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        @if ($s->foto && file_exists(public_path($s->foto)))
                            <img src="{{ asset($s->foto) }}" alt="Foto {{ $s->nama_lengkap }}" style="width: 28px; height: 34px; object-fit: cover; border-radius: 3px; border: 1px solid var(--border);">
                        @else
                            <div style="width: 28px; height: 34px; background: #f1f5f9; color: #94a3b8; font-size: 8px; display: flex; align-items: center; justify-content: center; border-radius: 3px; border: 1px dashed #cbd5e1; text-align: center; line-height: 1;">No Foto</div>
                        @endif
                        <div>
                            <strong>{{ $s->nama_lengkap }}</strong>
                            <div style="font-size: 11px; color: var(--text-muted);">NIS: {{ $s->nis ?: '-' }}</div>
                        </div>
                    </div>
                </td>
                <td><span class="badge badge-neutral">{{ $s->kelas->nama_kelas ?? '-' }}</span></td>
                <td>{{ $s->jenis_kelamin }}</td>
                <td>{{ $s->sesi_ujian }} &bull; {{ $s->ruangan_ujian }}</td>
                <td>
                    @if ($s->status_login === 'online')
                        <span class="badge badge-success">Online</span>
                    @elseif ($s->status_login === 'sedang_tes')
                        <span class="badge badge-warning">Sedang Tes</span>
                    @elseif ($s->status_login === 'selesai')
                        <span class="badge badge-info">Selesai</span>
                    @else
                        <span class="badge badge-neutral">Offline</span>
                    @endif
                </td>
                <td style="text-align: center;">
                    <div style="display: flex; gap: 4px; justify-content: center;">
                        <button type="button" class="btn btn-secondary btn-sm" title="Edit Siswa" onclick='editSiswa(@json($s))'>
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </button>
                        <form action="{{ route('admin.reset_login', $s->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-secondary btn-sm" title="Reset Sesi Login">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 2v6h-6"></path><path d="M3 12a9 9 0 0 1 15-6.7L21 8"></path><path d="M3 22v-6h6"></path><path d="M21 12a9 9 0 0 1-15 6.7L3 16"></path></svg>
                            </button>
                        </form>
                        <form action="{{ route('admin.siswa.hapus', $s->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus siswa {{ $s->nama_lengkap }} beserta seluruh lembar jawabannya?');">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus Siswa">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center; padding: 32px; color: #64748b;">
                    Tidak ada data siswa ditemukan. Gunakan tombol 'Tambah Siswa' atau 'Impor XLSX / CSV' di atas.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 16px;">
    {{ $siswaList->links() }}
</div>

<!-- MODAL TAMBAH SISWA -->
<div id="modalTambahSiswa" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15,23,42,0.6); z-index: 999; align-items: center; justify-content: center;">
    <div style="background: #ffffff; border-radius: var(--radius); width: 95%; max-width: 580px; padding: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--primary);">Tambah Peserta Didik Baru</h3>
            <button type="button" onclick="closeModal('modalTambahSiswa')" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #64748b;">&times;</button>
        </div>

        <form action="{{ route('admin.siswa.simpan') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">NISN (Wajib / Login) *</label>
                    <input type="text" name="nisn" required class="form-control" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;" placeholder="Contoh: 0054819201">
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">NIS Sekolah</label>
                    <input type="text" name="nis" class="form-control" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;" placeholder="Contoh: 22101">
                </div>
            </div>

            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Nama Lengkap Siswa *</label>
                <input type="text" name="nama_lengkap" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;" placeholder="Nama sesuai dapodik">
            </div>

            <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 12px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Kelas / Rombel *</label>
                    <select name="kelas_id" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                        @foreach ($kelasList as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas }} ({{ $k->jurusan }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Jenis Kelamin *</label>
                    <select name="jenis_kelamin" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                        <option value="L">Laki-Laki (L)</option>
                        <option value="P">Perempuan (P)</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Sesi Ujian *</label>
                    <input type="text" name="sesi_ujian" value="Sesi 1" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Ruangan Ujian *</label>
                    <input type="text" name="ruangan_ujian" value="Lab Komputer 1" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 12px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Nomor Peserta Ujian</label>
                    <input type="text" name="nomor_peserta" placeholder="Otomatis jika kosong" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Kata Sandi (Default: 12345)</label>
                    <input type="text" name="password" value="12345" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Foto Siswa (Opsional, JPG/PNG)</label>
                <input type="file" name="foto" accept="image/*" style="width: 100%; font-size: 12px;">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeModal('modalTambahSiswa')">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm">Simpan Data Siswa</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT SISWA -->
<div id="modalEditSiswa" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15,23,42,0.6); z-index: 999; align-items: center; justify-content: center;">
    <div style="background: #ffffff; border-radius: var(--radius); width: 95%; max-width: 580px; padding: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--primary);">Ubah Data Siswa</h3>
            <button type="button" onclick="closeModal('modalEditSiswa')" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #64748b;">&times;</button>
        </div>

        <form id="formEditSiswa" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">NISN (Wajib / Login) *</label>
                    <input type="text" name="nisn" id="edit_nisn" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">NIS Sekolah</label>
                    <input type="text" name="nis" id="edit_nis" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
            </div>

            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Nama Lengkap Siswa *</label>
                <input type="text" name="nama_lengkap" id="edit_nama" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
            </div>

            <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 12px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Kelas / Rombel *</label>
                    <select name="kelas_id" id="edit_kelas_id" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                        @foreach ($kelasList as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas }} ({{ $k->jurusan }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Jenis Kelamin *</label>
                    <select name="jenis_kelamin" id="edit_jk" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                        <option value="L">Laki-Laki (L)</option>
                        <option value="P">Perempuan (P)</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Sesi Ujian *</label>
                    <input type="text" name="sesi_ujian" id="edit_sesi" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Ruangan Ujian *</label>
                    <input type="text" name="ruangan_ujian" id="edit_ruang" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 12px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Nomor Peserta Ujian</label>
                    <input type="text" name="nomor_peserta" id="edit_nopes" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Ganti Password (Kosongkan jika tetap)</label>
                    <input type="text" name="password" placeholder="Biarkan kosong jika tidak diubah" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Ganti Foto Siswa (Opsional, JPG/PNG)</label>
                <input type="file" name="foto" accept="image/*" style="width: 100%; font-size: 12px;">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeModal('modalEditSiswa')">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL UPLOAD FOTO ZIP -->
<div id="modalUploadFotoZip" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15,23,42,0.6); z-index: 999; align-items: center; justify-content: center;">
    <div style="background: #ffffff; border-radius: var(--radius); width: 95%; max-width: 540px; padding: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--primary);">Upload Foto Siswa Massal (.ZIP)</h3>
            <button type="button" onclick="closeModal('modalUploadFotoZip')" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #64748b;">&times;</button>
        </div>

        <p style="font-size: 12.5px; color: var(--text-muted); margin-bottom: 16px;">
            Unggah satu berkas <strong>.ZIP</strong> yang berisi foto seluruh peserta ujian. Sistem akan otomatis mengekstrak dan mencocokkan foto dengan NISN siswa.
        </p>

        <div style="background: #f0fdf4; border-left: 4px solid #16a34a; padding: 12px 14px; border-radius: var(--radius-sm); margin-bottom: 16px; font-size: 12px; line-height: 1.6; color: #166534;">
            <strong>Ketentuan Format Foto dalam ZIP:</strong><br>
            &bull; Nama file foto <strong>WAJIB menggunakan NISN Siswa</strong> (Contoh: <code>0103841671.jpg</code> atau <code>0081234567.png</code>).<br>
            &bull; Format gambar yang didukung: <code>.jpg</code>, <code>.jpeg</code>, <code>.png</code>, <code>.webp</code>.<br>
            &bull; Semua foto langsung ditaruh di dalam arsip ZIP (atau dalam folder tunggal).<br>
            &bull; Ukuran rasio pas foto disarankan 2x3 atau 3x4 formal.
        </div>

        <form action="{{ route('admin.siswa.upload_foto_zip') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 12.5px; font-weight: 600; margin-bottom: 6px;">Pilih Berkas ZIP Foto Siswa:</label>
                <input type="file" name="file_zip" accept=".zip,application/zip" required style="width: 100%; font-size: 13px; padding: 8px; border: 1px solid var(--border); border-radius: var(--radius-sm);">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeModal('modalUploadFotoZip')">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm">Mulai Ekstrak & Pasangkan Foto</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL IMPOR XLSX / CSV -->
<div id="modalImportSiswa" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15,23,42,0.6); z-index: 999; align-items: center; justify-content: center;">
    <div style="background: #ffffff; border-radius: var(--radius); width: 95%; max-width: 520px; padding: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--primary);">Impor Massal Data Siswa (XLSX / CSV)</h3>
            <button type="button" onclick="closeModal('modalImportSiswa')" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #64748b;">&times;</button>
        </div>

        <p style="font-size: 12.5px; color: var(--text-muted); margin-bottom: 16px;">
            Unggah file Excel (.xlsx / .csv) berisi daftar peserta didik. Sistem akan secara otomatis membuat akun login dan profil peserta.
        </p>

        <div style="background: #f8fafc; border: 1px solid var(--border-light); padding: 12px; border-radius: var(--radius-sm); margin-bottom: 16px; font-size: 12px; line-height: 1.6;">
            <strong>Urutan Kolom File Excel:</strong><br>
            A = NIS | B = NISN (Wajib) | C = Nama Lengkap (Wajib) | D = JK (L/P) | E = Kode Kelas (Contoh: XI-MM) | F = Sesi | G = Ruang | H = No. Peserta | I = Password
        </div>

        <form action="{{ route('admin.siswa.import_xlsx') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 12.5px; font-weight: 600; margin-bottom: 6px;">Pilih Berkas XLSX / CSV:</label>
                <input type="file" name="file_excel" accept=".xlsx,.xls,.csv" required style="width: 100%; font-size: 13px;">
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center;">
                <a href="{{ route('admin.siswa.template_xlsx') }}" style="font-size: 12px; color: var(--accent); text-decoration: none; font-weight: 600;">
                    &darr; Unduh Template Format Excel
                </a>
                <div style="display: flex; gap: 8px;">
                    <button type="button" class="btn btn-secondary btn-sm" onclick="closeModal('modalImportSiswa')">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Mulai Impor Siswa</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openModal(id) {
        document.getElementById(id).style.display = 'flex';
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }

    function editSiswa(siswa) {
        document.getElementById('edit_nisn').value = siswa.nisn || '';
        document.getElementById('edit_nis').value = siswa.nis || '';
        document.getElementById('edit_nama').value = siswa.nama_lengkap || '';
        document.getElementById('edit_kelas_id').value = siswa.kelas_id || '';
        document.getElementById('edit_jk').value = siswa.jenis_kelamin || 'L';
        document.getElementById('edit_sesi').value = siswa.sesi_ujian || 'Sesi 1';
        document.getElementById('edit_ruang').value = siswa.ruangan_ujian || 'Lab Komputer 1';
        document.getElementById('edit_nopes').value = siswa.nomor_peserta || '';

        document.getElementById('formEditSiswa').action = '/admin/siswa/' + siswa.id + '/update';
        openModal('modalEditSiswa');
    }
</script>
@endsection
