@extends('layouts.admin')

@section('title', 'Isi Berita Acara - ' . $jadwal->nama_ujian)

@section('content')
<div class="page-header">
    <div>
        <div style="font-size: 11px; color: var(--accent); font-weight: 700; text-transform: uppercase;">Administrasi Pelaksanaan Asesmen</div>
        <h2 class="page-title">Form Berita Acara Ujian</h2>
        <p class="page-subtitle">{{ $jadwal->nama_ujian }} &bull; Mata Pelajaran: {{ $jadwal->bankSoal->mataPelajaran->nama_mapel ?? 'Semua Mapel' }}</p>
    </div>
    <div style="display: flex; gap: 8px;">
        <a href="{{ route('admin.jadwal') }}" class="btn btn-secondary btn-sm">
            &larr; Kembali ke Jadwal
        </a>
        <a href="{{ route('admin.cetak_berita_acara', $jadwal->id) }}" target="_blank" class="btn btn-primary btn-sm">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
            Pratinjau / Cetak Berita Acara A4
        </a>
    </div>
</div>

@if (session('success'))
<div style="background: var(--success-bg); border-left: 4px solid var(--success); padding: 12px 16px; border-radius: var(--radius-sm); margin-bottom: 20px; font-size: 13px; color: #166534;">
    {{ session('success') }}
</div>
@endif

