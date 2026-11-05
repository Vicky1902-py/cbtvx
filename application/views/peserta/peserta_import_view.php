<!-- Content Header (Page header) -->
<section class="content-header" style="margin-bottom: 15px;">
	<h1 style="font-size: 22px; font-weight: 800; color: #001233;">
		Import Data Peserta Ujian Masal (.xlsx)
		<small style="color: #64748b; font-size: 13px;">Tambah akun ujian siswa sekaligus menggunakan lembar kerja Excel</small>
	</h1>
	<ol class="breadcrumb" style="background: transparent;">
		<li><a href="<?php echo site_url(); ?>/manager"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="<?php echo site_url(); ?>/manager/peserta_daftar">Peserta</a></li>
		<li class="active">Import Peserta</li>
	</ol>
</section>

<!-- Main content -->
<section class="content">
	<!-- Banner Unduh Template Excel -->
	<div class="row">
        <div class="col-xs-12">
            <div style="background: linear-gradient(135deg, #03045e 0%, #0077b6 100%); border-radius: 16px; padding: 22px 26px; color: #ffffff; margin-bottom: 22px; box-shadow: 0 8px 24px rgba(0, 119, 182, 0.25);">
                <div class="row" style="display: flex; align-items: center; flex-wrap: wrap;">
					<div class="col-md-7 col-xs-12">
						<div style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700; color: #90e0ef; margin-bottom: 6px;">
							<i class="fa fa-file-excel-o"></i> Format Berkas Excel Peserta
						</div>
						<h3 style="margin: 0 0 8px 0; font-size: 20px; font-weight: 800; color: #ffffff;">
							Unduh Format Import Siswa CBT SMKN 1 Kupang Barat
						</h3>
						<p style="margin: 0; font-size: 13px; color: #e0f2fe; line-height: 1.6;">
							Unduh salah satu template berikut, isi data siswa (NISN, Nama, Kelas/Rombel), lalu unggah pada form di bawah. Jika nama Kelas belum terdaftar, sistem akan otomatis mendaftarkannya.
						</p>
					</div>
					<div class="col-md-5 col-xs-12 text-right" style="margin-top: 10px; display: flex; gap: 10px; justify-content: flex-end; flex-wrap: wrap;">
						<a href="<?php echo base_url('public/form/form-data-siswa.xlsx'); ?>" class="btn btn-default" style="background: #ffffff; color: #0077b6; font-weight: 800; padding: 10px 18px; border-radius: 10px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
							<i class="fa fa-download" style="margin-right: 6px;"></i> Template Modern (.xlsx)
						</a>
						<a href="<?php echo base_url('public/form/form-data-siswa.xls'); ?>" class="btn btn-default" style="background: rgba(255,255,255,0.18); color: #ffffff; font-weight: 700; padding: 10px 14px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.35);">
							<i class="fa fa-file-excel-o" style="margin-right: 6px;"></i> Template Klasik (.xls)
						</a>
					</div>
				</div>
            </div>
        </div>
    </div>

	<div class="row">
        <!-- Form Unggah Berkas -->
        <div class="col-md-7 col-xs-12">
			<?php echo form_open_multipart($url.'/import','id="form-importsiswa"'); ?>
                <div class="box" style="border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 14px rgba(0,0,0,0.03);">
                    <div class="box-header with-border" style="padding: 16px 20px; background: #f8fafc; border-top-left-radius: 16px; border-top-right-radius: 16px;">
    					<div class="box-title" style="font-size: 15px; font-weight: 800; color: #001233;">
							<i class="fa fa-upload text-blue" style="margin-right: 8px;"></i> Unggah File Excel Data Peserta
						</div>
                    </div>

                    <div class="box-body" style="padding: 24px;">
						<?php if(!empty($error_upload)): ?>
							<div class="alert alert-danger alert-dismissible" style="border-radius: 10px;">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<h4><i class="icon fa fa-ban"></i> Gagal Mengunggah!</h4>
								<?php echo $error_upload; ?>
							</div>
						<?php endif; ?>

						<?php if(!empty($filename)): ?>
							<div class="alert alert-success alert-dismissible" style="border-radius: 10px;">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<h4><i class="icon fa fa-check"></i> Berhasil!</h4>
								<?php echo $filename; ?>
							</div>
						<?php endif; ?>

                        <div class="form-group" style="margin-bottom: 20px;">
                            <label style="font-weight: 700; color: #1e293b; margin-bottom: 8px;">Pilih Berkas Excel (.xlsx atau .xls)</label>
                            <input type="file" id="userfile" name="userfile" class="form-control" accept=".xlsx, .xls" style="padding: 10px; height: auto; border: 1.5px dashed #0077b6; background: #f0f9ff; border-radius: 10px;">
                            <p class="help-block" style="font-size: 12px; color: #64748b; margin-top: 6px;">
								Mendukung file Excel format modern (.xlsx) dan Excel 97-2003 (.xls).
							</p>
                        </div>
                        
                        <?php if(!empty($hasil)){ echo $hasil; } ?>
                    </div>
					
					<div class="box-footer" style="padding: 16px 24px; background: #f8fafc; border-bottom-left-radius: 16px; border-bottom-right-radius: 16px; display: flex; justify-content: space-between; align-items: center;">
                        <a href="<?php echo site_url(); ?>/manager/peserta_daftar" class="btn btn-default" style="border-radius: 8px; font-weight: 600;">
							<i class="fa fa-arrow-left"></i> Kembali ke Daftar Siswa
						</a>
						<button type="submit" class="btn btn-primary" id="import" style="padding: 9px 24px; font-weight: 700; border-radius: 8px; background: linear-gradient(135deg, #0077b6 0%, #0096c7 100%); border: none;">
							<i class="fa fa-upload" style="margin-right: 6px;"></i> Mulai Import Peserta
						</button>
                    </div>
                </div>
			<?php echo form_close(); ?> 
        </div>

		<!-- Kolom Kanan: Panduan Struktur Kolom -->
		<div class="col-md-5 col-xs-12">
			<div class="box" style="border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 14px rgba(0,0,0,0.03);">
				<div class="box-header with-border" style="padding: 16px 20px; background: #f8fafc; border-top-left-radius: 16px; border-top-right-radius: 16px;">
					<div class="box-title" style="font-size: 15px; font-weight: 800; color: #001233;">
						<i class="fa fa-table text-blue" style="margin-right: 8px;"></i> Struktur Kolom Lembar Siswa
					</div>
				</div>
				<div class="box-body" style="padding: 20px;">
					<table class="table table-bordered table-striped" style="font-size: 12px; margin-bottom: 0;">
						<thead>
							<tr style="background: #f1f5f9; color: #0f172a;">
								<th style="width: 70px;">Kolom</th>
								<th>Nama Kolom</th>
								<th>Keterangan / Contoh</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><b>Kolom A</b></td>
								<td>No</td>
								<td>Nomor urut baris (opsional)</td>
							</tr>
							<tr>
								<td><b>Kolom B</b></td>
								<td>Username</td>
								<td><span class="text-red">*</span> NISN / Nomor Ujian (contoh: <code>1001</code>)</td>
							</tr>
							<tr>
								<td><b>Kolom C</b></td>
								<td>Password</td>
								<td><span class="text-red">*</span> PIN / Sandi Siswa (contoh: <code>1001</code>)</td>
							</tr>
							<tr>
								<td><b>Kolom D</b></td>
								<td>Nama Siswa</td>
								<td><span class="text-red">*</span> Nama lengkap siswa (contoh: <code>Alexander Manafe</code>)</td>
							</tr>
							<tr>
								<td><b>Kolom E</b></td>
								<td>Email</td>
								<td>Email siswa (otomatis jika dikosongkan)</td>
							</tr>
							<tr>
								<td><b>Kolom F</b></td>
								<td>Group / Kelas</td>
								<td><span class="text-red">*</span> Nama rombel (contoh: <code>XI TKJ 1</code>)</td>
							</tr>
							<tr>
								<td><b>Kolom G</b></td>
								<td>Keterangan</td>
								<td>Sesi / Ruangan (contoh: <code>Ruang 1, Sesi 1</code>)</td>
							</tr>
						</tbody>
					</table>
					<div style="margin-top: 14px; font-size: 12px; color: #64748b;">
						<i class="fa fa-info-circle text-blue"></i> Baris 1 adalah judul header. Data siswa dimulai dari baris ke-2. Setelah mengimport siswa, Anda dapat mengunggah foto siswa masal via format .ZIP pada menu <b>Kelola Foto Siswa</b>.
					</div>
				</div>
			</div>
		</div>
    </div>
</section>
