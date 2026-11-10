@extends('layouts.admin')

@section('title', 'Guru & Pengguna Sistem')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">Guru & Pengguna Sistem</h2>
        <p class="page-subtitle">Kelola akun staf sekolah, tentukan peran hak akses, tugas / jabatan, serta Kepala Sekolah aktif.</p>
    </div>
    <div>
        <button type="button" class="btn btn-primary btn-sm" onclick="openModal('modalTambahUser')">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Tambah Pengguna Baru
        </button>
    </div>
</div>

@if (session('success'))
<div style="background: var(--success-bg); border-left: 4px solid var(--success); padding: 12px 16px; border-radius: var(--radius-sm); margin-bottom: 20px; font-size: 13px; color: #166534;">
    {{ session('success') }}
</div>
@endif

@if (session('error'))
<div style="background: #fee2e2; border-left: 4px solid #ef4444; padding: 12px 16px; border-radius: var(--radius-sm); margin-bottom: 20px; font-size: 13px; color: #991b1b;">
    {{ session('error') }}
</div>
@endif

<!-- INFORMASI KEPALA SEKOLAH AKTIF SISTEM -->
<div style="background: #eff6ff; border: 1px solid #bfdbfe; border-left: 4px solid var(--primary); padding: 11px 16px; border-radius: var(--radius-sm); margin-bottom: 16px; font-size: 13px; color: #1e3a8a; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
    <div>
        <span style="font-weight: 700; color: var(--primary);">⭐ Kepala Sekolah Aktif Saat Ini:</span>
        <strong style="color: #0f172a; margin-left: 4px;">{{ $sekolah->kepala_sekolah ?: '-' }}</strong>
        @if ($sekolah->nip_kepala_sekolah)
            <span style="font-family: monospace; font-size: 12px; margin-left: 6px; color: #475569;">(NIP: {{ $sekolah->nip_kepala_sekolah }})</span>
        @endif
        <span style="font-size: 11.5px; color: #64748b; margin-left: 8px;">&mdash; Tercantum otomatis di Berita Acara, Cetak Kartu, Daftar Hadir, & Laporan Nilai</span>
    </div>
</div>

<!-- FILTER & PENCARIAN GURU / STAF -->
<div class="card" style="margin-bottom: 16px; padding: 12px 16px;">
    <form action="{{ route('admin.users') }}" method="GET" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 220px;">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama guru, NIP, NUPTK, tugas, atau username..." style="width: 100%; padding: 7px 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
        </div>
        <div style="width: 180px;">
            <select name="role" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                <option value="">-- Semua Peran --</option>
                <option value="guru" {{ request('role') === 'guru' ? 'selected' : '' }}>Guru Pengampu</option>
                <option value="proktor" {{ request('role') === 'proktor' ? 'selected' : '' }}>Proktor CBT</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
            </select>
        </div>
        <div>
            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
            @if (request()->hasAny(['q', 'role']))
                <a href="{{ route('admin.users') }}" class="btn btn-secondary btn-sm">Reset</a>
            @endif
        </div>
    </form>
</div>

