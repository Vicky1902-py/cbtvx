@extends('layouts.admin')

@section('title', 'Data Kelas & Rombel')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">Data Kelas & Rombongan Belajar (Rombel)</h2>
        <p class="page-subtitle">Kelola master kelas, tingkat kelas (10, 11, 12), dan kompetensi keahlian / jurusan.</p>
    </div>
    <div>
        <button type="button" class="btn btn-primary btn-sm" onclick="openModal('modalTambahKelas')">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Tambah Kelas Baru
        </button>
    </div>
</div>

<div class="table-container">
    <table class="table-compact">
        <thead>
            <tr>
                <th style="width: 40px;">No.</th>
                <th>Kode Kelas</th>
                <th>Nama Rombel Kelas</th>
                <th>Tingkat</th>
                <th>Jurusan / Kompetensi Keahlian</th>
                <th>Jumlah Siswa</th>
                <th style="text-align: center; width: 120px;">Tindakan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($kelasList as $idx => $k)
            <tr>
                <td>{{ $idx + 1 }}</td>
                <td><code>{{ $k->kode_kelas }}</code></td>
                <td><strong style="color: var(--primary);">{{ $k->nama_kelas }}</strong></td>
                <td><span class="badge badge-neutral">Kelas {{ $k->tingkat }}</span></td>
                <td>{{ $k->jurusan }}</td>
                <td><strong>{{ $k->siswa_count }}</strong> Siswa</td>
                <td style="text-align: center;">
                    <div style="display: flex; gap: 6px; justify-content: center;">
                        <button type="button" class="btn btn-secondary btn-sm" onclick='editKelas(@json($k))'>Edit</button>
                        <form action="{{ route('admin.kelas.hapus', $k->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus kelas {{ $k->nama_kelas }}?');">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 24px; color: #64748b;">
                    Belum ada rombel kelas yang ditambahkan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Tambah Kelas -->
<div id="modalTambahKelas" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15,23,42,0.6); z-index: 999; align-items: center; justify-content: center;">
    <div style="background: #ffffff; border-radius: var(--radius); width: 95%; max-width: 480px; padding: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--primary);">Tambah Rombel Kelas Baru</h3>
            <button type="button" onclick="closeModal('modalTambahKelas')" style="background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
        </div>
        <form action="{{ route('admin.kelas.simpan') }}" method="POST">
            @csrf
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Kode Kelas (Unik) *</label>
                <input type="text" name="kode_kelas" required placeholder="Contoh: XI-MM" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
            </div>
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Nama Kelas / Rombel *</label>
                <input type="text" name="nama_kelas" required placeholder="Contoh: XI MM" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
            </div>
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Tingkat Kelas *</label>
                <select name="tingkat" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                    <option value="10">Kelas 10 (Fase E)</option>
                    <option value="11">Kelas 11 (Fase F)</option>
                    <option value="12">Kelas 12 (Fase F)</option>
                </select>
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Jurusan / Kompetensi Keahlian *</label>
                <input type="text" name="jurusan" required placeholder="Contoh: Multimedia / TKJ / TBSM" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeModal('modalTambahKelas')">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm">Simpan Kelas</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Kelas -->
<div id="modalEditKelas" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15,23,42,0.6); z-index: 999; align-items: center; justify-content: center;">
    <div style="background: #ffffff; border-radius: var(--radius); width: 95%; max-width: 480px; padding: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--primary);">Ubah Data Kelas</h3>
            <button type="button" onclick="closeModal('modalEditKelas')" style="background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
        </div>
        <form id="formEditKelas" method="POST">
            @csrf
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Kode Kelas (Unik) *</label>
                <input type="text" name="kode_kelas" id="edit_kode_kelas" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
            </div>
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Nama Kelas / Rombel *</label>
                <input type="text" name="nama_kelas" id="edit_nama_kelas" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
            </div>
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Tingkat Kelas *</label>
                <select name="tingkat" id="edit_tingkat" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                    <option value="10">Kelas 10</option>
                    <option value="11">Kelas 11</option>
                    <option value="12">Kelas 12</option>
                </select>
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Jurusan / Kompetensi Keahlian *</label>
                <input type="text" name="jurusan" id="edit_jurusan" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeModal('modalEditKelas')">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openModal(id) { document.getElementById(id).style.display = 'flex'; }
    function closeModal(id) { document.getElementById(id).style.display = 'none'; }
    function editKelas(k) {
        document.getElementById('edit_kode_kelas').value = k.kode_kelas;
        document.getElementById('edit_nama_kelas').value = k.nama_kelas;
        document.getElementById('edit_tingkat').value = k.tingkat;
        document.getElementById('edit_jurusan').value = k.jurusan;
        document.getElementById('formEditKelas').action = '/admin/kelas/' + k.id + '/update';
        openModal('modalEditKelas');
    }
</script>
@endsection
