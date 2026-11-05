<!-- Content Header (Page header) -->
<section class="content-header" style="margin-bottom: 10px;">
	<h1>
		Dashboard Utama
		<small>Pusat Kendali Ujian Online Standar TKA Kemdikbud</small>
	</h1>
	<ol class="breadcrumb" style="background: transparent;">
		<li><a href="<?php echo site_url(); ?>/manager"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Dashboard</li>
	</ol>
</section>

<!-- Main content -->
<section class="content">
    <!-- Hero Banner Selamat Datang Vicky Koroh -->
    <div class="row">
        <div class="col-xs-12">
            <div style="background: linear-gradient(135deg, #0f2b48 0%, #1e3a5f 45%, #2563eb 100%); border-radius: 16px; padding: 28px 32px; color: #ffffff; box-shadow: 0 10px 30px -5px rgba(15, 43, 72, 0.3); margin-bottom: 25px; position: relative; overflow: hidden;">
                <!-- Decorative Circle Orbs -->
                <div style="position: absolute; right: -40px; top: -40px; width: 220px; height: 220px; background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 70%); border-radius: 50%; pointer-events: none;"></div>
                <div style="position: absolute; right: 120px; bottom: -60px; width: 180px; height: 180px; background: radial-gradient(circle, rgba(96,165,250,0.2) 0%, rgba(255,255,255,0) 70%); border-radius: 50%; pointer-events: none;"></div>

                <div class="row" style="position: relative; z-index: 2;">
                    <div class="col-md-8 col-xs-12">
                        <div style="display: inline-flex; align-items: center; background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(8px); padding: 5px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; margin-bottom: 14px; letter-spacing: 0.5px; border: 1px solid rgba(255, 255, 255, 0.2);">
                            <span class="tka-pulse-dot"></span> SISTEM CBT STANDAR TKA PUSMENDIK KEMDIKBUD AKTIF
                        </div>
                        <h2 style="margin: 0 0 10px 0; font-size: 26px; font-weight: 800; letter-spacing: -0.5px;">
                            Selamat Datang, Bapak Vicky Koroh, S.Pd.,Gr.
                        </h2>
                        <p style="margin: 0; font-size: 14px; line-height: 1.6; color: #cbd5e1; max-width: 650px;">
                            Sistem Asesmen & Ujian Berbasis Komputer SMKN 1 Kupang Barat siap digunakan. Dilengkapi dukungan penuh soal Pilihan Ganda, Benar/Salah, Uraian, serta rendering rumus matematika LaTeX & upload gambar 100% offline.
                        </p>
                    </div>
                    <div class="col-md-4 col-xs-12" style="text-align: right; margin-top: 15px;">
                        <div style="background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(10px); padding: 14px 20px; border-radius: 12px; display: inline-block; text-align: left; border: 1px solid rgba(255,255,255,0.1);">
                            <div style="font-size: 11px; text-transform: uppercase; color: #94a3b8; font-weight: 700; margin-bottom: 4px;">
                                <i class="fa fa-clock-o text-yellow"></i> Waktu Server (Lokal)
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

    <!-- 4 KPI Summary Cards dengan Motion & Hover Lift -->
    <div class="row">
        <!-- Card 1: Bank Soal -->
        <div class="col-lg-3 col-sm-6 col-xs-12">
            <div class="box" style="border-radius: 14px; border: 1px solid #e2e8f0; overflow: hidden; position: relative;">
                <div class="box-body" style="padding: 22px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div>
                            <span style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Bank Soal & Topik</span>
                            <h3 style="margin: 8px 0 0 0; font-size: 28px; font-weight: 800; color: #0f172a;">TKA Modern</h3>
                            <p style="margin: 4px 0 0 0; font-size: 12px; color: #2563eb; font-weight: 600;">PG, Benar/Salah, Esai</p>
                        </div>
                        <div style="width: 50px; height: 50px; border-radius: 12px; background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); color: #1d4ed8; display: flex; align-items: center; justify-content: center; font-size: 22px; box-shadow: 0 4px 10px rgba(37, 99, 235, 0.15);">
                            <i class="fa fa-folder-open"></i>
                        </div>
                    </div>
                    <div style="margin-top: 16px; padding-top: 12px; border-top: 1px solid #f1f5f9;">
                        <a href="<?php echo site_url(); ?>/manager/modul_soal" class="btn btn-sm btn-default btn-block" style="font-weight: 600; color: #2563eb;">
                            Kelola Soal & Kunci <i class="fa fa-arrow-right" style="margin-left: 5px;"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Jadwal Ujian -->
        <div class="col-lg-3 col-sm-6 col-xs-12">
            <div class="box" style="border-radius: 14px; border: 1px solid #e2e8f0; overflow: hidden; position: relative;">
                <div class="box-body" style="padding: 22px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div>
                            <span style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Jadwal Ujian</span>
                            <h3 style="margin: 8px 0 0 0; font-size: 28px; font-weight: 800; color: #0f172a;">Jadwal Tes</h3>
                            <p style="margin: 4px 0 0 0; font-size: 12px; color: #10b981; font-weight: 600;">Acak Soal & Jawaban</p>
                        </div>
                        <div style="width: 50px; height: 50px; border-radius: 12px; background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); color: #059669; display: flex; align-items: center; justify-content: center; font-size: 22px; box-shadow: 0 4px 10px rgba(16, 185, 129, 0.15);">
                            <i class="fa fa-calendar-check-o"></i>
                        </div>
                    </div>
                    <div style="margin-top: 16px; padding-top: 12px; border-top: 1px solid #f1f5f9;">
                        <a href="<?php echo site_url(); ?>/manager/tes_tambah" class="btn btn-sm btn-default btn-block" style="font-weight: 600; color: #059669;">
                            Atur Jadwal Tes <i class="fa fa-arrow-right" style="margin-left: 5px;"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Peserta & Grup -->
        <div class="col-lg-3 col-sm-6 col-xs-12">
            <div class="box" style="border-radius: 14px; border: 1px solid #e2e8f0; overflow: hidden; position: relative;">
                <div class="box-body" style="padding: 22px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div>
                            <span style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Data Siswa</span>
                            <h3 style="margin: 8px 0 0 0; font-size: 28px; font-weight: 800; color: #0f172a;">Rombel</h3>
                            <p style="margin: 4px 0 0 0; font-size: 12px; color: #8b5cf6; font-weight: 600;">Import Excel / Manual</p>
                        </div>
                        <div style="width: 50px; height: 50px; border-radius: 12px; background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%); color: #7c3aed; display: flex; align-items: center; justify-content: center; font-size: 22px; box-shadow: 0 4px 10px rgba(139, 92, 246, 0.15);">
                            <i class="fa fa-users"></i>
                        </div>
                    </div>
                    <div style="margin-top: 16px; padding-top: 12px; border-top: 1px solid #f1f5f9;">
                        <a href="<?php echo site_url(); ?>/manager/peserta_daftar" class="btn btn-sm btn-default btn-block" style="font-weight: 600; color: #7c3aed;">
                            Daftar Siswa & Kelas <i class="fa fa-arrow-right" style="margin-left: 5px;"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Token Ujian -->
        <div class="col-lg-3 col-sm-6 col-xs-12">
            <div class="box" style="border-radius: 14px; border: 1px solid #e2e8f0; overflow: hidden; position: relative;">
                <div class="box-body" style="padding: 22px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div>
                            <span style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Keamanan Tes</span>
                            <h3 style="margin: 8px 0 0 0; font-size: 28px; font-weight: 800; color: #0f172a;">Token</h3>
                            <p style="margin: 4px 0 0 0; font-size: 12px; color: #f59e0b; font-weight: 600;">Rilis Token Pengawas</p>
                        </div>
                        <div style="width: 50px; height: 50px; border-radius: 12px; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 22px; box-shadow: 0 4px 10px rgba(245, 158, 11, 0.15);">
                            <i class="fa fa-key"></i>
                        </div>
                    </div>
                    <div style="margin-top: 16px; padding-top: 12px; border-top: 1px solid #f1f5f9;">
                        <a href="<?php echo site_url(); ?>/manager/tes_token" class="btn btn-sm btn-default btn-block" style="font-weight: 600; color: #d97706;">
                            Generate Token <i class="fa fa-arrow-right" style="margin-left: 5px;"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Action Hub (Jalan Pintas Cepat dengan Motion) -->
    <div class="row" style="margin-top: 5px;">
        <div class="col-xs-12">
            <div class="box" style="border-radius: 16px;">
                <div class="box-header with-border" style="padding: 16px 22px;">
                    <h3 class="box-title" style="font-size: 16px; font-weight: 800; color: #0f172a;">
                        <i class="fa fa-bolt text-yellow"></i> Jalan Pintas Cepat (Quick Workflow)
                    </h3>
                </div>
                <div class="box-body" style="padding: 24px;">
                    <div class="row">
                        <!-- Action 1 -->
                        <div class="col-md-3 col-sm-6 col-xs-12" style="margin-bottom: 12px;">
                            <a href="<?php echo site_url(); ?>/manager/modul_soal" style="text-decoration: none;">
                                <div style="background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px; transition: all 0.25s ease; cursor: pointer; text-align: center;" onmouseover="this.style.borderColor='#2563eb'; this.style.transform='translateY(-4px)'; this.style.background='#eff6ff';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.transform='none'; this.style.background='#f8fafc';">
                                    <div style="width: 54px; height: 54px; margin: 0 auto 12px auto; background: #2563eb; color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 22px;">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </div>
                                    <h4 style="margin: 0; font-size: 15px; font-weight: 700; color: #0f172a;">1. Buat Butir Soal</h4>
                                    <p style="margin: 6px 0 0 0; font-size: 12px; color: #64748b;">Editor Rumus KaTeX & Gambar</p>
                                </div>
                            </a>
                        </div>

                        <!-- Action 2 -->
                        <div class="col-md-3 col-sm-6 col-xs-12" style="margin-bottom: 12px;">
                            <a href="<?php echo site_url(); ?>/manager/tes_tambah" style="text-decoration: none;">
                                <div style="background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px; transition: all 0.25s ease; cursor: pointer; text-align: center;" onmouseover="this.style.borderColor='#10b981'; this.style.transform='translateY(-4px)'; this.style.background='#f0fdf4';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.transform='none'; this.style.background='#f8fafc';">
                                    <div style="width: 54px; height: 54px; margin: 0 auto 12px auto; background: #10b981; color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 22px;">
                                        <i class="fa fa-cogs"></i>
                                    </div>
                                    <h4 style="margin: 0; font-size: 15px; font-weight: 700; color: #0f172a;">2. Jadwalkan Ujian</h4>
                                    <p style="margin: 6px 0 0 0; font-size: 12px; color: #64748b;">Atur Waktu & Acak Soal</p>
                                </div>
                            </a>
                        </div>

                        <!-- Action 3 -->
                        <div class="col-md-3 col-sm-6 col-xs-12" style="margin-bottom: 12px;">
                            <a href="<?php echo site_url(); ?>/manager/tes_token" style="text-decoration: none;">
                                <div style="background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px; transition: all 0.25s ease; cursor: pointer; text-align: center;" onmouseover="this.style.borderColor='#f59e0b'; this.style.transform='translateY(-4px)'; this.style.background='#fffbeb';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.transform='none'; this.style.background='#f8fafc';">
                                    <div style="width: 54px; height: 54px; margin: 0 auto 12px auto; background: #f59e0b; color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 22px;">
                                        <i class="fa fa-key"></i>
                                    </div>
                                    <h4 style="margin: 0; font-size: 15px; font-weight: 700; color: #0f172a;">3. Rilis Token Tes</h4>
                                    <p style="margin: 6px 0 0 0; font-size: 12px; color: #64748b;">Generate Kode Masuk Ujian</p>
                                </div>
                            </a>
                        </div>

                        <!-- Action 4 -->
                        <div class="col-md-3 col-sm-6 col-xs-12" style="margin-bottom: 12px;">
                            <a href="<?php echo site_url(); ?>/manager/tes_hasil" style="text-decoration: none;">
                                <div style="background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px; transition: all 0.25s ease; cursor: pointer; text-align: center;" onmouseover="this.style.borderColor='#6366f1'; this.style.transform='translateY(-4px)'; this.style.background='#eef2ff';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.transform='none'; this.style.background='#f8fafc';">
                                    <div style="width: 54px; height: 54px; margin: 0 auto 12px auto; background: #6366f1; color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 22px;">
                                        <i class="fa fa-bar-chart"></i>
                                    </div>
                                    <h4 style="margin: 0; font-size: 15px; font-weight: 700; color: #0f172a;">4. Rekap & Nilai</h4>
                                    <p style="margin: 6px 0 0 0; font-size: 12px; color: #64748b;">Evaluasi Esai & Export Excel</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sistem Telemetri & Hak Cipta Vicky Koroh -->
    <div class="row">
        <!-- Telemetri Lingkungan Server -->
        <div class="col-md-6 col-xs-12">
            <div class="box" style="border-radius: 16px;">
                <div class="box-header with-border">
                    <h3 class="box-title" style="font-weight: 800; color: #0f172a;"><i class="fa fa-server text-blue"></i> Status Lingkungan Server</h3>
                </div>
                <div class="box-body" style="padding: 20px;">
                    <table class="table table-bordered" style="margin: 0;">
                        <tbody>
                            <tr>
                                <td style="width: 45%; font-weight: 600;"><i class="fa fa-code"></i> Versi PHP</td>
                                <td><span class="label label-success" style="font-size: 12px; padding: 4px 10px; border-radius: 4px;">PHP <?php echo PHP_VERSION; ?> (Optimized)</span></td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600;"><i class="fa fa-database"></i> Database Server</td>
                                <td><span class="label label-primary" style="font-size: 12px; padding: 4px 10px; border-radius: 4px;">MariaDB Portable (Hemat RAM ~35MB)</span></td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600;"><i class="fa fa-calculator"></i> Mesin Rumus Matematika</td>
                                <td><span class="label label-info" style="font-size: 12px; padding: 4px 10px; border-radius: 4px;">KaTeX Math Engine (100% Offline)</span></td>
                            </tr>
                            <tr>
                                <td style="font-weight: 600;"><i class="fa fa-upload"></i> Batas Upload File</td>
                                <td><b>POST:</b> <?php if(!empty($post_max_size)){ echo $post_max_size; }else{ echo '64M'; } ?> | <b>UPLOAD:</b> <?php if(!empty($upload_max_filesize)){ echo $upload_max_filesize; }else{ echo '64M'; } ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Kartu Hak Cipta & Identitas Pembuat -->
        <div class="col-md-6 col-xs-12">
            <div class="box" style="border-radius: 16px; background: #ffffff;">
                <div class="box-header with-border">
                    <h3 class="box-title" style="font-weight: 800; color: #0f172a;"><i class="fa fa-certificate text-yellow"></i> Hak Cipta & Lisensi Penggunaan</h3>
                </div>
                <div class="box-body" style="padding: 20px;">
                    <div style="background: #f8fafc; border-left: 4px solid #2563eb; padding: 16px; border-radius: 6px; margin-bottom: 12px;">
                        <h4 style="margin: 0 0 6px 0; font-size: 15px; font-weight: 800; color: #0f172a;">
                            Hak Cipta © Vicky Koroh
                        </h4>
                        <p style="margin: 0; font-size: 13px; line-height: 1.6; color: #475569;">
                            Aplikasi CBT Modern Berstandar TKA Pusmendik Kemdikbud ini dikembangkan dan disempurnakan secara khusus oleh <b>Vicky Koroh, S.Pd.,Gr.</b> untuk pelaksanaan asesmen di SMKN 1 Kupang Barat. Seluruh hak cipta dilindungi oleh undang-undang.
                        </p>
                    </div>
                    <div style="font-size: 12px; color: #64748b; line-height: 1.5;">
                        <i class="fa fa-shield text-green"></i> Mendukung hingga 36+ Client PC per sesi ujian secara stabil di jaringan LAN maupun mandiri.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section><!-- /.content -->