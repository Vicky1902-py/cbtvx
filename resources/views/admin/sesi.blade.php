@extends('layouts.admin')

@section('title', 'Pengaturan Sesi & Ruangan Lab Siswa')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">Pengaturan Sesi & Ruangan Lab Siswa</h2>
        <p class="page-subtitle">Distribusi sesi ujian dan alokasi ruang lab komputer siswa secara massal. Seluruh kartu login, daftar hadir, berita acara, dan portal ujian langsung tersinkronisasi otomatis.</p>
    </div>
    <div style="display: flex; gap: 8px;">
        <a href="{{ route('admin.cetak_sesi', request()->query()) }}" target="_blank" class="btn btn-primary btn-sm" style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; font-weight: 700;" title="Cetak Rekap Jadwal & Sesi Ruang Lab Siswa Format A4">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
            Cetak Rekap Sesi A4
        </a>
    </div>
</div>

<!-- KARTU STATISTIK DISTRIBUSI -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 18px 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border-left: 4px solid #0284c7;">
        <div style="font-size: 11.5px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Total Peserta Didik</div>
        <div style="font-size: 26px; font-weight: 800; color: var(--primary); margin-top: 4px;">{{ $totalSiswa }} <span style="font-size: 13px; font-weight: normal; color: #64748b;">Siswa</span></div>
        <div style="font-size: 11px; color: #0284c7; margin-top: 2px;">Terdaftar di database aktif</div>
    </div>

    <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 18px 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border-left: 4px solid #10b981;">
        <div style="font-size: 11.5px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Peserta Sesi 1</div>
        <div style="font-size: 26px; font-weight: 800; color: #047857; margin-top: 4px;">{{ $totalSesi1 }} <span style="font-size: 13px; font-weight: normal; color: #64748b;">Siswa</span></div>
        <div style="font-size: 11px; color: #10b981; margin-top: 2px;">{{ $totalSiswa > 0 ? round(($totalSesi1 / $totalSiswa) * 100, 1) : 0 }}% dari total peserta</div>
    </div>

    <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 18px 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border-left: 4px solid #f59e0b;">
        <div style="font-size: 11.5px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Peserta Sesi 2</div>
        <div style="font-size: 26px; font-weight: 800; color: #b45309; margin-top: 4px;">{{ $totalSesi2 }} <span style="font-size: 13px; font-weight: normal; color: #64748b;">Siswa</span></div>
        <div style="font-size: 11px; color: #f59e0b; margin-top: 2px;">{{ $totalSiswa > 0 ? round(($totalSesi2 / $totalSiswa) * 100, 1) : 0 }}% dari total peserta</div>
    </div>

    <div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 18px 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border-left: 4px solid #6366f1;">
        <div style="font-size: 11.5px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Distribusi Ruangan Lab</div>
        <div style="font-size: 13px; font-weight: 700; color: var(--primary); margin-top: 8px; display: flex; justify-content: space-between;">
            <span>Lab Komputer 1:</span>
            <strong style="color: #4338ca;">{{ $totalLab1 }} Siswa</strong>
        </div>
        <div style="font-size: 13px; font-weight: 700; color: var(--primary); margin-top: 4px; display: flex; justify-content: space-between;">
            <span>Lab Komputer 2:</span>
            <strong style="color: #4338ca;">{{ $totalLab2 }} Siswa</strong>
        </div>
    </div>
</div>

