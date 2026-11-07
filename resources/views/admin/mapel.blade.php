@extends('layouts.admin')

@section('title', 'Mata Pelajaran Asesmen')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">Mata Pelajaran Asesmen (Khas SMK)</h2>
        <p class="page-subtitle">Manajemen mata pelajaran muatan umum dan kejuruan spesifik per jurusan dan guru pengampu.</p>
    </div>
    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
        <a href="{{ route('admin.mapel.template_xlsx') }}" class="btn btn-secondary btn-sm" title="Unduh format template Excel untuk pengisian massal">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
            Unduh Template XLSX
        </a>
        <button type="button" class="btn btn-secondary btn-sm" onclick="openModal('modalImportMapel')">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
            Impor XLSX Mapel
        </button>
        <a href="{{ route('admin.mapel.export_xlsx') }}" class="btn btn-secondary btn-sm">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
            Ekspor XLSX
        </a>
        <button type="button" class="btn btn-primary btn-sm" onclick="openModal('modalTambahMapel')">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Tambah Mata Pelajaran
        </button>
    </div>
</div>

<!-- Filter & Search Toolbar -->
<div class="card" style="margin-bottom: 16px; padding: 14px 16px;">
    <form action="{{ route('admin.mapel') }}" method="GET" style="display: grid; grid-template-columns: 2fr 1.3fr 1.5fr 1fr 1.5fr auto; gap: 10px; align-items: end;">
        <div>
            <label style="display: block; font-size: 11px; font-weight: 600; color: #475569; margin-bottom: 4px;">Pencarian (Kode / Nama)</label>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari Matematika, DPIB, BIN..." style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 12.5px;">
        </div>
        <div>
            <label style="display: block; font-size: 11px; font-weight: 600; color: #475569; margin-bottom: 4px;">Kelompok Mapel</label>
            <select name="kelompok" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 12.5px;">
                <option value="">-- Semua Kelompok --</option>
                <option value="Mata Pelajaran Pilihan (MPP)" {{ request('kelompok') === 'Mata Pelajaran Pilihan (MPP)' ? 'selected' : '' }}>Mata Pelajaran Pilihan (MPP)</option>
                <option value="Dasar Program Keahlian (DKK)" {{ request('kelompok') === 'Dasar Program Keahlian (DKK)' ? 'selected' : '' }}>Dasar Program Keahlian (DKK)</option>
                <option value="Konsentrasi Keahlian (KK)" {{ request('kelompok') === 'Konsentrasi Keahlian (KK)' ? 'selected' : '' }}>Konsentrasi Keahlian (KK)</option>
                <option value="Muatan Umum (A)" {{ request('kelompok') === 'Muatan Umum (A)' ? 'selected' : '' }}>Muatan Umum (A)</option>
                <option value="Muatan Kewilayahan (B)" {{ request('kelompok') === 'Muatan Kewilayahan (B)' ? 'selected' : '' }}>Muatan Kewilayahan (B)</option>
                <option value="Muatan Lokal" {{ request('kelompok') === 'Muatan Lokal' ? 'selected' : '' }}>Muatan Lokal</option>
            </select>
        </div>
        <div>
            <label style="display: block; font-size: 11px; font-weight: 600; color: #475569; margin-bottom: 4px;">Jurusan Sasaran</label>
            <select name="jurusan" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 12.5px;">
                <option value="">-- Semua Jurusan --</option>
                <option value="Semua Jurusan" {{ request('jurusan') === 'Semua Jurusan' ? 'selected' : '' }}>Semua Jurusan (Lintas Jurusan)</option>
                <option value="Desain Pemodelan dan Informasi Bangunan" {{ request('jurusan') === 'Desain Pemodelan dan Informasi Bangunan' ? 'selected' : '' }}>DPIB (Teknik Bangunan)</option>
                <option value="Teknik Instalasi Tenaga Listrik" {{ request('jurusan') === 'Teknik Instalasi Tenaga Listrik' ? 'selected' : '' }}>TITL / TKL (Listrik)</option>
                <option value="Teknik Kendaraan Ringan" {{ request('jurusan') === 'Teknik Kendaraan Ringan' ? 'selected' : '' }}>TKR (Otomotif Ringan)</option>
                <option value="Teknik Otomotif" {{ request('jurusan') === 'Teknik Otomotif' ? 'selected' : '' }}>Teknik Otomotif (TO)</option>
                <option value="Teknik Pengelasan" {{ request('jurusan') === 'Teknik Pengelasan' ? 'selected' : '' }}>Teknik Pengelasan (TP - Kelas XI & XII)</option>
                <option value="Teknik Pengelasan dan Fabrikasi Logam" {{ request('jurusan') === 'Teknik Pengelasan dan Fabrikasi Logam' ? 'selected' : '' }}>TPFL (Teknik Pengelasan dan Fabrikasi Logam - Kelas X)</option>
            </select>
        </div>
        <div>
            <label style="display: block; font-size: 11px; font-weight: 600; color: #475569; margin-bottom: 4px;">Tingkat</label>
            <select name="tingkat" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 12.5px;">
                <option value="">Semua</option>
                <option value="Semua" {{ request('tingkat') === 'Semua' ? 'selected' : '' }}>Lintas Tingkat</option>
                <option value="10" {{ request('tingkat') === '10' ? 'selected' : '' }}>Kelas 10</option>
                <option value="11" {{ request('tingkat') === '11' ? 'selected' : '' }}>Kelas 11</option>
                <option value="12" {{ request('tingkat') === '12' ? 'selected' : '' }}>Kelas 12</option>
            </select>
        </div>
        <div>
            <label style="display: block; font-size: 11px; font-weight: 600; color: #475569; margin-bottom: 4px;">Guru Pengampu</label>
            <select name="guru_id" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 12.5px;">
                <option value="">-- Semua Guru --</option>
                @foreach ($guruList as $g)
                    <option value="{{ $g->id }}" {{ request('guru_id') == $g->id ? 'selected' : '' }}>{{ $g->name }}</option>
                @endforeach
            </select>
        </div>
        <div style="display: flex; gap: 6px;">
            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
            <a href="{{ route('admin.mapel') }}" class="btn btn-secondary btn-sm" title="Reset filter">Reset</a>
        </div>
    </form>
