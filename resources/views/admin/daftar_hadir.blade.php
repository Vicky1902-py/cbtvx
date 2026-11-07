@extends('layouts.admin')

@section('title', 'Daftar Hadir Asesmen Per-Kelas & Sesi')

@section('content')
<div class="page-header">
    <div>
        <div style="font-size: 11px; color: var(--accent); font-weight: 700; text-transform: uppercase;">Administrasi Pelaksanaan Asesmen</div>
        <h2 class="page-title">Daftar Hadir Peserta Asesmen</h2>
        <p class="page-subtitle">Kelola pengisian presensi dan cetak lembar daftar hadir terstandar Pusmendik per-kelas dan per-sesi.</p>
    </div>
</div>

@if (session('success'))
<div style="background: var(--success-bg); border-left: 4px solid var(--success); padding: 12px 16px; border-radius: var(--radius-sm); margin-bottom: 20px; font-size: 13px; color: #166534;">
    {{ session('success') }}
</div>
@endif

<!-- FILTER BAR -->
<div class="card" style="margin-bottom: 20px; padding: 16px 20px;">
    <form method="GET" action="{{ route('admin.daftar_hadir') }}" style="display: grid; grid-template-columns: 2fr 1.5fr 1fr auto; gap: 14px; align-items: flex-end;">
        <div>
            <label style="display: block; font-size: 11.5px; font-weight: 700; color: #475569; margin-bottom: 4px;">Pilih Jadwal Asesmen / Mapel</label>
            <select name="jadwal_id" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;" onchange="this.form.submit()">
                @forelse ($jadwalList as $j)
                    <option value="{{ $j->id }}" {{ $selectedJadwalId == $j->id ? 'selected' : '' }}>
                        {{ $j->nama_ujian }} &bull; {{ $j->bankSoal->mataPelajaran->nama_mapel ?? 'Semua Mapel' }} ({{ $j->kelas->pluck('nama_kelas')->implode(', ') }})
                    </option>
                @empty
                    <option value="">-- Belum ada jadwal ujian --</option>
                @endforelse
            </select>
        </div>

        <div>
            <label style="display: block; font-size: 11.5px; font-weight: 700; color: #475569; margin-bottom: 4px;">Filter Kelas / Rombel</label>
            <select name="kelas_id" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;" onchange="this.form.submit()">
                <option value="">-- Semua Kelas Sasaran --</option>
                @if ($activeJadwal)
                    @foreach ($activeJadwal->kelas as $k)
                        <option value="{{ $k->id }}" {{ $selectedKelasId == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }} ({{ $k->jurusan }})
                        </option>
                    @endforeach
                @endif
            </select>
        </div>

        <div>
            <label style="display: block; font-size: 11.5px; font-weight: 700; color: #475569; margin-bottom: 4px;">Filter Sesi</label>
            <select name="sesi" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;" onchange="this.form.submit()">
                <option value="">-- Semua Sesi --</option>
                <option value="Sesi 1" {{ $selectedSesi === 'Sesi 1' ? 'selected' : '' }}>Sesi 1</option>
                <option value="Sesi 2" {{ $selectedSesi === 'Sesi 2' ? 'selected' : '' }}>Sesi 2</option>
                <option value="Sesi 3" {{ $selectedSesi === 'Sesi 3' ? 'selected' : '' }}>Sesi 3</option>
            </select>
        </div>

        <div>
            <a href="{{ route('admin.daftar_hadir') }}" class="btn btn-secondary btn-sm" style="height: 35px; display: inline-flex; align-items: center;" title="Reset Filter">
                Reset
            </a>
        </div>
    </form>
</div>

<!-- TABEL RINCIAN SESI PER KELAS -->
<div class="card" style="padding: 0; overflow: hidden;">
    <div style="padding: 14px 20px; border-bottom: 1px solid var(--border-light); background: #f8fafc; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3 style="font-size: 14px; font-weight: 700; color: var(--primary);">Daftar Sesi Pelaksanaan Asesmen</h3>
            <p style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">
                @if ($activeJadwal)
                    Jadwal Aktif: <strong>{{ $activeJadwal->nama_ujian }}</strong> &bull; Token: <strong>{{ $activeJadwal->token_ujian }}</strong>
                @else
                    Silakan buat jadwal ujian terlebih dahulu di menu Jadwal Ujian.
                @endif
            </p>
        </div>
    </div>

    <div class="table-container" style="border: none; border-radius: 0;">
        <table class="table-compact">
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;">No.</th>
                    <th>Rombel & Jurusan</th>
                    <th style="width: 90px; text-align: center;">Sesi</th>
                    <th>Ruang & ID Server</th>
                    <th>Proktor & Pengawas</th>
                    <th style="width: 140px; text-align: center;">Kehadiran Peserta</th>
                    <th style="width: 100px; text-align: center;">Status</th>
                    <th style="width: 240px; text-align: center;">Tindakan Administrasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sesiRows as $idx => $row)
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td>
                        <strong style="color: var(--primary); font-size: 13.5px;">{{ $row['kelas']->nama_kelas }}</strong>
                        <div style="font-size: 11px; color: #64748b;">{{ $row['kelas']->jurusan }}</div>
                    </td>
                    <td style="text-align: center;">
                        <span class="badge" style="background: #e0f2fe; color: #0369a1; font-weight: 700;">{{ $row['sesi'] }}</span>
                    </td>
                    <td>
                        <div style="font-weight: 600;">{{ $row['berita_acara']->ruangan ?? 'Lab Komputer 1' }}</div>
                        <div style="font-size: 11px; color: #64748b;">ID Server: {{ $row['berita_acara']->id_server ?? 'K24020010-AS4K' }}</div>
                    </td>
                    <td>
                        <div style="font-size: 12px;"><strong>P:</strong> {{ $row['proktor'] }}</div>
                        <div style="font-size: 12px; color: #475569;"><strong>W:</strong> {{ $row['pengawas'] }}</div>
                    </td>
                    <td style="text-align: center;">
                        <div style="font-weight: 700; color: #047857; font-size: 13px;">
                            {{ $row['hadir'] }} / {{ $row['total_siswa'] }} Siswa
                        </div>
                        @if ($row['tidak_hadir'] > 0)
                            <span class="badge badge-danger" style="font-size: 10.5px;">{{ $row['tidak_hadir'] }} Tidak Hadir</span>
                        @else
                            <span class="badge badge-success" style="font-size: 10.5px;">Lengkap</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        @if ($row['berita_acara'])
                            <span class="badge badge-success">Tersimpan</span>
                        @else
                            <span class="badge badge-warning">Siap Diisi</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 6px; justify-content: center;">
                            <a href="{{ route('admin.daftar_hadir.form', ['id' => $row['jadwal']->id, 'kelas_id' => $row['kelas']->id, 'sesi' => $row['sesi']]) }}" class="btn btn-primary btn-sm" title="Isi Presensi Kehadiran">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                                Isi Presensi
                            </a>
                            <a href="{{ route('admin.cetak_daftar_hadir', ['id' => $row['jadwal']->id, 'kelas_id' => $row['kelas']->id, 'sesi' => $row['sesi']]) }}" target="_blank" class="btn btn-secondary btn-sm" style="border-color: #059669; color: #059669;" title="Cetak Lembar Daftar Hadir Pusmendik A4">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                Cetak DH
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 30px; color: #64748b;">
                        Belum ada data sesi pelaksanaan asesmen. Pastikan jadwal ujian sudah diatur dan memilih rombel kelas sasaran.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