<!-- FILTER & PENCARIAN -->
<div style="background: #ffffff; border: 1px solid var(--border-light); border-radius: var(--radius); padding: 16px 20px; margin-bottom: 20px;">
    <form method="GET" action="{{ route('admin.sesi') }}" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 180px;">
            <select name="kelas_id" onchange="this.form.submit()" style="width: 100%; padding: 7px 12px; border: 1px solid var(--border); border-radius: var(--radius); font-size: 13px;">
                <option value="">-- Semua Kelas / Rombel --</option>
                @foreach ($kelasList as $k)
                    <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                        {{ $k->nama_kelas }} ({{ $k->jurusan }})
                    </option>
                @endforeach
            </select>
        </div>

        <div style="width: 160px;">
            <select name="sesi" onchange="this.form.submit()" style="width: 100%; padding: 7px 12px; border: 1px solid var(--border); border-radius: var(--radius); font-size: 13px;">
                <option value="">-- Semua Sesi --</option>
                <option value="Sesi 1" {{ request('sesi') == 'Sesi 1' ? 'selected' : '' }}>Sesi 1</option>
                <option value="Sesi 2" {{ request('sesi') == 'Sesi 2' ? 'selected' : '' }}>Sesi 2</option>
                <option value="Sesi 3" {{ request('sesi') == 'Sesi 3' ? 'selected' : '' }}>Sesi 3</option>
            </select>
        </div>

        <div style="width: 180px;">
            <select name="ruangan" onchange="this.form.submit()" style="width: 100%; padding: 7px 12px; border: 1px solid var(--border); border-radius: var(--radius); font-size: 13px;">
                <option value="">-- Semua Ruang Lab --</option>
                <option value="Lab Komputer 1" {{ request('ruangan') == 'Lab Komputer 1' ? 'selected' : '' }}>Lab Komputer 1</option>
                <option value="Lab Komputer 2" {{ request('ruangan') == 'Lab Komputer 2' ? 'selected' : '' }}>Lab Komputer 2</option>
                <option value="Lab Komputer 3" {{ request('ruangan') == 'Lab Komputer 3' ? 'selected' : '' }}>Lab Komputer 3</option>
            </select>
        </div>

        <div style="flex: 1.2; min-width: 200px;">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama siswa, NISN, atau no peserta..." style="width: 100%; padding: 7px 12px; border: 1px solid var(--border); border-radius: var(--radius); font-size: 13px;">
        </div>

        <div style="display: flex; gap: 6px;">
            <button type="submit" class="btn btn-primary btn-sm" style="padding: 7px 14px;">
                Filter
            </button>
            @if (request()->hasAny(['kelas_id', 'sesi', 'ruangan', 'q']))
                <a href="{{ route('admin.sesi') }}" class="btn btn-secondary btn-sm" style="padding: 7px 12px;">Reset</a>
            @endif
        </div>
    </form>
</div>

<!-- BILAH AKSI MASSAL (TERAPUNG / MUNCUL KETIKA ADA CHECKBOX TERPILIH) -->
<div id="bulkActionBar" style="display: none; background: #0f2b48; color: #ffffff; padding: 12px 20px; border-radius: var(--radius); margin-bottom: 16px; align-items: center; justify-content: space-between; gap: 14px; box-shadow: 0 4px 12px rgba(15, 43, 72, 0.25);">
    <div style="display: flex; align-items: center; gap: 12px;">
        <span style="background: #38bdf8; color: #0f2b48; font-weight: 800; padding: 3px 10px; border-radius: 20px; font-size: 12px;" id="selectedCountBadge">
            0 Siswa
        </span>
        <span style="font-size: 13px; font-weight: 600;">Siswa telah dicentang untuk pengaturan massal</span>
    </div>

    <div style="display: flex; gap: 10px;">
        <button type="button" onclick="openAturSesiModal()" style="background: #0284c7; color: #ffffff; border: none; padding: 7px 16px; font-size: 13px; font-weight: 700; border-radius: 4px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
            Atur Sesi & Ruang Lab
        </button>

        <button type="button" onclick="openAutoSplitModal()" style="background: #10b981; color: #ffffff; border: none; padding: 7px 16px; font-size: 13px; font-weight: 700; border-radius: 4px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;" title="Bagi siswa terpilih secara 50:50 ke Lab 1 dan Lab 2">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg>
            Bagi Rata ke Lab 1 & 2 (50:50)
        </button>
    </div>
</div>