</div>

<div class="table-container">
    <table class="table-compact">
        <thead>
            <tr>
                <th style="width: 40px;">No.</th>
                <th>Kode Mapel</th>
                <th>Nama Mata Pelajaran</th>
                <th>Kelompok</th>
                <th>Jurusan Sasaran</th>
                <th style="width: 90px;">Tingkat</th>
                <th>Guru Pengampu (Dapodik)</th>
                <th style="text-align: center; width: 100px;">Bank Soal</th>
                <th style="text-align: center; width: 130px;">Tindakan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($mapelList as $idx => $m)
            <tr>
                <td>{{ $mapelList->firstItem() + $idx }}</td>
                <td><code>{{ $m->kode_mapel }}</code></td>
                <td>
                    <strong style="color: var(--primary); font-size: 13px;">{{ $m->nama_mapel }}</strong>
                </td>
                <td>
                    @if (str_contains($m->kelompok, 'Pilihan') || str_contains($m->kelompok, 'MPP'))
                        <span class="badge" style="background: #f3e8ff; color: #7e22ce; border: 1px solid #d8b4fe; font-size: 11px; font-weight: 700;">{{ $m->kelompok }}</span>
                    @elseif (str_contains($m->kelompok, 'DKK') || str_contains($m->kelompok, 'C2') || str_contains($m->kelompok, 'Dasar'))
                        <span class="badge badge-warning" style="font-size: 11px;">{{ $m->kelompok }}</span>
                    @elseif (str_contains($m->kelompok, 'KK') || str_contains($m->kelompok, 'C3') || str_contains($m->kelompok, 'Konsentrasi'))
                        <span class="badge badge-success" style="font-size: 11px;">{{ $m->kelompok }}</span>
                    @elseif (str_contains($m->kelompok, 'Umum') || str_contains($m->kelompok, 'Nasional'))
                        <span class="badge badge-info" style="font-size: 11px;">{{ $m->kelompok }}</span>
                    @else
                        <span class="badge badge-neutral" style="font-size: 11px;">{{ $m->kelompok }}</span>
                    @endif
                </td>
                <td>
                    @if ($m->jurusan === 'Semua Jurusan')
                        <span style="font-size: 12px; color: #475569;">Semua Jurusan</span>
                    @else
                        <span style="font-size: 12px; font-weight: 600; color: #0369a1;">
                            {{ $m->jurusan }}
                        </span>
                    @endif
                </td>
                <td>
                    @if ($m->tingkat === 'Semua')
                        <span class="badge badge-neutral">Semua</span>
                    @else
                        <span class="badge badge-info">Kelas {{ $m->tingkat }}</span>
                    @endif
                </td>
                <td>
                    @if ($m->guru)
                        <div style="font-weight: 600; font-size: 12.5px; color: var(--text-main);">{{ $m->guru->name }}</div>
                        <div style="font-size: 11px; color: #64748b;">
                            NIP: {{ $m->guru->nip ?: ($m->guru->nuptk ?: 'Honor Sekolah') }}
                        </div>
                    @else
                        <span style="color: #94a3b8; font-style: italic; font-size: 11.5px;">Belum Ditentukan</span>
                    @endif
                </td>
                <td style="text-align: center;">
                    <span class="badge badge-info">{{ $m->bank_soal_count }} Paket</span>
                </td>
                <td style="text-align: center;">
                    <div style="display: flex; gap: 6px; justify-content: center;">
                        <button type="button" class="btn btn-secondary btn-sm" onclick='editMapel(@json($m))'>Edit</button>
                        <form action="{{ route('admin.mapel.hapus', $m->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus mata pelajaran {{ $m->nama_mapel }}?');">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center; padding: 32px; color: #64748b;">
                    Tidak ditemukan mata pelajaran sesuai filter yang dipilih. Silakan tambah mata pelajaran atau ubah kriteria pencarian.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 16px;">
    {{ $mapelList->links() }}
