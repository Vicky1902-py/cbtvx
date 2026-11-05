<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<title>Cetak Kartu Peserta CBT - <?php echo htmlspecialchars($sekolah['nama']); ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="<?php echo base_url('public/images/favicon.png'); ?>">
	<!-- Google Fonts for Print & UI -->
	<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Times+New+Roman&display=swap" rel="stylesheet">
	<style type="text/css">
		* {
			box-sizing: border-box;
			-webkit-print-color-adjust: exact !important;
			print-color-adjust: exact !important;
		}

		body {
			background: #e2e8f0;
			font-family: 'Plus Jakarta Sans', Arial, sans-serif;
			margin: 0;
			padding: 0;
			color: #000000;
		}

		/* Toolbar Pengatur Cetak */
		.no-print-toolbar {
			background: #001233;
			color: #ffffff;
			padding: 12px 24px;
			position: sticky;
			top: 0;
			z-index: 1000;
			display: flex;
			justify-content: space-between;
			align-items: center;
			box-shadow: 0 4px 15px rgba(0,0,0,0.25);
		}

		.btn-cetak {
			background: linear-gradient(135deg, #0077b6 0%, #0096c7 100%);
			color: #ffffff;
			border: none;
			padding: 8px 22px;
			font-weight: 700;
			font-size: 14px;
			border-radius: 8px;
			cursor: pointer;
			display: inline-flex;
			align-items: center;
			gap: 8px;
			transition: all 0.2s ease;
		}

		.btn-cetak:hover {
			background: #005a8c;
		}

		.btn-tutup {
			background: rgba(255, 255, 255, 0.15);
			color: #ffffff;
			border: 1px solid rgba(255, 255, 255, 0.25);
			padding: 8px 18px;
			font-weight: 600;
			font-size: 13px;
			border-radius: 8px;
			cursor: pointer;
			text-decoration: none;
		}

		/* Lembar Kertas Preview */
		.page-canvas {
			width: 210mm;
			min-height: 297mm;
			margin: 20px auto;
			background: #ffffff;
			padding: 10mm;
			box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
			border-radius: 4px;
		}

		/* Grid Kartu Peserta (2 Kolom Pas Kertas A4) */
		.kartu-grid {
			display: grid;
			grid-template-columns: repeat(2, 1fr);
			gap: 8mm 6mm;
		}

		/* Struktur 1 Kartu Peserta */
		.kartu-peserta {
			border: 1.5px solid #000000;
			border-radius: 8px;
			padding: 8px 10px;
			background: #ffffff;
			position: relative;
			page-break-inside: avoid;
			break-inside: avoid;
			min-height: 72mm;
			display: flex;
			flex-direction: column;
			justify-content: space-between;
			outline: 1px dashed #cbd5e1;
			outline-offset: 3px;
		}

		/* KOP RESMI SEKOLAH */
		.kop-container {
			display: flex;
			align-items: center;
			justify-content: space-between;
			padding-bottom: 4px;
			border-bottom: 2px solid #000000;
			position: relative;
		}

		.kop-container::after {
			content: '';
			position: absolute;
			left: 0;
			right: 0;
			bottom: -4px;
			border-bottom: 1px solid #000000;
		}

		.kop-logo {
			width: 44px;
			height: 48px;
			object-fit: contain;
		}

		.kop-text {
			flex: 1;
			text-align: center;
			padding: 0 6px;
			line-height: 1.15;
		}

		.kop-prov {
			font-size: 8.5pt;
			font-weight: 700;
			text-transform: uppercase;
			letter-spacing: 0.2px;
		}

		.kop-dinas {
			font-size: 8pt;
			font-weight: 700;
			text-transform: uppercase;
		}

		.kop-sekolah {
			font-size: 9.5pt;
			font-weight: 800;
			text-transform: uppercase;
			color: #000000;
			margin: 1px 0;
		}

		.kop-alamat {
			font-size: 6.5pt;
			color: #333333;
			font-weight: 500;
		}

		/* JUDUL KARTU */
		.kartu-title {
			text-align: center;
			font-size: 8pt;
			font-weight: 800;
			margin: 7px 0 5px 0;
			letter-spacing: 0.4px;
			text-transform: uppercase;
			border-bottom: 1px dotted #999;
			padding-bottom: 2px;
		}

		/* BIODATA & FOTO */
		.kartu-body {
			display: flex;
			gap: 8px;
			margin-top: 2px;
			flex: 1;
		}

		/* Kotak Pas Foto 3x4 */
		.foto-wrapper {
			width: 25mm;
			height: 32mm;
			border: 1px solid #000000;
			border-radius: 4px;
			overflow: hidden;
			background: #f8fafc;
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			flex-shrink: 0;
			text-align: center;
		}

		.foto-img {
			width: 100%;
			height: 100%;
			object-fit: cover;
		}

		.foto-placeholder {
			font-size: 7pt;
			color: #64748b;
			line-height: 1.2;
			padding: 4px;
		}

		/* Tabel Biodata Siswa */
		.table-bio {
			flex: 1;
			font-size: 7.5pt;
			border-collapse: collapse;
			width: 100%;
		}

		.table-bio td {
			padding: 1.5px 0;
			vertical-align: top;
		}

		.table-bio .label-col {
			width: 82px;
			color: #222;
			font-weight: 600;
		}

		.table-bio .separator-col {
			width: 6px;
			text-align: center;
		}

		.table-bio .val-col {
			font-weight: 700;
			color: #000000;
		}

		.badge-cred {
			background: #f1f5f9;
			padding: 1px 4px;
			border-radius: 3px;
			border: 1px solid #cbd5e1;
			font-family: monospace;
			font-size: 8pt;
		}

		/* FOOTER KARTU & TANDA TANGAN KEPSEK */
		.kartu-footer {
			margin-top: 5px;
			display: flex;
			justify-content: space-between;
			align-items: flex-end;
		}

		.catatan-ujian {
			font-size: 5.5pt;
			color: #444;
			max-width: 155px;
			line-height: 1.25;
		}

		.ttd-box {
			text-align: center;
			font-size: 6.8pt;
			min-width: 120px;
		}

		.ttd-img-seal {
			height: 38px;
			max-width: 110px;
			object-fit: contain;
			margin: -4px auto -2px auto;
			display: block;
		}

		.ttd-nama-kepsek {
			font-weight: 800;
			text-decoration: underline;
			font-size: 7.2pt;
			margin-top: 1px;
		}

		.ttd-nip-kepsek {
			font-size: 6.2pt;
			color: #333;
		}

		.watermark-copyright {
			font-size: 5.5pt;
			color: #94a3b8;
			text-align: right;
			margin-top: 2px;
			border-top: 0.5px solid #eee;
			padding-top: 1px;
		}

		/* PENGATURAN CETAK PRINTER */
		@media print {
			body {
				background: #ffffff !important;
				margin: 0 !important;
				padding: 0 !important;
			}

			.no-print-toolbar {
				display: none !important;
			}

			.page-canvas {
				width: 100% !important;
				min-height: auto !important;
				margin: 0 !important;
				padding: 6mm 8mm !important;
				box-shadow: none !important;
				border-radius: 0 !important;
			}

			.kartu-grid {
				gap: 6mm 5mm !important;
			}

			.kartu-peserta {
				outline: 1px dashed #777 !important;
			}
		}
	</style>
</head>
<body>

	<!-- Toolbar Kontrol Cetak (Tidak ikut tercetak) -->
	<div class="no-print-toolbar">
		<div style="display: flex; align-items: center; gap: 14px;">
			<img src="<?php echo $sekolah['logo_sekolah']; ?>" style="width: 32px; height: 32px; object-fit: contain;">
			<div>
				<div style="font-size: 14px; font-weight: 800;"><?php echo htmlspecialchars($sekolah['nama']); ?></div>
				<div style="font-size: 11px; color: #94a3b8;">Cetak Kartu Login CBT &bull; <?php echo htmlspecialchars($group_nama); ?> (<?php echo count($users); ?> Peserta)</div>
			</div>
		</div>

		<div style="display: flex; gap: 10px; align-items: center;">
			<button type="button" class="btn-cetak" onclick="window.print()">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
				<span>Cetak Sekarang (Print)</span>
			</button>
			<a href="<?php echo site_url(); ?>/manager/peserta_kartu" class="btn-tutup">Kembali</a>
		</div>
	</div>

	<!-- Canvas Halaman Kartu Peserta -->
	<div class="page-canvas">
		<?php if(!empty($users)): ?>
			<div class="kartu-grid">
				<?php foreach($users as $idx => $u): ?>
					<?php
						// Cek keberadaan file foto siswa
						$foto_path = '';
						$exts = array('jpg', 'jpeg', 'png', 'webp');
						foreach($exts as $ext){
							$test_file = './public/uploads/foto_siswa/' . $u->user_name . '.' . $ext;
							if(file_exists($test_file)){
								$foto_path = base_url('public/uploads/foto_siswa/' . $u->user_name . '.' . $ext);
								break;
							}
						}

						// Nama Group
						$nama_grup = isset($grup_map[$u->user_grup_id]) ? $grup_map[$u->user_grup_id] : $group_nama;
					?>
					<div class="kartu-peserta">
						<!-- KOP RESMI RESMI PEMPROV & SEKOLAH -->
						<div class="kop-container">
							<img src="<?php echo $sekolah['logo_provinsi']; ?>" alt="Logo Pemprov NTT" class="kop-logo" />
							<div class="kop-text">
								<div class="kop-prov"><?php echo htmlspecialchars($sekolah['provinsi']); ?></div>
								<div class="kop-dinas"><?php echo htmlspecialchars($sekolah['dinas']); ?></div>
								<div class="kop-sekolah"><?php echo htmlspecialchars($sekolah['nama']); ?></div>
								<div class="kop-alamat"><?php echo htmlspecialchars($sekolah['alamat']); ?>, <?php echo htmlspecialchars($sekolah['kecamatan']); ?></div>
							</div>
							<img src="<?php echo $sekolah['logo_sekolah']; ?>" alt="Logo SMKN 1 Kupang Barat" class="kop-logo" />
						</div>

						<!-- JUDUL KARTU -->
						<div class="kartu-title">
							KARTU PESERTA UJIAN BERBASIS KOMPUTER (CBT)
						</div>

						<!-- KONTEN BIODATA & FOTO SISWA -->
						<div class="kartu-body">
							<!-- Pas Foto Siswa (Upload ZIP / Satuan) -->
							<div class="foto-wrapper">
								<?php if(!empty($foto_path)): ?>
									<img src="<?php echo $foto_path; ?>" alt="Foto <?php echo htmlspecialchars($u->user_name); ?>" class="foto-img" />
								<?php else: ?>
									<div class="foto-placeholder">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 2px;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
										<div>PAS FOTO<br>3 x 4</div>
									</div>
								<?php endif; ?>
							</div>

							<!-- Tabel Biodata Peserta -->
							<table class="table-bio">
								<tr>
									<td class="label-col">No. Peserta / NISN</td>
									<td class="separator-col">:</td>
									<td class="val-col"><span class="badge-cred"><?php echo htmlspecialchars($u->user_name); ?></span></td>
								</tr>
								<tr>
									<td class="label-col">Nama Lengkap</td>
									<td class="separator-col">:</td>
									<td class="val-col"><?php echo htmlspecialchars($u->user_firstname); ?></td>
								</tr>
								<tr>
									<td class="label-col">Kelas / Rombel</td>
									<td class="separator-col">:</td>
									<td class="val-col"><?php echo htmlspecialchars($nama_grup); ?></td>
								</tr>
								<tr>
									<td class="label-col">Sesi / Ruang</td>
									<td class="separator-col">:</td>
									<td class="val-col"><?php echo !empty($u->user_detail) ? htmlspecialchars($u->user_detail) : 'Sesi 1 / Lab Komputer'; ?></td>
								</tr>
								<tr>
									<td class="label-col">Password / PIN</td>
									<td class="separator-col">:</td>
									<td class="val-col"><span class="badge-cred"><?php echo htmlspecialchars($u->user_password); ?></span></td>
								</tr>
							</table>
						</div>

						<!-- FOOTER TANDA TANGAN KEPALA SEKOLAH -->
						<div class="kartu-footer">
							<div class="catatan-ujian">
								<b>Petunjuk:</b><br>
								1. Bawa kartu ini saat ujian.<br>
								2. Jagalah kerahasiaan kata sandi akun ujian Anda.
							</div>

							<div class="ttd-box">
								<div><?php echo htmlspecialchars($sekolah['titimangsa']); ?>, <?php echo date('d F Y'); ?></div>
								<div>Kepala Sekolah,</div>
								<?php if(!empty($sekolah['ttd_kepsek'])): ?>
									<img src="<?php echo $sekolah['ttd_kepsek']; ?>" alt="Stempel & TTD" class="ttd-img-seal" />
								<?php else: ?>
									<div style="height: 38px;"></div>
								<?php endif; ?>
								<div class="ttd-nama-kepsek"><?php echo htmlspecialchars($sekolah['kepsek_nama']); ?></div>
								<div class="ttd-nip-kepsek">NIP. <?php echo htmlspecialchars($sekolah['kepsek_nip']); ?></div>
							</div>
						</div>

						<div class="watermark-copyright">
							CBT SMKN 1 Kupang Barat &bull; Hak Cipta &copy; Vicky Koroh
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php else: ?>
			<div style="text-align: center; padding: 60px 20px; color: #64748b;">
				<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-bottom: 12px;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
				<h3 style="margin: 0 0 6px 0; color: #0f172a;">Data Peserta Tidak Ditemukan</h3>
				<p>Tidak ada siswa pada rombel / group yang dipilih. Silahkan periksa kembali data peserta.</p>
			</div>
		<?php endif; ?>
	</div>

</body>
</html>