<!-- TABEL DAFTAR SISWA & SESI -->
<div class="table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 40px; text-align: center;">
                    <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll(this)" style="cursor: pointer; width: 16px; height: 16px;">
                </th>
                <th style="width: 45px; text-align: center;">No</th>
                <th style="width: 120px;">NISN / No Peserta</th>
                <th>Nama Lengkap Siswa</th>
                <th>Kelas / Rombel</th>
                <th>Sesi Ujian</th>
                <th>Ruangan Ujian</th>
                <th style="text-align: center; width: 110px;">Status Login</th>
                <th style="text-align: center; width: 110px;">Aksi Cepat</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($daftarSiswa as $idx => $s)
            <tr id="row-siswa-{{ $s->id }}">
                <td style="text-align: center;">
                    <input type="checkbox" class="siswa-check" value="{{ $s->id }}" onchange="handleCheckboxChange()" style="cursor: pointer; width: 16px; height: 16px;">
                </td>
                <td style="text-align: center; color: #64748b;">{{ $idx + 1 }}</td>
                <td>
                    <strong style="color: var(--primary); font-family: monospace; font-size: 13px;">{{ $s->nisn }}</strong>
                    @if ($s->nomor_peserta)
                        <div style="font-size: 10.5px; color: #64748b; font-family: monospace;">{{ $s->nomor_peserta }}</div>
                    @endif
                </td>
                <td>
                    <strong style="font-size: 13.5px; color: #0f172a;">{{ $s->nama_lengkap }}</strong>
                    <div style="font-size: 11px; color: #64748b;">NIS: {{ $s->nis ?: '-' }} &bull; JK: {{ $s->jenis_kelamin }}</div>
                </td>
                <td>
                    <span style="font-weight: 600; color: #0369a1;">{{ $s->kelas->nama_kelas ?? '-' }}</span>
                    <div style="font-size: 11px; color: #64748b;">{{ $s->kelas->jurusan ?? '' }}</div>
                </td>
                <td>
                    @if ($s->sesi_ujian === 'Sesi 1')
                        <span style="background: #dcfce7; color: #15803d; font-weight: 700; padding: 3px 9px; border-radius: 4px; font-size: 12px; display: inline-block;">
                            {{ $s->sesi_ujian }}
                        </span>
                    @elseif ($s->sesi_ujian === 'Sesi 2')
                        <span style="background: #fef3c7; color: #b45309; font-weight: 700; padding: 3px 9px; border-radius: 4px; font-size: 12px; display: inline-block;">
                            {{ $s->sesi_ujian }}
                        </span>
                    @else
                        <span style="background: #e2e8f0; color: #475569; font-weight: 700; padding: 3px 9px; border-radius: 4px; font-size: 12px; display: inline-block;">
                            {{ $s->sesi_ujian ?: 'Belum Diatur' }}
                        </span>
                    @endif
                </td>
                <td>
                    <span style="font-weight: 600; color: #1e293b; background: #f1f5f9; padding: 3px 8px; border-radius: 4px; font-size: 12px; border: 1px solid #e2e8f0;">
                        {{ $s->ruangan_ujian ?: 'Belum Diatur' }}
                    </span>
                </td>
                <td style="text-align: center;">
                    @if ($s->status_login === 'sedang_tes')
                        <span class="badge badge-warning">Sedang Tes</span>
                    @elseif ($s->status_login === 'selesai')
                        <span class="badge badge-success">Selesai</span>
                    @elseif ($s->status_login === 'online')
                        <span class="badge badge-info">Online</span>
                    @else
                        <span class="badge" style="background: #f1f5f9; color: #64748b;">Offline</span>
                    @endif
                </td>
                <td style="text-align: center;">
                    <button type="button" onclick="quickEditSingle({{ $s->id }}, '{{ addslashes($s->nama_lengkap) }}', '{{ $s->sesi_ujian }}', '{{ $s->ruangan_ujian }}')" class="btn btn-secondary btn-sm" style="padding: 4px 10px; font-size: 11.5px;">
                        Ubah
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center; padding: 36px; color: #64748b;">
                    Tidak ada siswa yang sesuai dengan filter di atas.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- ============================================================== -->
