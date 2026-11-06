@extends('layouts.admin')

@section('title', 'Dashboard & Monitoring Realtime')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">Dashboard & Monitoring Ujian Realtime</h2>
        <p class="page-subtitle">Pantau aktivitas peserta ujian, performa server CBT sekolah, dan statistik capaian nilai secara langsung.</p>
    </div>
    <div style="display: flex; gap: 8px;">
        <form action="{{ route('admin.reset_all_logins') }}" method="POST" onsubmit="return confirm('Peringatan: Aksi ini akan mereset seluruh kuncian login peserta yang sedang online/tersangkut. Lanjutkan?');">
            @csrf
            <button type="submit" class="btn btn-secondary btn-sm" style="color: #b91c1c; border-color: #fca5a5;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path><line x1="12" y1="2" x2="12" y2="12"></line></svg>
                Reset Semua Sesi Terkunci
            </button>
        </form>

        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-sm">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 4v6h-6"></path><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path></svg>
            Segarkan Data Realtime
        </a>
    </div>
</div>

<!-- KARTU PEMANTAU SERVER CBT SEKOLAH (RAM, DISK, NETWORK/DATABASE, PHP) -->
<div style="margin-bottom: 24px;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
        <h3 style="font-size: 13.5px; font-weight: 700; color: var(--primary); text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 6px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect><line x1="6" y1="6" x2="6.01" y2="6"></line><line x1="6" y1="18" x2="6.01" y2="18"></line></svg>
            Status & Kapasitas Server CBT (Koneksi LAN / Server Sekolah)
        </h3>
        <span style="font-size: 11px; color: var(--success); font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
            <span style="width: 8px; height: 8px; border-radius: 50%; background: #10b981; display: inline-block;"></span>
            Sistem Berjalan Stabil
        </span>
    </div>

    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px;">
        <!-- RAM Usage Card -->
        <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 14px; position: relative;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                <span style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Penggunaan Memori (RAM)</span>
                <span style="font-size: 11px; font-weight: 600; color: var(--accent);">Limit: {{ $serverStats['ram_limit'] }}</span>
            </div>
            <div style="font-size: 20px; font-weight: 700; color: var(--primary);">{{ $serverStats['ram_used_mb'] }} MB</div>
            <div style="width: 100%; height: 6px; background: #e2e8f0; border-radius: 3px; margin-top: 8px; overflow: hidden;">
                <div style="width: {{ min(100, round(($serverStats['ram_used_mb'] / 512) * 100)) }}%; height: 100%; background: #0077b6;"></div>
            </div>
            <div style="font-size: 10.5px; color: var(--text-muted); margin-top: 6px;">Alokasi aman untuk ujian beban tinggi</div>
        </div>

        <!-- Disk Storage Card -->
        <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 14px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                <span style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Penyimpanan Disk (Storage)</span>
                <span style="font-size: 11px; font-weight: 600; color: #0284c7;">{{ $serverStats['disk_free_gb'] }} GB Bebas</span>
            </div>
            <div style="font-size: 20px; font-weight: 700; color: var(--primary);">{{ $serverStats['disk_percent'] }}% Terpakai</div>
            <div style="width: 100%; height: 6px; background: #e2e8f0; border-radius: 3px; margin-top: 8px; overflow: hidden;">
                <div style="width: {{ $serverStats['disk_percent'] }}%; height: 100%; background: {{ $serverStats['disk_percent'] > 85 ? '#ef4444' : '#10b981' }};"></div>
            </div>
            <div style="font-size: 10.5px; color: var(--text-muted); margin-top: 6px;">Total Kapasitas: {{ $serverStats['disk_total_gb'] }} GB</div>
        </div>

        <!-- Latensi Jaringan / DB Card -->
        <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 14px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                <span style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Respon Database & Network</span>
                <span style="font-size: 11px; font-weight: 600; color: var(--success);">&#x2714; Aktif</span>
            </div>
            <div style="font-size: 20px; font-weight: 700; color: {{ $serverStats['db_latency_ms'] < 100 ? '#15803d' : '#b45309' }};">
                {{ $serverStats['db_latency_ms'] }} <span style="font-size: 14px; font-weight: 600;">ms</span>
            </div>
            <div style="width: 100%; height: 6px; background: #e2e8f0; border-radius: 3px; margin-top: 8px; overflow: hidden;">
                <div style="width: 100%; height: 100%; background: #10b981;"></div>
            </div>
            <div style="font-size: 10.5px; color: var(--text-muted); margin-top: 6px;">{{ $serverStats['db_status'] }}</div>
        </div>

        <!-- Runtime Environment Card -->
        <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 14px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                <span style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Lingkungan Mesin Server</span>
                <span class="badge badge-info" style="font-size: 10px;">PHP {{ $serverStats['php_version'] }}</span>
            </div>
            <div style="font-size: 14px; font-weight: 700; color: var(--primary); margin-top: 2px;">
                CBT Engine v2.0
            </div>
            <div style="font-size: 11px; color: #475569; margin-top: 6px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                {{ $serverStats['server_software'] }}
            </div>
            <div style="font-size: 10.5px; color: var(--text-muted); margin-top: 6px;">Teroptimasi untuk LAN sekolah tanpa lag</div>
        </div>
    </div>
</div>

