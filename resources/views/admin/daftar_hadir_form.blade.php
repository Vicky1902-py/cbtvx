@extends('layouts.admin')

@section('title', 'Isi Presensi Daftar Hadir - ' . ($kelas->nama_kelas ?? 'Kelas') . ' ' . $sesiFormatted)

@section('content')
<div class="page-header">
    <div>
        <div style="font-size: 11px; color: var(--accent); font-weight: 700; text-transform: uppercase;">Administrasi Pelaksanaan Asesmen</div>
        <h2 class="page-title">Form Presensi Daftar Hadir Ujian</h2>
        <p class="page-subtitle">
            {{ $jadwal->nama_ujian }} &bull; 
            Rombel: <strong>{{ $kelas->nama_kelas ?? 'Semua Kelas' }}</strong> &bull; 
            Sesi: <strong>{{ $sesiFormatted }}</strong>
        </p>
    </div>
    <div style="display: flex; gap: 8px;">
        <a href="{{ route('admin.daftar_hadir', ['jadwal_id' => $jadwal->id, 'kelas_id' => $kelas->id ?? '', 'sesi' => $sesiFormatted]) }}" class="btn btn-secondary btn-sm">
            &larr; Kembali ke Daftar Hadir
        </a>
        <a href="{{ route('admin.cetak_daftar_hadir', ['id' => $jadwal->id, 'kelas_id' => $kelas->id ?? '', 'sesi' => $sesiFormatted]) }}" target="_blank" class="btn btn-primary btn-sm">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
            Cetak Format Pusmendik A4
        </a>
    </div>
</div>

@if (session('success'))
<div style="background: var(--success-bg); border-left: 4px solid var(--success); padding: 12px 16px; border-radius: var(--radius-sm); margin-bottom: 20px; font-size: 13px; color: #166534;">
    {{ session('success') }}
</div>
@endif