</div>

<!-- Modal Tambah Mapel -->
<div id="modalTambahMapel" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15,23,42,0.6); z-index: 999; align-items: center; justify-content: center;">
    <div style="background: #ffffff; border-radius: var(--radius); width: 95%; max-width: 540px; padding: 24px; max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--primary);">Tambah Mata Pelajaran Baru</h3>
            <button type="button" onclick="closeModal('modalTambahMapel')" style="background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
        </div>
        <form action="{{ route('admin.mapel.simpan') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1.6fr; gap: 12px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Kode Mapel (Unik) *</label>
                    <input type="text" name="kode_mapel" required placeholder="Contoh: MTK-DPIB-X" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Nama Lengkap Mapel *</label>
                    <input type="text" name="nama_mapel" required placeholder="Contoh: Matematika Terapan" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 12px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Kelompok Mapel *</label>
                    <input list="list_kelompok_tambah" name="kelompok" id="tambah_kelompok" required placeholder="Pilih atau ketik kelompok..." style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                    <datalist id="list_kelompok_tambah">
                        <option value="Mata Pelajaran Pilihan (MPP)">
                        <option value="Dasar Program Keahlian (DKK)">
                        <option value="Konsentrasi Keahlian (KK)">
                        <option value="Muatan Umum (A)">
                        <option value="Muatan Kewilayahan (B)">
                        <option value="Muatan Lokal">
                    </datalist>
                    <div style="font-size: 11px; color: #64748b; margin-top: 3px;">
                        Pilih saran dari daftar atau ketik kelompok bebas.
                    </div>
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Tingkat Sasaran *</label>
                    <select name="tingkat" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                        <option value="Semua">Semua Tingkat (Lintas Kelas)</option>
                        <option value="10">Kelas 10 (Fase E)</option>
                        <option value="11">Kelas 11 (Fase F)</option>
                        <option value="12">Kelas 12 (Fase F)</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Jurusan Sasaran *</label>
                <input list="list_jurusan_tambah" name="jurusan" id="tambah_jurusan" required placeholder="Pilih atau ketik nama jurusan sasaran..." style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                <datalist id="list_jurusan_tambah">
                    <option value="Semua Jurusan">
                    <option value="Desain Pemodelan dan Informasi Bangunan">
                    <option value="Teknik Instalasi Tenaga Listrik">
                    <option value="Teknik Kendaraan Ringan">
                    <option value="Teknik Otomotif">
                    <option value="Teknik Pengelasan">
                    <option value="Teknik Pengelasan dan Fabrikasi Logam">
                </datalist>
                <div style="font-size: 11px; color: #64748b; margin-top: 3px;">
                    Pilih jurusan sasaran atau ketik nama jurusan kustom bebas.
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Guru Pengampu Mata Pelajaran</label>
                <select name="guru_id" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                    <option value="">-- Pilih Guru Pengampu (Opsional) --</option>
                    @foreach ($guruList as $g)
                        <option value="{{ $g->id }}">{{ $g->name }} ({{ $g->nip ?: ($g->nuptk ?: 'Honor') }})</option>
                    @endforeach
                </select>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeModal('modalTambahMapel')">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm">Simpan Mapel</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Mapel -->