<div class="table-container">
    <table class="table-compact">
        <thead>
            <tr>
                <th style="width: 35px;">No.</th>
                <th>Nama Lengkap Staf / Guru</th>
                <th>NIP / NUPTK</th>
                <th>Username</th>
                <th>Hak Akses (Role)</th>
                <th>Tugas / Jabatan</th>
                <th>Status</th>
                <th>Login Terakhir</th>
                <th style="text-align: center; width: 140px;">Tindakan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($userList as $idx => $u)
            @php
                $isKepsek = ($sekolah->kepala_sekolah && trim(strtolower($sekolah->kepala_sekolah)) === trim(strtolower($u->name))) || (strtolower(trim($u->tugas ?? '')) === 'kepala sekolah');
            @endphp
            <tr style="{{ $isKepsek ? 'background: #fffdf5;' : '' }}">
                <td>{{ $userList->firstItem() + $idx }}</td>
                <td>
                    <strong style="color: var(--primary);">{{ $u->name }}</strong>
                    @if ($u->email)
                        <div style="font-size: 11px; color: var(--text-muted);">{{ $u->email }}</div>
                    @endif
                </td>
                <td>
                    @if ($u->nip)
                        <span style="font-family: monospace; font-size: 12px; font-weight: 600;">{{ $u->nip }}</span>
                    @elseif ($u->nuptk)
                        <span style="font-family: monospace; font-size: 11.5px; color: #64748b;">NUPTK: {{ $u->nuptk }}</span>
                    @else
                        <span style="color: #94a3b8; font-size: 12px;">-</span>
                    @endif
                </td>
                <td><code>{{ $u->username }}</code></td>
                <td>
                    @if ($u->role === 'admin')
                        <span class="badge badge-info">Administrator</span>
                    @elseif ($u->role === 'proktor')
                        <span class="badge badge-warning">Proktor CBT</span>
                    @elseif ($u->role === 'guru')
                        <span class="badge badge-neutral">Guru Pengampu</span>
                    @else
                        <span class="badge badge-neutral">{{ ucfirst($u->role) }}</span>
                    @endif
                </td>
                <td>
                    @if ($isKepsek)
                        <span class="badge" style="background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="#f59e0b" stroke="#d97706" stroke-width="1.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                            Kepala Sekolah
                        </span>
                    @elseif ($u->tugas)
                        <span class="badge" style="background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; font-weight: 600;">
                            {{ $u->tugas }}
                        </span>
                    @else
                        <span style="color: #94a3b8; font-size: 12px;">-</span>
                    @endif
                </td>
                <td>
                    @if ($u->status === 'aktif')
                        <span class="badge badge-success">Aktif</span>
                    @else
                        <span class="badge badge-danger">Nonaktif</span>
                    @endif
                </td>
                <td>{{ $u->last_login_at ? \Carbon\Carbon::parse($u->last_login_at)->diffForHumans() : 'Belum pernah' }}</td>
                <td style="text-align: center;">
                    <div style="display: flex; gap: 4px; justify-content: center; align-items: center; flex-wrap: wrap;">
                        <button type="button" class="btn btn-secondary btn-sm" style="padding: 3px 8px; font-size: 11.5px;" onclick='editUser(@json($u), {{ $isKepsek ? "true" : "false" }})'>Edit</button>
                        
                        @if (!$isKepsek)
                            <form action="{{ route('admin.users.set_kepsek', $u->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Tetapkan {{ addslashes($u->name) }} sebagai Kepala Sekolah?\n\nNama dan NIP beliau akan otomatis tercantum di seluruh dokumen resmi asesmen (Kartu, Berita Acara, Daftar Hadir, & Laporan Nilai).');">
                                @csrf
                                <button type="submit" class="btn btn-sm" title="Jadikan Kepala Sekolah & perbarui seluruh dokumen asesmen" style="background: #fffbeb; color: #b45309; border: 1px solid #fcd34d; padding: 3px 7px; font-size: 11px; font-weight: 600; cursor: pointer; border-radius: var(--radius-sm);">
                                    ⭐ Set Kepsek
                                </button>
                            </form>
                        @endif

                        @if ($u->id !== auth()->id())
                            <form action="{{ route('admin.users.hapus', $u->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus pengguna {{ addslashes($u->name) }}?');">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm" style="padding: 3px 8px; font-size: 11.5px;">Hapus</button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center; padding: 24px; color: #64748b;">
                    Belum ada data staf / guru ditemukan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 14px;">
    {{ $userList->links() }}
</div>

<!-- PRESET TUGAS / JABATAN (DATALIST UNTUK KEBEBASAN ADMIN) -->
<datalist id="preset_tugas">
    <option value="Kepala Sekolah">
    <option value="Wakil Kepala Sekolah (Kurikulum)">
    <option value="Wakil Kepala Sekolah (Kesiswaan)">
    <option value="Wakil Kepala Sekolah (Sarpras)">
    <option value="Wakil Kepala Sekolah (Humas)">
    <option value="Kepala Lab Komputer">
    <option value="Koordinator Proktor CBT">
    <option value="Teknisi Jaringan & Server">
    <option value="Pengawas Ruang Ujian">
    <option value="Wali Kelas">
    <option value="Guru Mata Pelajaran">
    <option value="Staf Tata Usaha">
    <option value="Ketua Panitia Ujian">
    <option value="Sekretaris Panitia">
    <option value="Bendahara Panitia">
</datalist>

