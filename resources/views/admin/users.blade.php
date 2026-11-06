@extends('layouts.admin')

@section('title', 'Guru & Pengguna Sistem')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">Guru & Pengguna Sistem</h2>
        <p class="page-subtitle">Kelola akun staf sekolah: Administrator, Guru Pengampu (Dapodik), dan Proktor Ujian.</p>
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

<!-- FILTER & PENCARIAN GURU / STAF -->
<div class="card" style="margin-bottom: 16px; padding: 12px 16px;">
    <form action="{{ route('admin.users') }}" method="GET" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 220px;">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama guru, NIP, NUPTK, atau username..." style="width: 100%; padding: 7px 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
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
                <th style="width: 40px;">No.</th>
                <th>Nama Lengkap Staf / Guru</th>
                <th>NIP / NUPTK</th>
                <th>Username</th>
                <th>Peran / Role</th>
                <th>Status</th>
                <th>Login Terakhir</th>
                <th style="text-align: center; width: 120px;">Tindakan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($userList as $idx => $u)
            <tr>
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
                    <div style="display: flex; gap: 6px; justify-content: center;">
                        <button type="button" class="btn btn-secondary btn-sm" onclick='editUser(@json($u))'>Edit</button>
                        @if ($u->id !== auth()->id())
                            <form action="{{ route('admin.users.hapus', $u->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus pengguna {{ $u->name }}?');">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 24px; color: #64748b;">
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

<!-- Modal Tambah User -->
<div id="modalTambahUser" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15,23,42,0.6); z-index: 999; align-items: center; justify-content: center;">
    <div style="background: #ffffff; border-radius: var(--radius); width: 95%; max-width: 500px; padding: 24px;">
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
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Username *</label>
                    <input type="text" name="username" required placeholder="Contoh: 198901..." style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Peran / Hak Akses *</label>
                    <select name="role" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                        <option value="guru">Guru Pengampu</option>
                        <option value="proktor">Proktor Ujian</option>
                        <option value="admin">Administrator</option>
                    </select>
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
    <div style="background: #ffffff; border-radius: var(--radius); width: 95%; max-width: 500px; padding: 24px;">
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
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Peran *</label>
                    <select name="role" id="edit_user_role" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                        <option value="guru">Guru Pengampu</option>
                        <option value="proktor">Proktor Ujian</option>
                        <option value="admin">Administrator</option>
                    </select>
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
    function editUser(u) {
        document.getElementById('formEditUser').action = '/admin/users/' + u.id + '/update';
        document.getElementById('edit_user_name').value = u.name;
        document.getElementById('edit_user_username').value = u.username;
        document.getElementById('edit_user_email').value = u.email || '';
        document.getElementById('edit_user_nip').value = u.nip || '';
        document.getElementById('edit_user_nuptk').value = u.nuptk || '';
        document.getElementById('edit_user_role').value = u.role;
        document.getElementById('edit_user_status').value = u.status;
        openModal('modalEditUser');
    }
</script>
@endsection
