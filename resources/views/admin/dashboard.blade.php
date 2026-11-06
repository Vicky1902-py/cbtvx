@extends('layouts.admin')

@section('title', 'Dashboard & Monitoring Realtime')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">Dashboard & Monitoring Ujian Realtime</h2>
        <p class="page-subtitle">Pantau aktivitas peserta ujian yang sedang aktif di ruang laboratorium atau kelas.</p>
    </div>
    <div style="display: flex; gap: 8px;">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 4v6h-6"></path><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path></svg>
            Segarkan Data
        </a>
    </div>
</div>

<!-- Statistik Ringkas Padat -->
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;">
    <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 16px;">
        <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Total Siswa Terdaftar</div>
        <div style="font-size: 26px; font-weight: 700; color: var(--text-main); margin-top: 4px;">{{ $totalSiswa }}</div>
        <div style="font-size: 11px; color: #64748b; margin-top: 2px;">Tersebar di {{ $totalKelas }} rombel kelas</div>
    </div>
    <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 16px;">
        <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Bank Soal Siap Uji</div>
        <div style="font-size: 26px; font-weight: 700; color: var(--accent); margin-top: 4px;">{{ $totalBankSoal }}</div>
        <div style="font-size: 11px; color: #64748b; margin-top: 2px;">Paket 25 & 40 butir berstandar</div>
    </div>
    <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 16px;">
        <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Jadwal / Sesi Asesmen</div>
        <div style="font-size: 26px; font-weight: 700; color: #0284c7; margin-top: 4px;">{{ $totalJadwal }}</div>
        <div style="font-size: 11px; color: #64748b; margin-top: 2px;">Status aktif terjadwal</div>
    </div>
    <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 16px;">
        <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Peserta Aktif / Mengerjakan</div>
        <div style="font-size: 26px; font-weight: 700; color: var(--success); margin-top: 4px;">{{ $pesertaAktif->where('status', 'berlangsung')->count() }}</div>
        <div style="font-size: 11px; color: #64748b; margin-top: 2px;">Terhubung secara real-time</div>
    </div>
</div>

<!-- Tabel Monitoring Realtime -->
<div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 16px; margin-bottom: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
        <div>
            <h3 style="font-size: 15px; font-weight: 700; color: var(--text-main);">Daftar Peserta Sedang Mengerjakan / Selesai</h3>
            <p style="font-size: 12px; color: var(--text-muted);">Gunakan tombol 'Reset Login' jika komputer siswa mati mendadak atau butuh ganti perangkat.</p>
        </div>
    </div>

    <div class="table-container">
        <table class="table-compact">
            <thead>
                <tr>
                    <th style="width: 40px;">No.</th>
                    <th>Nama Peserta</th>
                    <th>NISN / Username</th>
                    <th>Kelas / Rombel</th>
                    <th>Mata Pelajaran & Ujian</th>
                    <th>Waktu Mulai</th>
                    <th>Sisa Waktu</th>
                    <th>Status</th>
                    <th>Nilai Sementara</th>
                    <th style="text-align: center;">Tindakan Proktor</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pesertaAktif as $idx => $p)
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td>
                        <strong style="color: var(--text-main);">{{ $p->siswa->nama_lengkap ?? '-' }}</strong><br>
                        <span style="font-size: 11px; color: #64748b;">Ruang: {{ $p->siswa->ruangan_ujian ?? '-' }} &bull; {{ $p->siswa->sesi_ujian ?? '-' }}</span>
                    </td>
                    <td><code>{{ $p->siswa->nisn ?? '-' }}</code></td>
                    <td><span class="badge badge-neutral">{{ $p->siswa->kelas->nama_kelas ?? '-' }}</span></td>
                    <td>
                        <strong>{{ $p->jadwalUjian->bankSoal->mataPelajaran->nama_mapel ?? '-' }}</strong><br>
                        <span style="font-size: 11px; color: #64748b;">{{ $p->jadwalUjian->nama_ujian ?? '-' }}</span>
                    </td>
                    <td>{{ $p->waktu_mulai ? $p->waktu_mulai->format('H:i:s') : '-' }}</td>
                    <td>
                        @if ($p->status === 'berlangsung')
                            <strong style="color: #0369a1;">{{ ceil($p->sisa_detik / 60) }} Menit</strong>
                        @else
                            <span style="color: #64748b;">Selesai</span>
                        @endif
                    </td>
                    <td>
                        @if ($p->status === 'berlangsung')
                            <span class="badge badge-warning">Sedang Mengerjakan</span>
                        @elseif ($p->status === 'selesai')
                            <span class="badge badge-success">Selesai Ujian</span>
                        @else
                            <span class="badge badge-neutral">{{ ucfirst($p->status) }}</span>
                        @endif
                    </td>
                    <td>
                        <strong style="font-size: 14px; color: {{ $p->total_nilai >= 75 ? 'var(--success)' : 'var(--danger)' }}">
                            {{ number_format($p->total_nilai, 1) }}
                        </strong>
                        <span style="font-size: 11px; color: #64748b;">/ 100</span>
                    </td>
                    <td style="text-align: center;">
                        <form action="{{ route('admin.reset_login', $p->siswa_id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin mereset login siswa {{ $p->siswa->nama_lengkap }}?');">
                            @csrf
                            <button type="submit" class="btn btn-secondary btn-sm" title="Buka kuncian login siswa">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 2v6h-6"></path><path d="M3 12a9 9 0 0 1 15-6.7L21 8"></path><path d="M3 22v-6h6"></path><path d="M21 12a9 9 0 0 1-15 6.7L3 16"></path></svg>
                                Reset Login
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="text-align: center; padding: 32px; color: #64748b;">
                        Belum ada peserta yang aktif mengerjakan ujian saat ini. Peserta yang masuk akan muncul secara otomatis di tabel ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