<!-- POP-UP MODAL 1: ATUR SESI & RUANG LAB MASSAL / INDIVIDUAL      -->
<!-- ============================================================== -->
<div id="modalAturSesi" class="modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1050; align-items: center; justify-content: center;">
    <div style="background: #ffffff; border-radius: var(--radius); width: 95%; max-width: 480px; padding: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.25);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid var(--border-light); padding-bottom: 10px;">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--primary);">Atur Sesi & Ruangan Lab</h3>
            <button type="button" onclick="closeModal('modalAturSesi')" style="background: none; border: none; font-size: 22px; cursor: pointer; color: #64748b;">&times;</button>
        </div>

        <form action="{{ route('admin.sesi.update_massal') }}" method="POST" id="formAturSesi">
            @csrf
            <div id="hiddenSiswaIdsContainer"></div>

            <div style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 4px; padding: 10px 14px; margin-bottom: 18px; font-size: 12.5px; color: #0369a1;">
                Pengaturan ini akan diterapkan ke <strong id="modalTargetCountText">0 Siswa</strong> yang telah dipilih.
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 6px;">Pilih Sesi Ujian:</label>
                <select name="sesi_ujian" id="modalSelectSesi" required style="width: 100%; padding: 9px 12px; border: 1px solid var(--border); border-radius: var(--radius); font-size: 13.5px; font-weight: 600;">
                    <option value="Sesi 1">Sesi 1 (Pagi: 07.30 - 09.30)</option>
                    <option value="Sesi 2">Sesi 2 (Siang: 10.00 - 12.00)</option>
                    <option value="Sesi 3">Sesi 3 (Siang/Sore: 13.00 - 15.00)</option>
                    <option value="Sesi Susulan">Sesi Susulan</option>
                </select>
            </div>

            <div style="margin-bottom: 22px;">
                <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 6px;">Pilih Ruangan / Lab Komputer:</label>
                <select name="ruangan_ujian" id="modalSelectRuangan" required style="width: 100%; padding: 9px 12px; border: 1px solid var(--border); border-radius: var(--radius); font-size: 13.5px; font-weight: 600;">
                    <option value="Lab Komputer 1">Lab Komputer 1</option>
                    <option value="Lab Komputer 2">Lab Komputer 2</option>
                    <option value="Lab Komputer 3">Lab Komputer 3</option>
                </select>
                <p style="font-size: 11px; color: #64748b; margin-top: 4px;">Kartu peserta dan daftar hadir otomatis mencantumkan ruangan ini.</p>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeModal('modalAturSesi')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary" style="padding: 8px 20px;">
                    Simpan & Terapkan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- POP-UP MODAL 2: BAGI RATA OTOMATIS (AUTO-SPLIT 50:50)          -->