<form action="{{ route('admin.daftar_hadir.simpan', $jadwal->id) }}" method="POST">
    @csrf
    <input type="hidden" name="kelas_id" value="{{ $kelas->id ?? '' }}">
    <input type="hidden" name="sesi" value="{{ $sesiFormatted }}">

    <div style="display: grid; grid-template-columns: 2.2fr 1fr; gap: 20px;">
        <!-- KOLOM KIRI -->
        <div>
            <!-- RINCIAN SESI & SERVER -->
            <div class="card" style="margin-bottom: 20px;">
                <h3 style="font-size: 14px; font-weight: 700; color: var(--primary); margin-bottom: 14px; padding-bottom: 8px; border-bottom: 1px solid var(--border-light);">
                    1. Rincian Sesi, Server & Ruangan
                </h3>

                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Nama / Jenis Asesmen (Tertulis pada Judul KOP Dokumen) *</label>
                    <input type="text" name="nama_tes" required value="{{ old('nama_tes', $beritaAcara->nama_tes ?? $jadwal->nama_ujian) }}" placeholder="Contoh: SIMULASI UJIAN CBT atau TES KEMAMPUAN AKADEMIK SMK" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px; font-weight: 600;">
                    <div style="font-size: 11px; color: #64748b; margin-top: 3px;">
                        Teks ini akan otomatis dicetak pada baris kedua judul KOP lembar daftar hadir (berubah dinamis sesuai jenis tes yang dipilih).
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Hari / Tanggal Ujian *</label>
                        <input type="text" name="hari_tanggal" required value="{{ old('hari_tanggal', $beritaAcara->hari_tanggal ?? \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y')) }}" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Alokasi Waktu (Pukul Mulai - Selesai)</label>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <input type="text" name="waktu_mulai" value="{{ old('waktu_mulai', $beritaAcara->waktu_mulai ?? '08:00') }}" placeholder="08:00" style="width: 50%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                            <span>s/d</span>
                            <input type="text" name="waktu_selesai" value="{{ old('waktu_selesai', $beritaAcara->waktu_selesai ?? '09:45') }}" placeholder="09:45" style="width: 50%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                        </div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1.5fr 1fr 1fr 1fr; gap: 12px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">ID Server CBT *</label>
                        <input type="text" name="id_server" value="{{ old('id_server', $beritaAcara->id_server ?? 'K24020010-AS4K') }}" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Ruangan Ujian</label>
                        <input type="text" name="ruangan" value="{{ old('ruangan', $beritaAcara->ruangan ?? 'TKA 1') }}" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Kode Kab.</label>
                        <input type="text" name="kode_kabupaten" value="{{ old('kode_kabupaten', $beritaAcara->kode_kabupaten ?? '02') }}" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Kode Sekolah</label>
                        <input type="text" name="kode_sekolah" value="{{ old('kode_sekolah', $beritaAcara->kode_sekolah ?? '0010') }}" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                    </div>
                </div>
            </div>

            <!-- PENETAPAN PROKTOR DAN PENGAWAS -->
            <div class="card" style="margin-bottom: 20px;">
                <h3 style="font-size: 14px; font-weight: 700; color: var(--primary); margin-bottom: 14px; padding-bottom: 8px; border-bottom: 1px solid var(--border-light);">
                    2. Penetapan Proktor & Pengawas Ruang
                </h3>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <!-- PROKTOR -->
                    <div style="background: #f8fafc; padding: 14px; border-radius: var(--radius-sm); border: 1px solid var(--border-light);">
                        <label style="display: block; font-size: 12px; font-weight: 700; color: var(--primary); margin-bottom: 6px;">Pilih Proktor CBT *</label>
                        <select id="selectProktor" name="proktor_id" onchange="autoFillProktor()" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px; margin-bottom: 10px;">
                            <option value="">-- Pilih dari Daftar Staf / Guru --</option>
                            @foreach ($guruList as $g)
                                <option value="{{ $g->id }}" data-name="{{ $g->name }}" data-nip="{{ $g->nip ?: $g->username }}" {{ (isset($beritaAcara) && $beritaAcara->proktor_id == $g->id) || ($g->username == '199002192020121003') ? 'selected' : '' }}>
                                    {{ $g->name }} ({{ $g->nip ?: 'NIP: -' }})
                                </option>
                            @endforeach
                        </select>
                        <div>
                            <label style="display: block; font-size: 11px; color: var(--text-muted); margin-bottom: 2px;">Nama Lengkap Proktor (Tertulis di Dokumen)</label>
                            <input type="text" id="inputNamaProktor" name="nama_proktor" value="{{ old('nama_proktor', $beritaAcara->nama_proktor ?? 'Izak Robinson Koroh, S.Pd.,Gr') }}" required style="width: 100%; padding: 6px 8px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 12px; margin-bottom: 8px;">
                            <label style="display: block; font-size: 11px; color: var(--text-muted); margin-bottom: 2px;">NIP Proktor</label>
                            <input type="text" id="inputNipProktor" name="nip_proktor" value="{{ old('nip_proktor', $beritaAcara->nip_proktor ?? '199002192020121003') }}" style="width: 100%; padding: 6px 8px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 12px;">
                        </div>
                    </div>

                    <!-- PENGAWAS -->
                    <div style="background: #f8fafc; padding: 14px; border-radius: var(--radius-sm); border: 1px solid var(--border-light);">
                        <label style="display: block; font-size: 12px; font-weight: 700; color: var(--primary); margin-bottom: 6px;">Pilih Pengawas Ruang *</label>
                        <select id="selectPengawas" name="pengawas_id" onchange="autoFillPengawas()" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px; margin-bottom: 10px;">
                            <option value="">-- Pilih dari Daftar Guru Dapodik --</option>
                            @foreach ($guruList as $g)
                                <option value="{{ $g->id }}" data-name="{{ $g->name }}" data-nip="{{ $g->nip ?: $g->username }}" {{ (isset($beritaAcara) && $beritaAcara->pengawas_id == $g->id) || ($g->username == '8843778679130052' || $g->name == 'Mike Amin Imanuel Modok') ? 'selected' : '' }}>
                                    {{ $g->name }} ({{ $g->nip ?: 'NIP: -' }})
                                </option>
                            @endforeach
                        </select>
                        <div>
                            <label style="display: block; font-size: 11px; color: var(--text-muted); margin-bottom: 2px;">Nama Lengkap Pengawas (Tertulis di Dokumen)</label>
                            <input type="text" id="inputNamaPengawas" name="nama_pengawas" value="{{ old('nama_pengawas', $beritaAcara->nama_pengawas ?? 'Mike Amin Imanuel Modok') }}" required style="width: 100%; padding: 6px 8px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 12px; margin-bottom: 8px;">
                            <label style="display: block; font-size: 11px; color: var(--text-muted); margin-bottom: 2px;">NIP Pengawas</label>
                            <input type="text" id="inputNipPengawas" name="nip_pengawas" value="{{ old('nip_pengawas', $beritaAcara->nip_pengawas ?? '8843778679130052') }}" style="width: 100%; padding: 6px 8px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 12px;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABEL PRESENSI SISWA -->
            <div class="card" style="padding: 0; overflow: hidden; margin-bottom: 20px;">
                <div style="padding: 12px 18px; background: #f8fafc; border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="font-size: 14px; font-weight: 700; color: var(--primary);">
                        3. Presensi Peserta Ujian ({{ $siswaList->count() }} Siswa)
                    </h3>
                    <div style="display: flex; gap: 8px;">
                        <button type="button" class="btn btn-secondary btn-sm" onclick="markAll('hadir')">
                            ✓ Hadir Semua
                        </button>
                    </div>
                </div>

                <div class="table-container" style="border: none; border-radius: 0;">
                    <table class="table-compact">
                        <thead>
                            <tr style="background: #f1f5f9;">
                                <th style="width: 35px; text-align: center;">No.</th>
                                <th style="width: 140px;">Username</th>
                                <th>Nama Peserta</th>
                                <th style="width: 150px; text-align: center;">Status Kehadiran</th>
                                <th style="width: 160px;">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $presensi = $beritaAcara->presensi_data ?? [];
                            @endphp
                            @forelse ($siswaList as $idx => $s)
                            @php
                                $status = $presensi[$s->id]['status'] ?? 'hadir';
                                $ket = $presensi[$s->id]['keterangan'] ?? '';
                                $usr = $s->user->username ?? $s->nomor_peserta ?? $s->nisn;
                            @endphp
                            <tr>
                                <td style="text-align: center;">{{ $idx + 1 }}</td>
                                <td>
                                    <strong style="font-family: monospace; color: #0284c7;">{{ $usr }}</strong>
                                </td>
                                <td>
                                    <strong>{{ strtoupper($s->nama_lengkap) }}</strong>
                                    <div style="font-size: 10.5px; color: #64748b;">NISN: {{ $s->nisn }} &bull; {{ $s->kelas->nama_kelas ?? '-' }}</div>
                                </td>
                                <td style="text-align: center;">
                                    <div style="display: inline-flex; gap: 10px; font-size: 12px; font-weight: 600;">
                                        <label style="cursor: pointer; color: #166534;">
                                            <input type="radio" name="kehadiran[{{ $s->id }}]" value="hadir" class="radio-kehadiran" {{ $status === 'hadir' ? 'checked' : '' }} onchange="updateSummaryCounts()"> Hadir
                                        </label>
                                        <label style="cursor: pointer; color: #991b1b;">
                                            <input type="radio" name="kehadiran[{{ $s->id }}]" value="tidak_hadir" class="radio-kehadiran" {{ $status !== 'hadir' ? 'checked' : '' }} onchange="updateSummaryCounts()"> Absen
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <input type="text" name="keterangan_siswa[{{ $s->id }}]" value="{{ $ket }}" placeholder="Contoh: Sakit / Izin" style="width: 100%; padding: 4px 8px; border: 1px solid var(--border); border-radius: 4px; font-size: 11.5px;">
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 25px; color: #64748b;">
                                    Tidak ada data siswa pada rombel atau sesi ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- CATATAN PELAKSANAAN -->
            <div class="card">
                <label style="display: block; font-size: 12px; font-weight: 700; color: var(--primary); margin-bottom: 6px;">Catatan Pelaksanaan / Kejadian Khusus Selama Tes</label>
                <textarea name="catatan_pelaksanaan" rows="3" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">{{ old('catatan_pelaksanaan', $beritaAcara->catatan_pelaksanaan ?? 'UJIAN BERJALAN AMAN DAN LANCAR') }}</textarea>
            </div>
        </div>

        <!-- KOLOM KANAN: REKAPITULASI & SIMPAN -->
        <div>
            <div class="card" style="margin-bottom: 20px; background: #f8fafc;">
                <h4 style="font-size: 13px; font-weight: 700; color: var(--primary); margin-bottom: 12px; padding-bottom: 6px; border-bottom: 1px solid var(--border-light);">
                    Rekapitulasi Kehadiran
                </h4>

                <div style="display: flex; flex-direction: column; gap: 10px; font-size: 13px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: #475569;">Total Seharusnya:</span>
                        <strong id="statSeharusnya" style="font-size: 15px;">{{ $siswaList->count() }} Siswa</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: #166534;">Jumlah Hadir:</span>
                        <strong id="statHadir" style="color: #166534; font-size: 15px;">{{ $beritaAcara->jumlah_peserta_hadir ?? $siswaList->count() }} Siswa</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: #991b1b;">Tidak Hadir:</span>
                        <strong id="statTidakHadir" style="color: #991b1b; font-size: 15px;">{{ $beritaAcara->jumlah_peserta_tidak_hadir ?? 0 }} Siswa</strong>
                    </div>
                </div>
            </div>

            <div class="card" style="margin-bottom: 20px;">
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 14px; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 10px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Simpan Presensi Daftar Hadir
                </button>
                <a href="{{ route('admin.cetak_daftar_hadir', ['id' => $jadwal->id, 'kelas_id' => $kelas->id ?? '', 'sesi' => $sesiFormatted]) }}" target="_blank" class="btn btn-secondary" style="width: 100%; padding: 10px; font-size: 13px; font-weight: 600; text-align: center; text-decoration: none; display: block; border-color: #059669; color: #059669;">
                    Cetak Lembar Daftar Hadir A4
                </a>
            </div>
        </div>
    </div>
