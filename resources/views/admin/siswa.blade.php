@extends('layouts.admin')

@section('title', 'Data Siswa')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">Data Siswa Peserta Asesmen</h2>
        <p class="page-subtitle">Kelola akun, kelas rombel, nomor peserta, dan kuncian status login peserta.</p>
    </div>
    <div style="display: flex; gap: 8px;">
        <a href="{{ route('admin.cetak_kartu') }}" target="_blank" class="btn btn-primary">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
            Cetak Kartu Login A4
        </a>
    </div>
</div>

<div class="table-container">
    <table class="table-compact">
        <thead>
            <tr>
                <th style="width: 40px;">No.</th>
                <th>Nomor Peserta</th>
                <th>NISN / Username</th>
                <th>Nama Lengkap Siswa</th>
                <th>Kelas / Rombel</th>
                <th>L/P</th>
                <th>Sesi & Ruang</th>
                <th>Status Sesi</th>
                <th style="text-align: center;">Tindakan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($siswaList as $idx => $s)
            <tr>
                <td>{{ $siswaList->firstItem() + $idx }}</td>
                <td><code>{{ $s->nomor_peserta ?? '-' }}</code></td>
                <td><strong>{{ $s->nisn }}</strong></td>
                <td>{{ $s->nama_lengkap }}</td>
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
                    <form action="{{ route('admin.reset_login', $s->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-secondary btn-sm" title="Reset status login">
                            Reset Sesi
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center; padding: 24px; color: #64748b;">
                    Belum ada data siswa yang diimpor.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 16px;">
    {{ $siswaList->links() }}
</div>
@endsection