<!-- ============================================================== -->
<div id="modalAutoSplit" class="modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1050; align-items: center; justify-content: center;">
    <div style="background: #ffffff; border-radius: var(--radius); width: 95%; max-width: 500px; padding: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.25);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid var(--border-light); padding-bottom: 10px;">
            <h3 style="font-size: 16px; font-weight: 700; color: #047857;">Bagi Rata Otomatis (Auto-Split 50:50)</h3>
            <button type="button" onclick="closeModal('modalAutoSplit')" style="background: none; border: none; font-size: 22px; cursor: pointer; color: #64748b;">&times;</button>
        </div>

        <form action="{{ route('admin.sesi.auto_split') }}" method="POST" id="formAutoSplit">
            @csrf
            <div id="hiddenSplitSiswaIdsContainer"></div>

            <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 4px; padding: 10px 14px; margin-bottom: 18px; font-size: 12.5px; color: #065f46;">
                Sistem akan membagi <strong id="modalSplitCountText">0 Siswa</strong> terpilih menjadi 2 kelompok seimbang:
                setengah masuk ke Lab A dan setengahnya lagi ke Lab B.
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 6px;">Tentukan Sesi Ujian:</label>
                <select name="sesi_ujian" required style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: var(--radius); font-size: 13px;">
                    <option value="Sesi 1">Sesi 1 (Pagi)</option>
                    <option value="Sesi 2">Sesi 2 (Siang)</option>
                    <option value="Sesi 3">Sesi 3 (Sore)</option>
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 22px;">
                <div>
                    <label style="display: block; font-weight: 600; font-size: 12.5px; margin-bottom: 5px;">Ruangan Lab A (50% Pertama):</label>
                    <input type="text" name="lab_a" value="Lab Komputer 1" required style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: var(--radius); font-size: 13px;">
                </div>
                <div>
                    <label style="display: block; font-weight: 600; font-size: 12.5px; margin-bottom: 5px;">Ruangan Lab B (50% Kedua):</label>
                    <input type="text" name="lab_b" value="Lab Komputer 2" required style="width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: var(--radius); font-size: 13px;">
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeModal('modalAutoSplit')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary" style="background: #10b981; border-color: #059669; padding: 8px 20px;">
                    Mulai Bagi Rata
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function getSelectedSiswaIds() {
        const checked = document.querySelectorAll('.siswa-check:checked');
        const ids = [];
        checked.forEach(cb => ids.push(cb.value));
        return ids;
    }

    function handleCheckboxChange() {
        const ids = getSelectedSiswaIds();
        const bar = document.getElementById('bulkActionBar');
        const badge = document.getElementById('selectedCountBadge');

        if (ids.length > 0) {
            bar.style.display = 'flex';
            badge.textContent = ids.length + ' Siswa';
        } else {
            bar.style.display = 'none';
        }

        // Update row highlight
        document.querySelectorAll('.siswa-check').forEach(cb => {
            const tr = document.getElementById('row-siswa-' + cb.value);
            if (tr) {
                if (cb.checked) {
                    tr.style.backgroundColor = '#f0f9ff';
                } else {
                    tr.style.backgroundColor = '';
                }
            }
        });
    }

    function toggleSelectAll(masterCb) {
        const checkboxes = document.querySelectorAll('.siswa-check');
        checkboxes.forEach(cb => {
            cb.checked = masterCb.checked;
        });
        handleCheckboxChange();
    }

    function openAturSesiModal() {
        const ids = getSelectedSiswaIds();
        if (ids.length === 0) {
            alert('Silakan pilih minimal 1 siswa terlebih dahulu.');
            return;
        }

        const container = document.getElementById('hiddenSiswaIdsContainer');
        container.innerHTML = '';
        ids.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'siswa_ids[]';
            input.value = id;
            container.appendChild(input);
        });

        document.getElementById('modalTargetCountText').textContent = ids.length + ' Siswa';
        openModal('modalAturSesi');
    }

    function openAutoSplitModal() {
        const ids = getSelectedSiswaIds();
        if (ids.length < 2) {
            alert('Untuk pembagian otomatis 50:50, silakan pilih minimal 2 siswa.');
            return;
        }

        const container = document.getElementById('hiddenSplitSiswaIdsContainer');
        container.innerHTML = '';
        ids.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'siswa_ids[]';
            input.value = id;
            container.appendChild(input);
        });

        document.getElementById('modalSplitCountText').textContent = ids.length + ' Siswa';
        openModal('modalAutoSplit');
    }

    function quickEditSingle(id, nama, sesi, ruangan) {
        const container = document.getElementById('hiddenSiswaIdsContainer');
        container.innerHTML = `<input type="hidden" name="siswa_ids[]" value="${id}">`;

        document.getElementById('modalTargetCountText').textContent = '1 Siswa (' + nama + ')';
        if (sesi) document.getElementById('modalSelectSesi').value = sesi;
        if (ruangan) document.getElementById('modalSelectRuangan').value = ruangan;

        openModal('modalAturSesi');
    }

    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) modal.style.display = 'flex';
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) modal.style.display = 'none';
    }
</script>
@endsection
