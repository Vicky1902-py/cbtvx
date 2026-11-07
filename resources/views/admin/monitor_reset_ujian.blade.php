@extends('layouts.admin')

@section('title', 'Monitor & Pusat Reset Ujian Siswa')

@section('content')
<div class="page-header">
    <div>
        <div style="font-size: 11px; color: var(--accent); font-weight: 700; text-transform: uppercase;">Pengawasan & Intervensi Proktor</div>
        <h2 class="page-title">Monitor & Pusat Reset Ujian Siswa</h2>
        <p class="page-subtitle">Pantau status pengerjaan siswa secara langsung, reset sesi login yang terkunci, tambah waktu pengerjaan, atau reset tes untuk mengulang.</p>
    </div>
    <div style="display: flex; gap: 8px; align-items: center;">
        <label style="display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; color: #475569; background: #ffffff; border: 1px solid var(--border); padding: 6px 12px; border-radius: var(--radius-sm); cursor: pointer;" title="Perbarui data monitoring secara otomatis tanpa perlu tekan F5">
            <input type="checkbox" id="toggleAutoRefresh" onchange="toggleRefresh(this.checked)">
            Auto-Refresh (15d)
        </label>
        <form action="{{ route('admin.reset_all_logins') }}" method="POST" onsubmit="return confirm('Reset seluruh sesi siswa yang berstatus login/terkunci?');">
            @csrf
            <button type="submit" class="btn btn-warning btn-sm">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 2v6h-6"></path><path d="M3 12a9 9 0 0 1 15-6.7L21 8"></path><path d="M3 22v-6h6"></path><path d="M21 12a9 9 0 0 1-15 6.7L3 16"></path></svg>
                Reset Semua Sesi Terkunci
            </button>
        </form>
    </div>
</div>

