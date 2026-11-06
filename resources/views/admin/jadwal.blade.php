@extends('layouts.admin')

@section('title', 'Jadwal & Token Ujian')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">Jadwal Ujian & Pengaturan Token</h2>
        <p class="page-subtitle">Atur token asesmen, alokasi waktu, serta rombel kelas yang berhak mengikuti asesmen.</p>
    </div>
</div>

<div class="table-container">
    <table class="table-compact">
        <thead>
            <tr>
                <th style="width: 40px;">No.</th>
                <th>Nama Ujian / Asesmen</th>
                <th>Paket Bank Soal</th>
                <th>Token Ujian</th>
                <th>Durasi</th>
                <th>Waktu Pelaksanaan</th>
                <th>Sasaran Kelas</th>
                <th>Status</th>
                <th style="text-align: center;">Berita Acara</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($jadwalList as $idx => $j)
            <tr>
                <td>{{ $idx + 1 }}</td>
                <td>
                    <strong>{{ $j->nama_ujian }}</strong><br>
                    <span style="font-size: 11px; color: #64748b;">Jenis: {{ $j->jenis_ujian }}</span>
                </td>
                <td>{{ $j->bankSoal->mataPelajaran->nama_mapel ?? '-' }}</td>
                <td>
                    <span style="background: #0f172a; color: #38bdf8; font-family: monospace; font-size: 14px; font-weight: 700; padding: 4px 10px; border-radius: var(--radius); letter-spacing: 1px;">
                        {{ $j->token_ujian }}
                    </span>
                </td>
                <td><strong>{{ $j->durasi_menit }} Menit</strong></td>
                <td>
                    <span style="font-size: 12px;">{{ $j->waktu_mulai ? $j->waktu_mulai->format('d/m/Y H:i') : '-' }}</span><br>
                    <span style="font-size: 11px; color: #64748b;">s.d {{ $j->waktu_selesai ? $j->waktu_selesai->format('d/m/Y H:i') : '-' }}</span>
                </td>
                <td>
                    @if ($j->kelas->count() > 0)
                        @foreach ($j->kelas as $k)
                            <span class="badge badge-neutral" style="margin-bottom: 2px;">{{ $k->nama_kelas }}</span>
                        @endforeach
                    @else
                        <span class="badge badge-info">Semua Kelas</span>
                    @endif
                </td>
                <td>
                    @if ($j->status === 'aktif')
                        <span class="badge badge-success">Aktif / Berlangsung</span>
                    @else
                        <span class="badge badge-neutral">{{ ucfirst($j->status) }}</span>
                    @endif
                </td>
                <td style="text-align: center;">
                    <a href="{{ route('admin.cetak_berita_acara', $j->id) }}" target="_blank" class="btn btn-secondary btn-sm">
                        Cetak Berita Acara
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center; padding: 24px; color: #64748b;">
                    Belum ada jadwal ujian.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
