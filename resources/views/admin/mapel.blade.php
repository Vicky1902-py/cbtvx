@extends('layouts.admin')

@section('title', 'Mata Pelajaran')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">Mata Pelajaran Asesmen</h2>
        <p class="page-subtitle">Daftar mata pelajaran untuk pengelompokan bank soal dan jadwal ujian.</p>
    </div>
    <div>
        <button type="button" class="btn btn-primary btn-sm" onclick="openModal('modalTambahMapel')">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Tambah Mata Pelajaran
        </button>
    </div>
</div>

<div class="table-container">
    <table class="table-compact">
        <thead>
            <tr>
                <th style="width: 40px;">No.</th>
                <th>Kode Mapel</th>
                <th>Nama Mata Pelajaran</th>
                <th>Jumlah Paket Bank Soal</th>
                <th style="text-align: center; width: 120px;">Tindakan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($mapelList as $idx => $m)
            <tr>
                <td>{{ $idx + 1 }}</td>
                <td><code>{{ $m->kode_mapel }}</code></td>
                <td><strong style="color: var(--primary);">{{ $m->nama_mapel }}</strong></td>
                <td><span class="badge badge-info">{{ $m->bank_soal_count }} Paket</span></td>
                <td style="text-align: center;">
                    <div style="display: flex; gap: 6px; justify-content: center;">
                        <button type="button" class="btn btn-secondary btn-sm" onclick='editMapel(@json($m))'>Edit</button>
                        <form action="{{ route('admin.mapel.hapus', $m->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus mapel {{ $m->nama_mapel }}?');">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 24px; color: #64748b;">
                    Belum ada data mata pelajaran.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Tambah Mapel -->
<div id="modalTambahMapel" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15,23,42,0.6); z-index: 999; align-items: center; justify-content: center;">
    <div style="background: #ffffff; border-radius: var(--radius); width: 95%; max-width: 460px; padding: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--primary);">Tambah Mata Pelajaran Baru</h3>
            <button type="button" onclick="closeModal('modalTambahMapel')" style="background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
        </div>
        <form action="{{ route('admin.mapel.simpan') }}" method="POST">
            @csrf
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Kode Mapel (Unik) *</label>
                <input type="text" name="kode_mapel" required placeholder="Contoh: TKA-SIMDIG" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Nama Lengkap Mata Pelajaran *</label>
                <input type="text" name="nama_mapel" required placeholder="Contoh: Simulasi Digital dan Literasi TIK" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
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
    <div style="background: #ffffff; border-radius: var(--radius); width: 95%; max-width: 460px; padding: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--primary);">Ubah Mata Pelajaran</h3>
            <button type="button" onclick="closeModal('modalEditMapel')" style="background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
        </div>
        <form id="formEditMapel" method="POST">
            @csrf
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Kode Mapel *</label>
                <input type="text" name="kode_mapel" id="edit_kode_mapel" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Nama Lengkap Mata Pelajaran *</label>
                <input type="text" name="nama_mapel" id="edit_nama_mapel" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeModal('modalEditMapel')">Batal</button>
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
    function editMapel(m) {
        document.getElementById('edit_kode_mapel').value = m.kode_mapel;
        document.getElementById('edit_nama_mapel').value = m.nama_mapel;
        document.getElementById('formEditMapel').action = '/admin/mapel/' + m.id + '/update';
        openModal('modalEditMapel');
    }
</script>
@endsection