<!-- Modal Tambah User -->
<div id="modalTambahUser" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15,23,42,0.6); z-index: 999; align-items: center; justify-content: center;">
    <div style="background: #ffffff; border-radius: var(--radius); width: 95%; max-width: 520px; padding: 24px; max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--primary);">Tambah Pengguna Baru</h3>
            <button type="button" onclick="closeModal('modalTambahUser')" style="background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
        </div>
        <form action="{{ route('admin.users.simpan') }}" method="POST">
            @csrf
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Nama Lengkap Staf / Guru *</label>
                <input type="text" name="name" required placeholder="Contoh: Maria Therik, S.Pd." style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">NIP</label>
                    <input type="text" name="nip" placeholder="Nomor Induk Pegawai" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">NUPTK</label>
                    <input type="text" name="nuptk" placeholder="Nomor Unik PTK" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Username Login *</label>
                    <input type="text" name="username" required placeholder="Contoh: 198901..." style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Peran / Hak Akses *</label>
                    <select name="role" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                        <option value="guru">Guru Pengampu</option>
                        <option value="proktor">Proktor Ujian</option>
                        <option value="admin">Administrator (Akses Penuh)</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Tugas / Jabatan Tambahan (Bebas)</label>
                <input type="text" name="tugas" list="preset_tugas" placeholder="Ketik bebas atau pilih (contoh: Kepala Sekolah, Wakasek, Teknisi)..." style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                <span style="font-size: 11px; color: var(--text-muted);">Admin bebas menentukan tugas apa saja untuk guru/staf ini.</span>
            </div>

            <!-- OPSI KEPALA SEKOLAH AKTIF -->
            <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: var(--radius-sm); padding: 10px 12px; margin-bottom: 14px;">
                <label style="display: flex; align-items: center; gap: 8px; font-size: 12px; font-weight: 600; color: #92400e; cursor: pointer; margin: 0;">
                    <input type="checkbox" name="is_kepsek" value="1" style="width: 16px; height: 16px;">
                    <span>⭐ Tetapkan sebagai Kepala Sekolah Aktif</span>
                </label>
                <div style="font-size: 11px; color: #b45309; margin-top: 4px; padding-left: 24px;">
                    Jika dicentang, nama dan NIP/NUPTK pengguna ini akan langsung tercantum otomatis di seluruh dokumen resmi asesmen sekolah.
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 12px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Alamat Email</label>
                    <input type="email" name="email" placeholder="staf@smkn1kupangbarat.sch.id" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Password *</label>
                    <input type="text" name="password" required value="guru123" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeModal('modalTambahUser')">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm">Simpan Pengguna</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit User -->
<div id="modalEditUser" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15,23,42,0.6); z-index: 999; align-items: center; justify-content: center;">
    <div style="background: #ffffff; border-radius: var(--radius); width: 95%; max-width: 520px; padding: 24px; max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--primary);">Ubah Data Pengguna</h3>
            <button type="button" onclick="closeModal('modalEditUser')" style="background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
        </div>
        <form id="formEditUser" method="POST">
            @csrf
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Nama Lengkap *</label>
                <input type="text" name="name" id="edit_user_name" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">NIP</label>
                    <input type="text" name="nip" id="edit_user_nip" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">NUPTK</label>
                    <input type="text" name="nuptk" id="edit_user_nuptk" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Username *</label>
                    <input type="text" name="username" id="edit_user_username" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Peran / Hak Akses *</label>
                    <select name="role" id="edit_user_role" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                        <option value="guru">Guru Pengampu</option>
                        <option value="proktor">Proktor Ujian</option>
                        <option value="admin">Administrator (Akses Penuh)</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Tugas / Jabatan Tambahan (Bebas)</label>
                <input type="text" name="tugas" id="edit_user_tugas" list="preset_tugas" placeholder="Ketik bebas atau pilih (contoh: Kepala Sekolah, Wakasek, Wali Kelas)..." style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                <span style="font-size: 11px; color: var(--text-muted);">Admin bebas menentukan tugas apa saja untuk pengguna ini.</span>
            </div>

            <!-- OPSI KEPALA SEKOLAH AKTIF -->
            <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: var(--radius-sm); padding: 10px 12px; margin-bottom: 14px;">
                <label style="display: flex; align-items: center; gap: 8px; font-size: 12px; font-weight: 600; color: #92400e; cursor: pointer; margin: 0;">
                    <input type="checkbox" name="is_kepsek" id="edit_is_kepsek" value="1" style="width: 16px; height: 16px;">
                    <span>⭐ Tetapkan sebagai Kepala Sekolah Aktif</span>
                </label>
                <div style="font-size: 11px; color: #b45309; margin-top: 4px; padding-left: 24px;">
                    Jika dicentang, nama dan NIP/NUPTK pengguna ini akan langsung tercantum otomatis di seluruh dokumen resmi asesmen sekolah.
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 12px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Alamat Email</label>
                    <input type="email" name="email" id="edit_user_email" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Status Akun *</label>
                    <select name="status" id="edit_user_status" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Ganti Password (Kosongkan jika tetap)</label>
                <input type="text" name="password" placeholder="Biarkan kosong jika tidak diubah" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeModal('modalEditUser')">Batal</button>
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
    function editUser(u, isCurrentKepsek) {
        document.getElementById('formEditUser').action = '/admin/users/' + u.id + '/update';
        document.getElementById('edit_user_name').value = u.name;
        document.getElementById('edit_user_username').value = u.username;
        document.getElementById('edit_user_email').value = u.email || '';
        document.getElementById('edit_user_nip').value = u.nip || '';
        document.getElementById('edit_user_nuptk').value = u.nuptk || '';
        document.getElementById('edit_user_role').value = u.role;
        document.getElementById('edit_user_tugas').value = u.tugas || '';
        document.getElementById('edit_user_status').value = u.status;
        document.getElementById('edit_is_kepsek').checked = isCurrentKepsek || (u.tugas && u.tugas.toLowerCase() === 'kepala sekolah');
        openModal('modalEditUser');
    }
</script>
@endsection
