<!-- Content Header (Page header) -->
<section class="content-header" style="margin-bottom: 15px;">
	<h1 style="font-size: 22px; font-weight: 800; color: #001233;">
		Cetak Kartu Peserta Ujian (CBT)
		<small style="font-size: 13px; color: #64748b;">Mencetak Kartu Peserta lengkap dengan Kop Resmi, Foto Siswa, dan TTD Kepala Sekolah</small>
	</h1>
	<ol class="breadcrumb" style="background: transparent;">
		<li><a href="<?php echo site_url(); ?>/manager"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="<?php echo site_url(); ?>/manager/peserta_daftar">Data Peserta</a></li>
		<li class="active">Cetak Kartu</li>
	</ol>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
        <div class="col-md-12">
            <div class="callout callout-info" style="border-radius: 12px; background: #f0f9ff !important; border-left: 5px solid #0096c7; color: #034f75 !important;">
                <h4 style="font-size: 15px; font-weight: 800; margin-bottom: 6px;"><i class="fa fa-info-circle"></i> Format Resmi Kartu Asesmen SMKN 1 Kupang Barat:</h4>
                <p style="margin-bottom: 4px; font-size: 13px;">
					Kartu login dicetak otomatis dengan <b>Kop Dinas Pendidikan NTT & SMKN 1 Kupang Barat (Kuanheun)</b>, 
					Logo Pemprov NTT di sisi kiri, Logo Sekolah di sisi kanan, Pas Foto Siswa, serta TTD & Stempel Kepala Sekolah.
				</p>
                <p style="margin-bottom: 0; font-size: 12.5px; color: #0284c7;">
					Foto siswa dapat diunggah secara massal dengan format file .ZIP sesuai NISN di menu <b>Upload Foto Siswa</b>.
				</p>
            </div>
        </div>
    </div>

	<div class="row">
        <div class="col-md-8 col-xs-12">
			<div class="box" style="border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.03); margin-bottom: 25px;">
				<div class="box-header with-border" style="padding: 16px 20px; background: #f8fafc; border-top-left-radius: 16px; border-top-right-radius: 16px;">
					<div class="box-title" style="font-size: 15px; font-weight: 800; color: #001233;">
						<i class="fa fa-print text-blue" style="margin-right: 8px;"></i> Pilih Rombel / Group untuk Dicetak
					</div>
				</div>

				<div class="box-body" style="padding: 24px;">
					<div class="form-group">
						<label style="font-weight: 700; color: #1e293b;">Pilih Group / Kelas Siswa</label>
						<select name="group" id="group" class="form-control" style="border-radius: 8px; height: 42px; font-size: 14px;">
							<?php if(!empty($select_group)){ echo $select_group; } ?>
						</select>
						<p class="help-block" style="font-size: 12px; color: #64748b;">Pilih "Semua Group / Kelas" untuk mencetak seluruh peserta ujian sekaligus.</p>
					</div>

					<div style="margin-top: 25px; display: flex; gap: 12px; align-items: center;">
						<button type="button" class="btn btn-primary" id="btn-cetak-kartu" style="padding: 10px 24px; font-weight: 700; border-radius: 10px; background: linear-gradient(135deg, #0077b6 0%, #0096c7 100%); border: none; box-shadow: 0 4px 14px rgba(0, 119, 182, 0.4);">
							<i class="fa fa-print" style="margin-right: 6px;"></i> Buka & Cetak Kartu (Print)
						</button>

						<a href="<?php echo site_url(); ?>/manager/peserta_foto" class="btn btn-default" style="padding: 10px 20px; font-weight: 700; border-radius: 10px; border-color: #cbd5e1;">
							<i class="fa fa-camera text-blue" style="margin-right: 6px;"></i> Kelola Foto Siswa (.ZIP)
						</a>
					</div>
				</div>
			</div>
        </div>

		<!-- Panel Informasi Identitas Kop -->
		<div class="col-md-4 col-xs-12">
			<div class="box" style="border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.03); margin-bottom: 25px;">
				<div class="box-header with-border" style="padding: 16px 20px; background: #f8fafc; border-top-left-radius: 16px; border-top-right-radius: 16px;">
					<div class="box-title" style="font-size: 15px; font-weight: 800; color: #001233;">
						<i class="fa fa-university text-blue" style="margin-right: 8px;"></i> Identitas Sekolah Aktif
					</div>
				</div>
				<div class="box-body" style="padding: 18px 20px; font-size: 13px;">
					<div style="margin-bottom: 8px;">
						<span style="color: #64748b; font-size: 11px; text-transform: uppercase; font-weight: 700;">Sekolah:</span><br>
						<b><?php echo $sekolah['nama']; ?></b>
					</div>
					<div style="margin-bottom: 8px;">
						<span style="color: #64748b; font-size: 11px; text-transform: uppercase; font-weight: 700;">Lokasi Definitif:</span><br>
						<span><?php echo $sekolah['alamat']; ?>, <?php echo $sekolah['kecamatan']; ?></span>
					</div>
					<div style="margin-bottom: 8px;">
						<span style="color: #64748b; font-size: 11px; text-transform: uppercase; font-weight: 700;">Kepala Sekolah:</span><br>
						<b><?php echo $sekolah['kepsek_nama']; ?></b><br>
						<span style="color: #64748b; font-size: 12px;">NIP: <?php echo $sekolah['kepsek_nip']; ?></span>
					</div>
					<div style="margin-top: 15px; padding-top: 12px; border-top: 1px solid #f1f5f9;">
						<a href="<?php echo site_url(); ?>/manager/pengaturan_zyacbt" class="btn btn-xs btn-default btn-block" style="border-radius: 6px; font-weight: 600;">
							<i class="fa fa-pencil"></i> Ubah Pengaturan Sekolah
						</a>
					</div>
				</div>
			</div>
		</div>
    </div>
</section>

<script lang="javascript">
	$(function(){
        $('#btn-cetak-kartu').click(function(){
			var grup_id = $('#group').val();
			window.open("<?php echo site_url().'/'.$url; ?>/cetak_kartu/" + grup_id, '_blank');
		});
    });
</script>
