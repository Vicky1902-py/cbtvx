@extends('layouts.admin')

@section('title', 'Jadwal & Token Ujian')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">Jadwal Ujian & Pengaturan Token</h2>
        <p class="page-subtitle">Atur token asesmen, durasi pengerjaan, rombel kelas peserta, serta administrasi berita acara dan daftar hadir.</p>
    </div>
    <div>
        <button type="button" class="btn btn-primary btn-sm" onclick="openModal('modalTambahJadwal')">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Tambah Jadwal Ujian Baru
        </button>
    </div>
</div>

<div class="table-container">
    <table class="table-compact">
        <thead>
            <tr>
                <th style="width: 35px;">No.</th>
                <th>Nama Ujian / Asesmen</th>
                <th>Paket Bank Soal</th>
                <th>Token Ujian</th>
                <th>Durasi</th>
                <th>Waktu Pelaksanaan</th>
                <th>Sasaran Kelas</th>
                <th>Status</th>
                <th style="text-align: center; width: 220px;">Kontrol Proktor & Administrasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($jadwalList as $idx => $j)
            <tr>
                <td>{{ $idx + 1 }}</td>
                <td>
                    <strong style="font-size: 13.5px; color: var(--text-main);">{{ $j->nama_ujian }}</strong><br>
                    <span style="font-size: 11px; color: #64748b;">Jenis: {{ $j->jenis_ujian }} &bull; Peserta: {{ $j->peserta->count() }} Siswa</span>
                </td>
                <td>{{ $j->bankSoal->mataPelajaran->nama_mapel ?? '-' }}</td>
                <td>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <span style="background: #0f172a; color: #38bdf8; font-family: monospace; font-size: 14px; font-weight: 700; padding: 4px 8px; border-radius: var(--radius-sm); letter-spacing: 1px;">
                            {{ $j->token_ujian }}
                        </span>
                        <form action="{{ route('admin.generate_token', $j->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-secondary btn-sm" title="Putar / Ganti Token Acak">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
                <td><strong>{{ $j->durasi_menit }} Menit</strong></td>
                <td>
                    <span style="font-size: 12px;">{{ $j->waktu_mulai ? \Carbon\Carbon::parse($j->waktu_mulai)->format('d/m/Y H:i') : '-' }}</span><br>
                    <span style="font-size: 11px; color: #64748b;">s.d {{ $j->waktu_selesai ? \Carbon\Carbon::parse($j->waktu_selesai)->format('d/m/Y H:i') : '-' }}</span>
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
                        <span class="badge badge-success">Aktif</span>
                    @else
                        <span class="badge badge-neutral">{{ ucfirst($j->status) }}</span>
                    @endif
                </td>
                <td style="text-align: center;">
                    <div style="display: flex; gap: 4px; flex-wrap: wrap; justify-content: center;">
                        <a href="{{ route('admin.rekap_nilai', $j->id) }}" class="btn btn-secondary btn-sm" title="Lihat Rekap Nilai Ujian">
                            Rekap Nilai
                        </a>
                        <a href="{{ route('admin.berita_acara.form', $j->id) }}" class="btn btn-secondary btn-sm" style="color: var(--accent); font-weight: 600;" title="Isi & Cetak Berita Acara A4">
                            Berita Acara
                        </a>
                        <a href="{{ route('admin.cetak_daftar_hadir', $j->id) }}" target="_blank" class="btn btn-secondary btn-sm" title="Cetak Daftar Hadir A4">
                            Daftar Hadir
                        </a>
                        <form action="{{ route('admin.force_finish', $j->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Paksa selesai seluruh peserta yang sedang berlangsung pada jadwal ini?');">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm" title="Paksa Selesai Semua">
                                Selesai
                            </button>
                        </form>
                        <form action="{{ route('admin.jadwal.hapus', $j->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus jadwal ujian {{ $j->nama_ujian }}?');">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus Jadwal">
                                &times;
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center; padding: 24px; color: #64748b;">
                    Belum ada jadwal ujian. Klik tombol 'Tambah Jadwal Ujian Baru' di atas.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Tambah Jadwal Ujian -->