<div id="modalEditMapel" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15,23,42,0.6); z-index: 999; align-items: center; justify-content: center;">
    <div style="background: #ffffff; border-radius: var(--radius); width: 95%; max-width: 540px; padding: 24px; max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--primary);">Ubah Mata Pelajaran</h3>
            <button type="button" onclick="closeModal('modalEditMapel')" style="background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
        </div>
        <form id="formEditMapel" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1.6fr; gap: 12px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Kode Mapel *</label>
                    <input type="text" name="kode_mapel" id="edit_kode_mapel" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Nama Lengkap Mapel *</label>
                    <input type="text" name="nama_mapel" id="edit_nama_mapel" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 12px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Kelompok Mapel *</label>
                    <input list="list_kelompok_edit" name="kelompok" id="edit_kelompok" required placeholder="Pilih atau ketik kelompok..." style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                    <datalist id="list_kelompok_edit">
                        <option value="Mata Pelajaran Pilihan (MPP)">
                        <option value="Dasar Program Keahlian (DKK)">
                        <option value="Konsentrasi Keahlian (KK)">
                        <option value="Muatan Umum (A)">
                        <option value="Muatan Kewilayahan (B)">
                        <option value="Muatan Lokal">
                    </datalist>
                    <div style="font-size: 11px; color: #64748b; margin-top: 3px;">
                        Pilih saran dari daftar atau ketik kelompok bebas.
                    </div>
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Tingkat Sasaran *</label>
                    <select name="tingkat" id="edit_tingkat" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                        <option value="Semua">Semua Tingkat (Lintas Kelas)</option>
                        <option value="10">Kelas 10 (Fase E)</option>
                        <option value="11">Kelas 11 (Fase F)</option>
                        <option value="12">Kelas 12 (Fase F)</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Jurusan Sasaran *</label>
                <input list="list_jurusan_edit" name="jurusan" id="edit_jurusan" required placeholder="Pilih atau ketik jurusan sasaran..." style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                <datalist id="list_jurusan_edit">
                    <option value="Semua Jurusan">
                    <option value="Desain Pemodelan dan Informasi Bangunan">
                    <option value="Teknik Instalasi Tenaga Listrik">
                    <option value="Teknik Kendaraan Ringan">
                    <option value="Teknik Otomotif">
                    <option value="Teknik Pengelasan">
                    <option value="Teknik Pengelasan dan Fabrikasi Logam">
                </datalist>
                <div style="font-size: 11px; color: #64748b; margin-top: 3px;">
                    Pilih jurusan sasaran atau ketik nama jurusan kustom bebas.
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Guru Pengampu Mata Pelajaran</label>
                <select name="guru_id" id="edit_guru_id" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                    <option value="">-- Pilih Guru Pengampu --</option>
                    @foreach ($guruList as $g)
                        <option value="{{ $g->id }}">{{ $g->name }} ({{ $g->nip ?: ($g->nuptk ?: 'Honor') }})</option>
                    @endforeach
                </select>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeModal('modalEditMapel')">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Impor XLSX Mapel -->
<div id="modalImportMapel" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15,23,42,0.6); z-index: 999; align-items: center; justify-content: center;">
    <div style="background: #ffffff; border-radius: var(--radius); width: 95%; max-width: 500px; padding: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--primary);">Impor Massal Mata Pelajaran (.xlsx)</h3>
            <button type="button" onclick="closeModal('modalImportMapel')" style="background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
        </div>
        <form action="{{ route('admin.mapel.import_xlsx') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: var(--radius-sm); padding: 12px; margin-bottom: 16px; font-size: 12px; color: #166534;">
                <strong>Panduan Impor:</strong>
                <ol style="margin: 6px 0 0 16px; padding: 0;">
                    <li>Unduh template resmi: <a href="{{ route('admin.mapel.template_xlsx') }}" style="color: #15803d; font-weight: 700; text-decoration: underline;">Unduh Template XLSX</a></li>
                    <li>Isi kode mapel, nama, kelompok, jurusan, tingkat, dan NIP/username guru pengampu.</li>
                    <li>Sistem otomatis menautkan data ke akun guru Dapodik terkait.</li>
                </ol>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 6px;">Pilih Berkas Excel (.xlsx, .xls, .csv) *</label>
                <input type="file" name="file_excel" required accept=".xlsx, .xls, .csv" style="width: 100%; padding: 8px; border: 1px dashed var(--border); border-radius: var(--radius-sm); font-size: 12.5px;">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeModal('modalImportMapel')">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm">Mulai Unggah & Impor</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openModal(id) { document.getElementById(id).style.display = 'flex'; }
    function closeModal(id) { document.getElementById(id).style.display = 'none'; }
    function editMapel(m) {
        document.getElementById('edit_kode_mapel').value = m.kode_mapel || '';
        document.getElementById('edit_nama_mapel').value = m.nama_mapel || '';
        document.getElementById('edit_kelompok').value = m.kelompok || 'Muatan Umum (A)';
        document.getElementById('edit_jurusan').value = m.jurusan || 'Semua Jurusan';
        document.getElementById('edit_tingkat').value = m.tingkat || 'Semua';
        document.getElementById('edit_guru_id').value = m.guru_id || '';
        document.getElementById('formEditMapel').action = '/admin/mapel/' + m.id + '/update';
        openModal('modalEditMapel');
    }
</script>
@endsection
