<div class="container cbt-card-animated" style="margin-top: 20px; margin-bottom: 50px;">
    <!-- Modern Header Banner -->
    <div style="background: linear-gradient(135deg, #0f2b48 0%, #1e3a5f 50%, #2563eb 100%); border-radius: 20px; padding: 26px 30px; color: #ffffff; box-shadow: 0 10px 25px -5px rgba(15, 43, 72, 0.35); margin-bottom: 25px; position: relative; overflow: hidden;">
        <div style="position: absolute; right: -30px; top: -30px; width: 180px; height: 180px; background: radial-gradient(circle, rgba(255,255,255,0.12) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>
        
        <div class="row" style="position: relative; z-index: 2;">
            <div class="col-md-8 col-sm-7 col-xs-12">
                <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(8px); padding: 5px 14px; border-radius: 20px; font-size: 11px; font-weight: 700; margin-bottom: 12px; letter-spacing: 0.5px;">
                    <span style="width: 8px; height: 8px; border-radius: 50%; background: #34d399; display: inline-block;"></span>
                    PORTAL ASESMEN SISWA RESMI
                </div>
                <h2 style="margin: 0 0 8px 0; font-size: 24px; font-weight: 800; letter-spacing: -0.5px;">
                    Selamat Datang, <?php if(!empty($nama)){ echo $nama; }else{ echo 'Peserta Ujian'; } ?>!
                </h2>
                <p style="margin: 0; font-size: 13px; color: #cbd5e1; line-height: 1.5;">
                    Kelas / Rombel: <span class="label" style="background: rgba(255,255,255,0.2); font-size: 12px; padding: 3px 10px; border-radius: 12px;"><?php if(!empty($group)){ echo $group; }else{ echo 'Peserta'; } ?></span>
                    &bull; Pastikan membaca tata tertib dan petunjuk soal dengan seksama.
                </p>
            </div>
            <div class="col-md-4 col-sm-5 col-xs-12 text-right" style="margin-top: 10px;">
                <div style="background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(10px); padding: 12px 18px; border-radius: 14px; display: inline-block; text-align: left; border: 1px solid rgba(255,255,255,0.15);">
                    <div style="font-size: 11px; color: #94a3b8; font-weight: 700; text-transform: uppercase;">
                        <i class="fa fa-info-circle text-yellow"></i> Standar Asesmen
                    </div>
                    <div style="font-size: 14px; font-weight: 700; color: #ffffff; margin-top: 2px;">
                        TKA Kemdikbud Modern
                    </div>
                    <div style="font-size: 11px; color: #38bdf8;">
                        PG • Benar/Salah • Uraian
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Alert Box -->
    <?php if(!empty($informasi)): ?>
        <div style="background: #ffffff; border-left: 5px solid #2563eb; border-radius: 14px; padding: 16px 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.04); margin-bottom: 25px;">
            <div style="display: flex; align-items: flex-start; gap: 14px;">
                <div style="width: 36px; height: 36px; border-radius: 10px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
                    <i class="fa fa-bullhorn"></i>
                </div>
                <div>
                    <h4 style="margin: 0 0 4px 0; font-size: 15px; font-weight: 700; color: #0f172a;">Pengumuman & Instruksi</h4>
                    <div style="font-size: 13px; color: #475569; line-height: 1.6;">
                        <?php echo $informasi; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Card: Daftar Tes Tersedia -->
    <div style="background: #ffffff; border-radius: 18px; box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04); border: 1px solid #e2e8f0; overflow: hidden;">
        <div style="padding: 20px 24px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background: #f8fafc;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 34px; height: 34px; border-radius: 8px; background: #dcfce7; color: #059669; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                    <i class="fa fa-list-alt"></i>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 16px; font-weight: 800; color: #0f172a;">Daftar Ujian / Asesmen Aktif</h3>
                    <p style="margin: 0; font-size: 12px; color: #64748b;">Silahkan pilih ujian yang tersedia pada daftar di bawah ini</p>
                </div>
            </div>
            <button onclick="$('#table-tes').DataTable().ajax.reload();" class="btn btn-sm btn-default" style="border-radius: 8px; font-weight: 600; color: #475569;" title="Muat Ulang Daftar">
                <i class="fa fa-refresh"></i> Refresh
            </button>
        </div>

        <div style="padding: 20px 24px;">
            <div class="table-responsive">
                <table id="table-tes" class="table table-hover" style="width: 100%; border-collapse: separate; border-spacing: 0;">
                    <thead>
                        <tr style="background: #f1f5f9; color: #334155;">
                            <th style="border-top: none; border-bottom: 2px solid #cbd5e1; font-size: 12px; font-weight: 700; text-transform: uppercase;">No.</th>
                            <th class="all" style="border-top: none; border-bottom: 2px solid #cbd5e1; font-size: 12px; font-weight: 700; text-transform: uppercase;">Nama Asesmen / Tes</th>
                            <th style="border-top: none; border-bottom: 2px solid #cbd5e1; font-size: 12px; font-weight: 700; text-transform: uppercase;">Mulai</th>
                            <th style="border-top: none; border-bottom: 2px solid #cbd5e1; font-size: 12px; font-weight: 700; text-transform: uppercase;">Selesai</th>
                            <th style="border-top: none; border-bottom: 2px solid #cbd5e1; font-size: 12px; font-weight: 700; text-transform: uppercase;">Status</th>
                            <th class="all" style="border-top: none; border-bottom: 2px solid #cbd5e1; font-size: 12px; font-weight: 700; text-transform: uppercase; text-align: center;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 13px; color: #1e293b;">
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Card Footer Vicky Koroh Signature -->
        <div style="background: #f8fafc; padding: 14px 24px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #64748b;">
            <span><i class="fa fa-shield text-green"></i> Sesi pengerjaan terlindungi & tersimpan otomatis per butir soal.</span>
            <span>Hak Cipta &copy; <b>Vicky Koroh</b></span>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $('#table-tes').DataTable({
            "paging": true,
            "iDisplayLength": 25,
            "bProcessing": true,
            "bServerSide": true, 
            "searching": false,
            "aoColumns": [
                {"bSearchable": false, "bSortable": false, "sWidth":"35px"},
                {"bSearchable": false, "bSortable": false},
                {"bSearchable": false, "bSortable": false, "sWidth":"160px"},
                {"bSearchable": false, "bSortable": false, "sWidth":"160px"},
                {"bSearchable": false, "bSortable": false, "sWidth":"110px"},
                {"bSearchable": false, "bSortable": false, "sWidth":"100px", "sClass": "text-center"}
            ],
            "sAjaxSource": "<?php echo site_url().'/'.$url; ?>/get_datatable/",
            "autoWidth": false,
            "responsive": true,
            "language": {
                "emptyTable": "Tidak ada tes yang sedang aktif untuk kelompok Anda.",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ tes",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 tes",
                "paginate": {
                    "first": "Awal",
                    "last": "Akhir",
                    "next": "Lanjut",
                    "previous": "Sebelumnya"
                }
            }
        });   
    });
</script>