<div id="modalTambahJadwal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15,23,42,0.6); z-index: 999; align-items: center; justify-content: center;">
    <div style="background: #ffffff; border-radius: var(--radius); width: 95%; max-width: 600px; padding: 24px; max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--primary);">Tambah Jadwal Ujian Baru</h3>
            <button type="button" onclick="closeModal('modalTambahJadwal')" style="background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
        </div>
        <form action="{{ route('admin.jadwal.simpan') }}" method="POST">
            @csrf
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Pilih Paket Bank Soal *</label>
                <select name="bank_soal_id" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                    @foreach ($bankList as $b)
                        <option value="{{ $b->id }}">{{ $b->kode_bank }} - {{ $b->mataPelajaran->nama_mapel ?? '' }} (Tingkat: {{ $b->tingkat }})</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Nama Ujian / Asesmen *</label>
                <input type="text" name="nama_ujian" required placeholder="Contoh: Asesmen Sumatif Akhir Jenjang 2026" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Jenis Ujian *</label>
                    <select name="jenis_ujian" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                        <option value="SIMULASI">Simulasi Ujian / Asesmen</option>
                        <option value="TKA">Standar TKA</option>
                        <option value="ANBK">ANBK Pusmendik</option>
                        <option value="UTS">Penilaian Tengah Semester (UTS)</option>
                        <option value="UAS">Penilaian Akhir Semester (UAS)</option>
                        <option value="UH">Ulangan Harian (UH)</option>
                        <option value="TRYOUT">Try Out Asesmen</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Token Ujian *</label>
                    <input type="text" name="token_ujian" value="SMK01" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px; font-family: monospace; font-weight: bold; text-transform: uppercase;">
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Durasi (Menit) *</label>
                    <input type="number" name="durasi_menit" value="60" required min="5" max="300" style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Waktu Mulai Asesmen *</label>
                    <input type="datetime-local" name="waktu_mulai" value="{{ date('Y-m-d\T07:30') }}" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Waktu Selesai Asesmen *</label>
                    <input type="datetime-local" name="waktu_selesai" value="{{ date('Y-m-d\T23:59', strtotime('+30 days')) }}" required style="width: 100%; padding: 7px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px;">
                </div>
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 6px;">Pilih Rombel Kelas Target *</label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 6px; background: #f8fafc; padding: 10px; border: 1px solid var(--border-light); border-radius: var(--radius-sm);">
                    @foreach ($kelasList as $k)
                        <label style="font-size: 12px; display: flex; align-items: center; gap: 6px; cursor: pointer;">
                            <input type="checkbox" name="kelas_ids[]" value="{{ $k->id }}" checked style="accent-color: var(--accent);">
                            <span>{{ $k->nama_kelas }} ({{ $k->jurusan }})</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div style="display: flex; gap: 16px; margin-bottom: 20px;">
                <label style="font-size: 12px; display: flex; align-items: center; gap: 6px; cursor: pointer;">
                    <input type="checkbox" name="acak_soal" value="1" checked style="accent-color: var(--accent);">
                    <span>Acak Urutan Soal</span>
                </label>
                <label style="font-size: 12px; display: flex; align-items: center; gap: 6px; cursor: pointer;">
                    <input type="checkbox" name="acak_opsi" value="1" checked style="accent-color: var(--accent);">
                    <span>Acak Opsi Pilihan (A-E)</span>
                </label>
                <label style="font-size: 12px; display: flex; align-items: center; gap: 6px; cursor: pointer;">
                    <input type="checkbox" name="tampilkan_nilai" value="1" checked style="accent-color: var(--accent);">
                    <span>Tampilkan Nilai Akhir</span>
                </label>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeModal('modalTambahJadwal')">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm">Aktifkan Jadwal Ujian</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openModal(id) { document.getElementById(id).style.display = 'flex'; }
    function closeModal(id) { document.getElementById(id).style.display = 'none'; }
</script>
@endsection