</form>

<script>
function autoFillProktor() {
    const sel = document.getElementById('selectProktor');
    const opt = sel.options[sel.selectedIndex];
    if (opt && opt.dataset.name) {
        document.getElementById('inputNamaProktor').value = opt.dataset.name;
        document.getElementById('inputNipProktor').value = opt.dataset.nip || '';
    }
}

function autoFillPengawas() {
    const sel = document.getElementById('selectPengawas');
    const opt = sel.options[sel.selectedIndex];
    if (opt && opt.dataset.name) {
        document.getElementById('inputNamaPengawas').value = opt.dataset.name;
        document.getElementById('inputNipPengawas').value = opt.dataset.nip || '';
    }
}

function markAll(status) {
    const radios = document.querySelectorAll('.radio-kehadiran');
    radios.forEach(r => {
        if (r.value === status) {
            r.checked = true;
        }
    });
    updateSummaryCounts();
}

function updateSummaryCounts() {
    const radios = document.querySelectorAll('.radio-kehadiran:checked');
    let hadir = 0;
    let tidakHadir = 0;
    radios.forEach(r => {
        if (r.value === 'hadir') hadir++;
        else tidakHadir++;
    });
    const total = hadir + tidakHadir;
    document.getElementById('statSeharusnya').innerText = total + ' Siswa';
    document.getElementById('statHadir').innerText = hadir + ' Siswa';
    document.getElementById('statTidakHadir').innerText = tidakHadir + ' Siswa';
}

document.addEventListener('DOMContentLoaded', updateSummaryCounts);
</script>
@endsection