<form action="{{ route('admin.berita_acara.simpan', $jadwal->id) }}" method="POST">
    @csrf

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
        <!-- KOLOM KIRI: FORM DATA BERITA ACARA -->
        <div>
            <div class="card" style="margin-bottom: 20px;">
                <h3 style="font-size: 14px; font-weight: 700; color: var(--primary); margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px solid var(--border-light);">
                    1. Rincian Pelaksanaan Asesmen
                </h3>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Nomor Surat Berita Acara *</label>
                        <input type="text" name="nomor_surat" required value="{{ old('nomor_surat', $beritaAcara->nomor_surat ?? ('421.5/024/SMKN1.KB/' . date('Y'))) }}" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Hari / Tanggal Ujian *</label>
                        <input type="text" name="hari_tanggal" required value="{{ old('hari_tanggal', $beritaAcara->hari_tanggal ?? \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y')) }}" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Waktu Mulai</label>
                        <input type="text" name="waktu_mulai" value="{{ old('waktu_mulai', $beritaAcara->waktu_mulai ?? '08:00') }}" placeholder="08:00" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Waktu Selesai</label>
                        <input type="text" name="waktu_selesai" value="{{ old('waktu_selesai', $beritaAcara->waktu_selesai ?? '09:30') }}" placeholder="09:30" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Ruangan Ujian</label>
                        <input type="text" name="ruangan" value="{{ old('ruangan', $beritaAcara->ruangan ?? 'Lab Komputer 1') }}" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Sesi Ujian</label>
                        <input type="text" name="sesi" value="{{ old('sesi', $beritaAcara->sesi ?? 'Sesi 1') }}" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                    </div>
                </div>

                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Catatan Pelaksanaan / Kejadian Khusus</label>
                    <textarea name="catatan_pelaksanaan" rows="3" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">{{ old('catatan_pelaksanaan', $beritaAcara->catatan_pelaksanaan ?? 'Pelaksanaan asesmen CBT berlangsung aman, tertib, dan terkendali didukung sarana komputer dan koneksi LAN sekolah.') }}</textarea>
                </div>
            </div>

            <!-- PENETAPAN PROKTOR DAN PENGAWAS -->
            <div class="card" style="margin-bottom: 20px;">
                <h3 style="font-size: 14px; font-weight: 700; color: var(--primary); margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px solid var(--border-light);">
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
                            <input type="text" id="inputNamaProktor" name="nama_proktor" value="{{ old('nama_proktor', $beritaAcara->nama_proktor ?? 'Izak Robinson Koroh, S.Pd') }}" required style="width: 100%; padding: 6px 8px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 12px; margin-bottom: 8px;">
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
                                <option value="{{ $g->id }}" data-name="{{ $g->name }}" data-nip="{{ $g->nip ?: $g->username }}" {{ (isset($beritaAcara) && $beritaAcara->pengawas_id == $g->id) || ($g->username == '198911042024212044') ? 'selected' : '' }}>
                                    {{ $g->name }} ({{ $g->nip ?: 'NIP: -' }})
                                </option>
                            @endforeach
                        </select>
                        <div>
                            <label style="display: block; font-size: 11px; color: var(--text-muted); margin-bottom: 2px;">Nama Lengkap Pengawas (Tertulis di Dokumen)</label>
                            <input type="text" id="inputNamaPengawas" name="nama_pengawas" value="{{ old('nama_pengawas', $beritaAcara->nama_pengawas ?? 'Potifera Buan, S.Pd') }}" required style="width: 100%; padding: 6px 8px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 12px; margin-bottom: 8px;">
                            <label style="display: block; font-size: 11px; color: var(--text-muted); margin-bottom: 2px;">NIP Pengawas</label>
                            <input type="text" id="inputNipPengawas" name="nip_pengawas" value="{{ old('nip_pengawas', $beritaAcara->nip_pengawas ?? '198911042024212044') }}" style="width: 100%; padding: 6px 8px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 12px;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- KEHADIRAN PESERTA -->
            <div class="card">
                <h3 style="font-size: 14px; font-weight: 700; color: var(--primary); margin-bottom: 16px; padding-bottom: 8px; border-bottom: 1px solid var(--border-light);">
                    3. Rekapitulasi Kehadiran Peserta Ujian
                </h3>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Jumlah Peserta Seharusnya *</label>
                        <input type="number" id="inputSeharusnya" name="jumlah_peserta_seharusnya" required value="{{ old('jumlah_peserta_seharusnya', $beritaAcara->jumlah_peserta_seharusnya ?? $totalPeserta) }}" onchange="recalcTidakHadir()" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Jumlah Peserta Hadir *</label>
                        <input type="number" id="inputHadir" name="jumlah_peserta_hadir" required value="{{ old('jumlah_peserta_hadir', $beritaAcara->jumlah_peserta_hadir ?? $totalPeserta) }}" onchange="recalcTidakHadir()" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Jumlah Tidak Hadir</label>
                        <input type="number" id="inputTidakHadir" name="jumlah_peserta_tidak_hadir" readonly value="{{ old('jumlah_peserta_tidak_hadir', $beritaAcara->jumlah_peserta_tidak_hadir ?? 0) }}" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px; background: #f1f5f9;">
                    </div>
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Daftar Siswa Tidak Hadir (Jika Ada)</label>
                    <input type="text" name="daftar_tidak_hadir" value="{{ old('daftar_tidak_hadir', $beritaAcara->daftar_tidak_hadir ?? '-') }}" placeholder="Contoh: NISN 0103841671 - Antriana Buan (Sakit)" style="width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: PREVIEW PENANGGUNG JAWAB & TOMBOL SIMPAN -->
        <div>
            <div class="card" style="margin-bottom: 20px; background: #f8fafc;">
                <h4 style="font-size: 13px; font-weight: 700; color: var(--primary); margin-bottom: 12px;">Penanggung Jawab Sekolah</h4>
                <div style="font-size: 12px; line-height: 1.6; color: #334155;">
                    <div style="font-weight: 700; color: var(--primary);">{{ $sekolah->nama_sekolah }}</div>
                    <div>NPSN: <strong>{{ $sekolah->npsn }}</strong></div>
                    <div style="margin-top: 8px;">Kepala Sekolah:</div>
                    <div style="font-weight: 700; color: #0f172a;">{{ $sekolah->kepala_sekolah }}</div>
                    <div>NIP: {{ $sekolah->nip_kepala_sekolah }}</div>
                    <div style="margin-top: 10px;">
                        <span class="badge badge-success">TTD Digital Terhubung</span>
                    </div>
                </div>
            </div>

            <div class="card">
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 14px; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 10px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Simpan Berita Acara
                </button>
                <a href="{{ route('admin.cetak_berita_acara', $jadwal->id) }}" target="_blank" class="btn btn-secondary" style="width: 100%; padding: 10px; font-size: 13px; font-weight: 600; text-align: center; text-decoration: none; display: block;">
                    Cetak Lembar Berita Acara A4
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

function recalcTidakHadir() {
    const seharusnya = parseInt(document.getElementById('inputSeharusnya').value) || 0;
    const hadir = parseInt(document.getElementById('inputHadir').value) || 0;
    const tidakHadir = Math.max(0, seharusnya - hadir);
    document.getElementById('inputTidakHadir').value = tidakHadir;
}
</script>
@endsection
