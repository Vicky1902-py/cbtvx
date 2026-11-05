<!-- Content Header (Page header) -->
<section class="content-header" style="margin-bottom: 10px; padding: 15px 15px 5px 15px;">
	<h1 style="font-size: clamp(18px, 3vw, 22px); font-weight: 800; color: #001233;">
		Dashboard Utama
		<small style="color: #64748b; font-size: 13px;">Pusat Kendali Ujian Online SMK Negeri 1 Kupang Barat</small>
	</h1>
	<ol class="breadcrumb" style="background: transparent; padding: 0; margin-top: 5px;">
		<li><a href="<?php echo site_url(); ?>/manager" style="color: #0077b6;"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Dashboard</li>
	</ol>
</section>

<!-- Main content -->
<section class="content" style="padding: 10px 15px;">
    <!-- Hero Banner SMKN 1 Kupang Barat - Vicky Koroh -->
    <div class="row">
        <div class="col-xs-12">
            <div style="background: linear-gradient(135deg, #03045e 0%, #0077b6 60%, #00b4d8 100%); border-radius: 20px; padding: clamp(20px, 4vw, 32px); color: #ffffff; box-shadow: 0 12px 35px -5px rgba(0, 119, 182, 0.4); margin-bottom: 25px; position: relative; overflow: hidden; border: 1.5px solid rgba(0, 180, 216, 0.35);">
                <!-- Decorative Ambient Glow -->
                <div style="position: absolute; right: -40px; top: -40px; width: 220px; height: 220px; background: radial-gradient(circle, rgba(0, 180, 216, 0.3) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>
                <div style="position: absolute; right: 120px; bottom: -60px; width: 180px; height: 180px; background: radial-gradient(circle, rgba(144, 224, 239, 0.25) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>

                <div class="row" style="position: relative; z-index: 2;">
                    <div class="col-md-8 col-xs-12">
                        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 14px; flex-wrap: wrap;">
                            <img src="<?php echo base_url(); ?>public/images/logo_smk.png" alt="Logo SMKN 1 Kupang Barat" 
                                 style="width: 60px; height: 60px; object-fit: contain; filter: drop-shadow(0 4px 12px rgba(0, 180, 216, 0.5)); flex-shrink: 0;" />
                            <div>
                                <div style="display: inline-flex; align-items: center; background: rgba(0, 180, 216, 0.2); backdrop-filter: blur(8px); padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; letter-spacing: 0.5px; border: 1px solid rgba(0, 180, 216, 0.4); color: #e0f2fe;">
                                    <span class="tka-pulse-dot"></span> SMK NEGERI 1 KUPANG BARAT &bull; STANDAR TKA
                                </div>
                                <h2 style="margin: 6px 0 0 0; font-size: clamp(20px, 3.5vw, 25px); font-weight: 800; letter-spacing: -0.5px; color: #ffffff;">
                                    Selamat Datang, Bapak Vicky Koroh, S.Pd.,Gr.
                                </h2>
                            </div>
                        </div>
                        <p style="margin: 0; font-size: 13.5px; line-height: 1.6; color: #e0f2fe; max-width: 650px;">
                            Sistem Asesmen Berbasis Komputer SMK Negeri 1 Kupang Barat aktif. Mendukung penuh soal Pilihan Ganda, Benar/Salah, Uraian, serta rendering rumus matematika KaTeX offline & manajemen gambar terintegrasi.
                        </p>
                    </div>
                    <div class="col-md-4 col-xs-12 text-right" style="margin-top: 15px;">
                        <div style="background: rgba(2, 11, 30, 0.45); backdrop-filter: blur(10px); padding: 14px 18px; border-radius: 14px; display: inline-block; text-align: left; border: 1px solid rgba(0, 180, 216, 0.3); width: auto; max-width: 100%;">
                            <div style="font-size: 11px; text-transform: uppercase; color: #94a3b8; font-weight: 700; margin-bottom: 4px;">
                                <i class="fa fa-clock-o text-yellow"></i> Waktu Server
                            </div>
                            <div style="font-size: 16px; font-weight: 800; color: #ffffff;" id="dashboard-clock">
                                <?php if(!empty($waktu_server)){ echo $waktu_server; }else{ echo date('Y-m-d H:i:s'); } ?>
                            </div>
                            <div style="font-size: 11px; color: #38bdf8; margin-top: 3px;">
                                <i class="fa fa-check-circle"></i> Sinkronisasi Presisi
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4 KPI Summary Cards dengan Hover Lift & SMK Theme -->
    <div class="row">
        <!-- Card 1: Bank Soal -->
        <div class="col-lg-3 col-sm-6 col-xs-12" style="margin-bottom: 15px;">
            <div class="box" style="border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 0;">
                <div class="box-body" style="padding: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div>
                            <span style="font-size: 11px; font-weight: 700; color: #0077b6; text-transform: uppercase; letter-spacing: 0.5px;">Bank Soal & Topik</span>
                            <h3 style="margin: 6px 0 0 0; font-size: 24px; font-weight: 800; color: #001233;">TKA Modern</h3>
                            <p style="margin: 3px 0 0 0; font-size: 12px; color: #0096c7; font-weight: 600;">PG, Benar/Salah, Uraian</p>
                        </div>
                        <div style="width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%); color: #0077b6; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                            <i class="fa fa-folder-open"></i>
                        </div>
                    </div>
                    <div style="margin-top: 14px; padding-top: 10px; border-top: 1px solid #f1f5f9;">
                        <a href="<?php echo site_url(); ?>/manager/modul_soal" class="btn btn-sm btn-default btn-block" style="font-weight: 600; color: #0077b6; border-radius: 8px;">
                            Kelola Soal & Kunci <i class="fa fa-arrow-right" style="margin-left: 5px;"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Jadwal Ujian -->
        <div class="col-lg-3 col-sm-6 col-xs-12" style="margin-bottom: 15px;">
            <div class="box" style="border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 0;">
                <div class="box-body" style="padding: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div>
                            <span style="font-size: 11px; font-weight: 700; color: #059669; text-transform: uppercase; letter-spacing: 0.5px;">Jadwal Ujian</span>
                            <h3 style="margin: 6px 0 0 0; font-size: 24px; font-weight: 800; color: #001233;">Jadwal Tes</h3>
                            <p style="margin: 3px 0 0 0; font-size: 12px; color: #10b981; font-weight: 600;">Acak Soal & Jawaban</p>
                        </div>
                        <div style="width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); color: #059669; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                            <i class="fa fa-calendar-check-o"></i>
                        </div>
                    </div>
                    <div style="margin-top: 14px; padding-top: 10px; border-top: 1px solid #f1f5f9;">
                        <a href="<?php echo site_url(); ?>/manager/tes_tambah" class="btn btn-sm btn-default btn-block" style="font-weight: 600; color: #059669; border-radius: 8px;">
                            Atur Jadwal Tes <i class="fa fa-arrow-right" style="margin-left: 5px;"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Peserta Ujian -->
        <div class="col-lg-3 col-sm-6 col-xs-12" style="margin-bottom: 15px;">
            <div class="box" style="border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 0;">
                <div class="box-body" style="padding: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div>
                            <span style="font-size: 11px; font-weight: 700; color: #7c3aed; text-transform: uppercase; letter-spacing: 0.5px;">Manajemen Peserta</span>
                            <h3 style="margin: 6px 0 0 0; font-size: 24px; font-weight: 800; color: #001233;">Data Siswa</h3>
                            <p style="margin: 3px 0 0 0; font-size: 12px; color: #8b5cf6; font-weight: 600;">Rombel & Jurusan</p>
                        </div>
                        <div style="width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%); color: #7c3aed; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                            <i class="fa fa-users"></i>
                        </div>
                    </div>
                    <div style="margin-top: 14px; padding-top: 10px; border-top: 1px solid #f1f5f9;">
                        <a href="<?php echo site_url(); ?>/manager/peserta_daftar" class="btn btn-sm btn-default btn-block" style="font-weight: 600; color: #7c3aed; border-radius: 8px;">
                            Kelola Siswa <i class="fa fa-arrow-right" style="margin-left: 5px;"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Hasil Ujian -->
        <div class="col-lg-3 col-sm-6 col-xs-12" style="margin-bottom: 15px;">
            <div class="box" style="border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 0;">
                <div class="box-body" style="padding: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div>
                            <span style="font-size: 11px; font-weight: 700; color: #ea580c; text-transform: uppercase; letter-spacing: 0.5px;">Rekapitulasi</span>
                            <h3 style="margin: 6px 0 0 0; font-size: 24px; font-weight: 800; color: #001233;">Hasil Ujian</h3>
                            <p style="margin: 3px 0 0 0; font-size: 12px; color: #f97316; font-weight: 600;">Skor & Analisis Soal</p>
                        </div>
                        <div style="width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, #ffedd5 0%, #fed7aa 100%); color: #ea580c; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                            <i class="fa fa-bar-chart"></i>
                        </div>
                    </div>
                    <div style="margin-top: 14px; padding-top: 10px; border-top: 1px solid #f1f5f9;">
                        <a href="<?php echo site_url(); ?>/manager/tes_hasil" class="btn btn-sm btn-default btn-block" style="font-weight: 600; color: #ea580c; border-radius: 8px;">
                            Rekap Nilai Siswa <i class="fa fa-arrow-right" style="margin-left: 5px;"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Baris 2: Quick Action Hub & System Telemetry -->
    <div class="row" style="margin-top: 10px;">
        <!-- Kolom Kiri: Quick Actions -->
        <div class="col-md-7 col-xs-12">
            <div class="box" style="border-radius: 16px; border: 1px solid #e2e8f0;">
                <div class="box-header with-border" style="padding: 16px 20px;">
                    <h3 class="box-title" style="font-size: 15px; font-weight: 800; color: #001233;">
                        <i class="fa fa-bolt" style="color: #00b4d8; margin-right: 6px;"></i> Pusat Aksi Cepat (Quick Actions)
                    </h3>
                </div>
                <div class="box-body" style="padding: 20px;">
                    <div class="row">
                        <div class="col-sm-6 col-xs-12" style="margin-bottom: 12px;">
                            <a href="<?php echo site_url(); ?>/manager/modul_daftar" class="btn btn-block btn-default" style="text-align: left; padding: 12px 16px; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 12px;">
                                <div style="width: 36px; height: 36px; border-radius: 8px; background: #e0f2fe; color: #0077b6; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
                                    <i class="fa fa-book"></i>
                                </div>
                                <div>
                                    <div style="font-weight: 700; color: #001233; font-size: 13px;">Daftar Modul / Mapel</div>
                                    <div style="font-size: 11px; color: #64748b;">Kelola modul mata pelajaran</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm-6 col-xs-12" style="margin-bottom: 12px;">
                            <a href="<?php echo site_url(); ?>/manager/tes_evaluasi" class="btn btn-block btn-default" style="text-align: left; padding: 12px 16px; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 12px;">
                                <div style="width: 36px; height: 36px; border-radius: 8px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
                                    <i class="fa fa-pencil"></i>
                                </div>
                                <div>
                                    <div style="font-weight: 700; color: #001233; font-size: 13px;">Koreksi Soal Uraian</div>
                                    <div style="font-size: 11px; color: #64748b;">Beri nilai jawaban esai siswa</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm-6 col-xs-12" style="margin-bottom: 12px;">
                            <a href="<?php echo site_url(); ?>/manager/tes_token" class="btn btn-block btn-default" style="text-align: left; padding: 12px 16px; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 12px;">
                                <div style="width: 36px; height: 36px; border-radius: 8px; background: #fce7f3; color: #db2777; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
                                    <i class="fa fa-key"></i>
                                </div>
                                <div>
                                    <div style="font-weight: 700; color: #001233; font-size: 13px;">Generate Token Ujian</div>
                                    <div style="font-size: 11px; color: #64748b;">Rilis token pengawas ujian</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm-6 col-xs-12" style="margin-bottom: 12px;">
                            <a href="<?php echo site_url(); ?>/manager/pengaturan_cbt" class="btn btn-block btn-default" style="text-align: left; padding: 12px 16px; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 12px;">
                                <div style="width: 36px; height: 36px; border-radius: 8px; background: #f1f5f9; color: #475569; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
                                    <i class="fa fa-sliders"></i>
                                </div>
                                <div>
                                    <div style="font-weight: 700; color: #001233; font-size: 13px;">Pengaturan CBT</div>
                                    <div style="font-size: 11px; color: #64748b;">Identitas sekolah & pengumuman</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Telemetri Sistem -->
        <div class="col-md-5 col-xs-12">
            <div class="box" style="border-radius: 16px; border: 1px solid #e2e8f0;">
                <div class="box-header with-border" style="padding: 16px 20px;">
                    <h3 class="box-title" style="font-size: 15px; font-weight: 800; color: #001233;">
                        <i class="fa fa-shield" style="color: #10b981; margin-right: 6px;"></i> Telemetri & Status Sistem
                    </h3>
                </div>
                <div class="box-body" style="padding: 20px;">
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #f1f5f9;">
                            <span style="font-size: 13px; color: #64748b;"><i class="fa fa-server" style="margin-right: 6px; color: #0077b6;"></i> Web Server Engine</span>
                            <span class="label" style="background: #e0f2fe; color: #0077b6; font-size: 12px; padding: 4px 10px; border-radius: 8px;">PHP <?php echo phpversion(); ?></span>
                        </li>
                        <li style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #f1f5f9;">
                            <span style="font-size: 13px; color: #64748b;"><i class="fa fa-database" style="margin-right: 6px; color: #0077b6;"></i> Database Server</span>
                            <span class="label" style="background: #dcfce7; color: #059669; font-size: 12px; padding: 4px 10px; border-radius: 8px;">MySQL / MariaDB</span>
                        </li>
                        <li style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #f1f5f9;">
                            <span style="font-size: 13px; color: #64748b;"><i class="fa fa-calculator" style="margin-right: 6px; color: #0077b6;"></i> Formula Math Engine</span>
                            <span class="label" style="background: #fef3c7; color: #d97706; font-size: 12px; padding: 4px 10px; border-radius: 8px;">KaTeX Offline 100%</span>
                        </li>
                        <li style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0;">
                            <span style="font-size: 13px; color: #64748b;"><i class="fa fa-check-circle" style="margin-right: 6px; color: #10b981;"></i> Status Asesmen</span>
                            <span class="label" style="background: #dcfce7; color: #059669; font-size: 12px; padding: 4px 10px; border-radius: 8px;">Online & Stabil</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Tribute & Hak Cipta Vicky Koroh -->
    <div class="row">
        <div class="col-xs-12">
            <div style="background: #ffffff; border-radius: 16px; border: 1.5px solid rgba(0, 180, 216, 0.25); padding: 22px 26px; box-shadow: 0 4px 15px rgba(0, 119, 182, 0.06); display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
                <img src="<?php echo base_url(); ?>public/images/logo_smk.png" alt="Logo SMKN 1 Kupang Barat" 
                     style="width: 52px; height: 52px; object-fit: contain; filter: drop-shadow(0 2px 8px rgba(0, 180, 216, 0.35)); flex-shrink: 0;" />
                <div style="flex: 1; min-width: 260px;">
                    <h4 style="margin: 0 0 4px 0; font-size: 15px; font-weight: 800; color: #001233;">
                        SMK Negeri 1 Kupang Barat &bull; Hak Cipta &copy; Vicky Koroh
                    </h4>
                    <p style="margin: 0; font-size: 13px; color: #64748b; line-height: 1.5;">
                        Aplikasi CBT Modern Standar TKA Pusmendik Kemdikbud ini dikembangkan dan disempurnakan oleh <b>Vicky Koroh, S.Pd.,Gr.</b> untuk pelaksanaan asesmen resmi SMKN 1 Kupang Barat.
                    </p>
                </div>
                <div style="font-size: 12px; color: #0077b6; font-weight: 600;">
                    <i class="fa fa-shield"></i> Pilihan Ganda &bull; Benar/Salah &bull; Uraian
                </div>
            </div>
        </div>
    </div>
</section><!-- /.content -->