<!-- AREA GRAFIK DONAT & GRAFIK BATANG DENGAN MOTION -->
<div style="display: grid; grid-template-columns: 1fr 1.6fr; gap: 16px; margin-bottom: 24px;">
    <!-- Grafik Donat (Donut Chart) -->
    <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 18px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <div>
                <h3 style="font-size: 14px; font-weight: 700; color: var(--primary);">Distribusi Status Peserta Ujian</h3>
                <p style="font-size: 11px; color: var(--text-muted);">Progres pengerjaan seluruh peserta hari ini</p>
            </div>
            <span class="badge badge-neutral">Total: {{ $totalSiswa }} Siswa</span>
        </div>

        <div style="height: 220px; position: relative;">
            <canvas id="donutChartPeserta"></canvas>
        </div>

        <div style="display: flex; justify-content: space-around; margin-top: 14px; padding-top: 12px; border-top: 1px solid var(--border-light); font-size: 11.5px;">
            <div style="display: flex; align-items: center; gap: 6px;">
                <span style="width: 10px; height: 10px; border-radius: 2px; background: #0f2b48;"></span>
                <span>Selesai (<strong>{{ $countSelesai }}</strong>)</span>
            </div>
            <div style="display: flex; align-items: center; gap: 6px;">
                <span style="width: 10px; height: 10px; border-radius: 2px; background: #0077b6;"></span>
                <span>Sedang Tes (<strong>{{ $countBerlangsung }}</strong>)</span>
            </div>
            <div style="display: flex; align-items: center; gap: 6px;">
                <span style="width: 10px; height: 10px; border-radius: 2px; background: #94a3b8;"></span>
                <span>Belum Mulai (<strong>{{ $countBelum }}</strong>)</span>
            </div>
        </div>
    </div>

    <!-- Grafik Batang (Bar Chart) -->
    <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 18px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <div>
                <h3 style="font-size: 14px; font-weight: 700; color: var(--primary);">Rata-Rata Nilai Asesmen per Kelas</h3>
                <p style="font-size: 11px; color: var(--text-muted);">Perbandingan nilai rata-rata dan nilai tertinggi rombel kelas</p>
            </div>
            <span class="badge badge-success">Skala Maks: 100 Poin</span>
        </div>

        <div style="height: 220px; position: relative;">
            <canvas id="barChartNilaiKelas"></canvas>
        </div>

        <div style="display: flex; justify-content: center; gap: 24px; margin-top: 14px; padding-top: 12px; border-top: 1px solid var(--border-light); font-size: 11.5px;">
            <div style="display: flex; align-items: center; gap: 6px;">
                <span style="width: 12px; height: 12px; border-radius: 2px; background: #0077b6;"></span>
                <span>Rata-Rata Kelas (Skor KKM: 75.0)</span>
            </div>
            <div style="display: flex; align-items: center; gap: 6px;">
                <span style="width: 12px; height: 12px; border-radius: 2px; background: #38bdf8;"></span>
                <span>Nilai Tertinggi Dicapai</span>
            </div>
        </div>
    </div>
</div>

<!-- TABEL MONITORING REALTIME PESERTA -->
<div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 18px; margin-bottom: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
        <div>
            <h3 style="font-size: 14.5px; font-weight: 700; color: var(--primary);">Daftar Peserta Sedang Mengerjakan / Selesai (Realtime)</h3>
            <p style="font-size: 11.5px; color: var(--text-muted);">Gunakan tombol 'Reset Login' jika komputer siswa mati mendadak atau butuh ganti perangkat.</p>
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
                    <th>Nilai Asesmen</th>
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
                    <td>{{ $p->waktu_mulai ? \Carbon\Carbon::parse($p->waktu_mulai)->format('H:i:s') : '-' }}</td>
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

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Grafik Donat Status Peserta (Motion & Warna Tema SMKN 1 Kupang Barat)
        const ctxDonut = document.getElementById('donutChartPeserta');
        if (ctxDonut) {
            new Chart(ctxDonut, {
                type: 'doughnut',
                data: {
                    labels: ['Selesai Ujian', 'Sedang Mengerjakan', 'Belum Mulai'],
                    datasets: [{
                        data: [{{ $countSelesai }}, {{ $countBerlangsung }}, {{ $countBelum }}],
                        backgroundColor: [
                            '#0f2b48', // Deep Navy SMKN 1
                            '#0077b6', // Cyan Blue
                            '#cbd5e1'  // Neutral Slate
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    animation: {
                        animateScale: true,
                        animateRotate: true,
                        duration: 1200,
                        easing: 'easeOutQuart'
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#07172c',
                            titleFont: { size: 12, family: 'Segoe UI' },
                            bodyFont: { size: 12, family: 'Segoe UI' },
                            padding: 10,
                            cornerRadius: 4
                        }
                    }
                }
            });
        }

        // 2. Grafik Batang Nilai per Rombel Kelas (Motion & Warna Logo)
        const ctxBar = document.getElementById('barChartNilaiKelas');
        if (ctxBar) {
            new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: @json($chartKelasLabels),
                    datasets: [
                        {
                            label: 'Rata-Rata Nilai',
                            data: @json($chartKelasAvg),
                            backgroundColor: 'rgba(0, 119, 182, 0.85)',
                            borderColor: '#0077b6',
                            borderWidth: 1,
                            borderRadius: 4
                        },
                        {
                            label: 'Nilai Tertinggi',
                            data: @json($chartKelasHighest),
                            backgroundColor: 'rgba(56, 189, 248, 0.85)',
                            borderColor: '#38bdf8',
                            borderWidth: 1,
                            borderRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 1400,
                        easing: 'easeOutCubic'
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            grid: { color: '#f1f5f9' },
                            ticks: {
                                font: { size: 11 },
                                stepSize: 20
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11, weight: '600' } }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#07172c',
                            padding: 10,
                            cornerRadius: 4
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
