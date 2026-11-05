<!-- Content Header (Page header) -->
<section class="content-header" style="margin-bottom: 15px;">
	<h1 style="font-size: 22px; font-weight: 800; color: #001233;">
		Import Data Pengguna Masal (.xlsx)
		<small style="color: #64748b; font-size: 13px;">Tambah akun Guru, Proktor/Operator, dan Administrator sekaligus</small>
	</h1>
	<ol class="breadcrumb" style="background: transparent;">
		<li><a href="<?php echo site_url(); ?>/manager"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="<?php echo site_url(); ?>/manager/useratur">Data User</a></li>
		<li class="active">Import Pengguna</li>
	</ol>
</section>

<!-- Main content -->
<section class="content">
	<!-- Petunjuk & Download Template -->
	<div class="row">
        <div class="col-xs-12">
            <div style="background: linear-gradient(135deg, #03045e 0%, #0077b6 100%); border-radius: 16px; padding: 22px 26px; color: #ffffff; margin-bottom: 22px; box-shadow: 0 8px 24px rgba(0, 119, 182, 0.25);">
                <div class="row" style="display: flex; align-items: center; flex-wrap: wrap;">
					<div class="col-md-8 col-xs-12">
						<div style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700; color: #90e0ef; margin-bottom: 6px;">
							<i class="fa fa-file-excel-o"></i> Format Berkas Excel Modern (.xlsx)
						</div>
						<h3 style="margin: 0 0 8px 0; font-size: 20px; font-weight: 800; color: #ffffff;">
							Unduh Format Import Pengguna CBT
						</h3>
						<p style="margin: 0; font-size: 13px; color: #e0f2fe; line-height: 1.6;">
							Gunakan format Excel standar untuk mengunggah akun Guru, Proktor, dan Pengawas secara masal. Password otomatis dienkripsi dengan standar keamanan SHA-1.
						</p>
					</div>
					<div class="col-md-4 col-xs-12 text-right" style="margin-top: 10px;">
						<a href="<?php echo base_url('public/form/form-data-user.xlsx'); ?>" class="btn btn-default" style="background: #ffffff; color: #0077b6; font-weight: 800; padding: 10px 22px; border-radius: 10px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
							<i class="fa fa-download" style="margin-right: 8px;"></i> Download Template (.xlsx)
						</a>
					</div>
				</div>
            </div>
        </div>
    </div>

	<div class="row">
        <div class="col-md-7 col-xs-12">
			<?php echo form_open_multipart($url.'/import','id="form-importuser"'); ?>
                <div class="box" style="border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 14px rgba(0,0,0,0.03);">
                    <div class="box-header with-border" style="padding: 16px 20px; background: #f8fafc; border-top-left-radius: 16px; border-top-right-radius: 16px;">
    					<div class="box-title" style="font-size: 15px; font-weight: 800; color: #001233;">
							<i class="fa fa-upload text-blue" style="margin-right: 8px;"></i> Unggah File Data Pengguna
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
                            <label style="font-weight: 700; color: #1e293b; margin-bottom: 8px;">Pilih Berkas Excel (.xlsx / .xls)</label>
                            <input type="file" id="userfile" name="userfile" class="form-control" accept=".xlsx, .xls" style="padding: 10px; height: auto; border: 1.5px dashed #0077b6; background: #f0f9ff; border-radius: 10px;">
                            <p class="help-block" style="font-size: 12px; color: #64748b; margin-top: 6px;">
								Mendukung format modern Microsoft Excel (.xlsx) dan format klasik (.xls).
							</p>
                        </div>
                        
                        <?php if(!empty($hasil)){ echo $hasil; } ?>
                    </div>
					
					<div class="box-footer" style="padding: 16px 24px; background: #f8fafc; border-bottom-left-radius: 16px; border-bottom-right-radius: 16px; display: flex; justify-content: space-between; align-items: center;">
                        <a href="<?php echo site_url(); ?>/manager/useratur" class="btn btn-default" style="border-radius: 8px; font-weight: 600;">
							<i class="fa fa-arrow-left"></i> Kembali ke Daftar User
						</a>
						<button type="submit" class="btn btn-primary" id="import" style="padding: 9px 24px; font-weight: 700; border-radius: 8px; background: linear-gradient(135deg, #0077b6 0%, #0096c7 100%); border: none;">
							<i class="fa fa-upload" style="margin-right: 6px;"></i> Mulai Import Data
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
						<i class="fa fa-table text-blue" style="margin-right: 8px;"></i> Struktur Kolom Excel
					</div>
				</div>
				<div class="box-body" style="padding: 20px;">
					<table class="table table-bordered table-striped" style="font-size: 12px; margin-bottom: 0;">
						<thead>
							<tr style="background: #f1f5f9; color: #0f172a;">
								<th style="width: 70px;">Kolom</th>
								<th>Nama Field</th>
								<th>Keterangan</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><b>Kolom A</b></td>
								<td>No</td>
								<td>Nomor urut (opsional)</td>
							</tr>
							<tr>
								<td><b>Kolom B</b></td>
								<td>Username</td>
								<td><span class="text-red">*</span> Username unik untuk login</td>
							</tr>
							<tr>
								<td><b>Kolom C</b></td>
								<td>Password</td>
								<td><span class="text-red">*</span> Kata sandi awal (plain text)</td>
							</tr>
							<tr>
								<td><b>Kolom D</b></td>
								<td>Nama Lengkap</td>
								<td><span class="text-red">*</span> Nama lengkap dan gelar</td>
							</tr>
							<tr>
								<td><b>Kolom E</b></td>
								<td>Level</td>
								<td><code>guru</code>, <code>operator</code>, atau <code>admin</code></td>
							</tr>
							<tr>
								<td><b>Kolom F</b></td>
								<td>Opsi Mapel</td>
								<td>ID Topik (opsional / boleh kosong)</td>
							</tr>
							<tr>
								<td><b>Kolom G</b></td>
								<td>Keterangan</td>
								<td>Keterangan tugas / proktor</td>
							</tr>
						</tbody>
					</table>
					<div style="margin-top: 14px; font-size: 12px; color: #64748b;">
						<i class="fa fa-info-circle text-blue"></i> Baris pertama pada berkas Excel adalah judul header (jangan dihapus). Data dimulai dari baris ke-2.
					</div>
				</div>
			</div>
		</div>
    </div>
</section>