<!-- Filter Panel -->
<div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 16px; margin-bottom: 20px;">
    <form action="{{ route('admin.laporan.reset_ujian') }}" method="GET" style="display: flex; gap: 14px; align-items: flex-end; flex-wrap: wrap;">
        <div style="flex: 2; min-width: 250px;">
            <label style="display: block; font-size: 11.5px; font-weight: 700; color: #475569; margin-bottom: 5px; text-transform: uppercase;">Pilih Jadwal Ujian *</label>
            <select name="jadwal_id" onchange="this.form.submit()" style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px; font-weight: 600;">
                <option value="">-- Pilih Jadwal Asesmen --</option>
                @foreach ($jadwalList as $j)
                    <option value="{{ $j->id }}" {{ $selectedJadwalId == $j->id ? 'selected' : '' }}>
                        {{ $j->nama_ujian }} &bull; {{ $j->bankSoal->mataPelajaran->nama_mapel ?? 'Mapel' }} (Durasi: {{ $j->durasi_menit }} Menit)
                    </option>
                @endforeach
            </select>
        </div>

        <div style="flex: 1; min-width: 180px;">
            <label style="display: block; font-size: 11.5px; font-weight: 700; color: #475569; margin-bottom: 5px; text-transform: uppercase;">Rombel / Kelas</label>
            <select name="kelas_id" onchange="this.form.submit()" style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                <option value="">-- Semua Kelas --</option>
                @foreach ($kelasList as $k)
                    <option value="{{ $k->id }}" {{ $selectedKelasId == $k->id ? 'selected' : '' }}>
                        {{ $k->nama_kelas }} ({{ $k->jurusan }})
                    </option>
                @endforeach
            </select>
        </div>

        <div style="flex: 1; min-width: 160px;">
            <label style="display: block; font-size: 11.5px; font-weight: 700; color: #475569; margin-bottom: 5px; text-transform: uppercase;">Status Tes</label>
            <select name="status" onchange="this.form.submit()" style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                <option value="">-- Semua Status --</option>
                <option value="belum" {{ $statusFilter == 'belum' ? 'selected' : '' }}>Belum Mulai</option>
                <option value="berlangsung" {{ $statusFilter == 'berlangsung' ? 'selected' : '' }}>Sedang Mengerjakan</option>
                <option value="selesai" {{ $statusFilter == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>

        <div>
            <button type="submit" class="btn btn-secondary" style="padding: 8px 14px;">Filter</button>
        </div>
    </form>
</div>

@if ($selectedJadwal)
<!-- Summary Ringkas Status -->
@php
    $cntBelum = $pesertaList->where('status', 'belum')->count();
    $cntSedang = $pesertaList->where('status', 'berlangsung')->count();
    $cntSelesai = $pesertaList->where('status', 'selesai')->count();
@endphp
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; margin-bottom: 20px;">
    <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 14px 18px; border-left: 4px solid var(--primary);">
        <div style="font-size: 11px; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Total Peserta</div>
        <div style="font-size: 22px; font-weight: 800; color: var(--primary); margin-top: 4px;">{{ $pesertaList->count() }} Siswa</div>
    </div>
    <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 14px 18px; border-left: 4px solid #64748b;">
        <div style="font-size: 11px; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Belum Mulai</div>
        <div style="font-size: 22px; font-weight: 800; color: #64748b; margin-top: 4px;">{{ $cntBelum }} Siswa</div>
    </div>
    <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 14px 18px; border-left: 4px solid #d97706;">
        <div style="font-size: 11px; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Sedang Mengerjakan</div>
        <div style="font-size: 22px; font-weight: 800; color: #d97706; margin-top: 4px;">{{ $cntSedang }} Siswa</div>
    </div>
    <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 14px 18px; border-left: 4px solid #16a34a;">
        <div style="font-size: 11px; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Sudah Selesai</div>
        <div style="font-size: 22px; font-weight: 800; color: #16a34a; margin-top: 4px;">{{ $cntSelesai }} Siswa</div>
    </div>
</div>

<!-- Tabel Monitoring Siswa -->
<div class="table-container">
    <table class="table-compact">
        <thead>
            <tr>
                <th style="width: 35px; text-align: center;">No.</th>
                <th>Nomor Peserta</th>
                <th>NISN</th>
                <th>Nama Lengkap Peserta</th>
                <th>Kelas</th>
                <th>Status Ujian</th>
                <th>Sisa Waktu</th>
                <th>Nilai Sementara / Akhir</th>
                <th style="text-align: center; width: 240px;">Aksi Intervensi Proktor</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pesertaList as $idx => $p)
            @php
                $sisaMenit = max(0, round($p->sisa_detik / 60));
            @endphp
            <tr>
                <td style="text-align: center;">{{ $idx + 1 }}</td>
                <td><code>{{ $p->siswa->nomor_peserta ?? '-' }}</code></td>
                <td>{{ $p->siswa->nisn }}</td>
                <td>
                    <strong style="color: var(--primary);">{{ $p->siswa->nama_lengkap ?? '-' }}</strong>
                    <div style="font-size: 11px; color: var(--text-muted);">
                        Sesi: {{ $p->siswa->sesi_ujian }} &bull; Ruang: {{ $p->siswa->ruangan_ujian }}
                    </div>
                </td>
                <td><span class="badge badge-neutral">{{ $p->siswa->kelas->nama_kelas ?? '-' }}</span></td>
                <td>
                    @if ($p->status === 'berlangsung')
                        <span class="badge badge-warning" style="font-weight: 700;">SEDANG MENGERJAKAN</span>
                    @elseif ($p->status === 'selesai')
                        <span class="badge badge-success" style="font-weight: 700;">SELESAI</span>
                    @else
                        <span class="badge badge-neutral">BELUM MULAI</span>
                    @endif

                    @if ($p->jumlah_pelanggaran > 0)
                        <div style="margin-top: 4px;">
                            <span class="badge badge-danger" style="font-weight: 800; font-size: 10px;" title="Terdeteksi meninggalkan jendela ujian atau berpindah tab">
                                ⚠️ {{ $p->jumlah_pelanggaran }}x Pindah Tab
                            </span>
                        </div>
                    @endif
                </td>
                <td>
                    @if ($p->status === 'berlangsung')
                        <strong style="color: {{ $sisaMenit < 10 ? '#dc2626' : '#0284c7' }};">
                            {{ $sisaMenit }} Menit
                        </strong>
                    @else
                        -
                    @endif
                </td>
                <td>
                    <strong>{{ number_format($p->total_nilai, 2) }}</strong>
                    <div style="font-size: 10px; color: #64748b;">(B:{{ $p->jawaban_benar }} | S:{{ $p->jawaban_salah }})</div>
                </td>
                <td style="text-align: center;">
                    <div style="display: flex; gap: 4px; justify-content: center; flex-wrap: wrap;">
                        <!-- Reset Login -->
                        <form action="{{ route('admin.reset_login', $p->siswa_id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Buka kunci login siswa {{ $p->siswa->nama_lengkap }}?');">
                            @csrf
                            <button type="submit" class="btn btn-secondary btn-sm" title="Reset Sesi Login (Unlock)">
                                Buka Kunci
                            </button>
                        </form>

                        <!-- Tambah Waktu +15 Menit -->
                        @if ($p->status === 'berlangsung')
                            <form action="{{ route('admin.reset_ujian.tambah_waktu', $p->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <input type="hidden" name="menit" value="15">
                                <button type="submit" class="btn btn-info btn-sm" title="Tambah Waktu Ujian 15 Menit">
                                    +15 Menit
                                </button>
                            </form>
                        @endif

                        <!-- Selesaikan Paksa -->
                        @if ($p->status === 'berlangsung')
                            <form action="{{ route('admin.reset_ujian.force_finish', $p->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Selesaikan paksa ujian siswa {{ $p->siswa->nama_lengkap }} sekarang?');">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm" title="Selesaikan Ujian Sekarang">
                                    Selesai Paksa
                                </button>
                            </form>
                        @endif

                        <!-- Reset Tes Total (Ulang Dari Awal) -->
                        <form action="{{ route('admin.reset_ujian.reset_tes', $p->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('PERINGATAN: Seluruh jawaban siswa {{ $p->siswa->nama_lengkap }} akan DIHAPUS dan siswa mengulang ujian dari awal. Lanjutkan?');">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm" title="Reset Ujian (Siswa Mengulang)">
                                Ulang Tes
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center; padding: 30px; color: #64748b;">
                    Tidak ada data peserta ujian untuk jadwal atau filter ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@else
<div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 40px; text-align: center; color: #64748b;">
    <p style="font-size: 15px; font-weight: 600;">Silakan pilih Jadwal Ujian di atas untuk memantau peserta.</p>
</div>
@endif

@section('scripts')
<script>
    let refreshTimer = null;
    function toggleRefresh(enabled) {
        localStorage.setItem('cbt_monitor_refresh', enabled ? 'true' : 'false');
        if (enabled) {
            refreshTimer = setInterval(() => location.reload(), 15000);
        } else {
            clearInterval(refreshTimer);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const isRef = localStorage.getItem('cbt_monitor_refresh') === 'true';
        const chk = document.getElementById('toggleAutoRefresh');
        if (chk) {
            chk.checked = isRef;
            if (isRef) {
                refreshTimer = setInterval(() => location.reload(), 15000);
            }
        }
    });
</script>
@endsection
@endsection
