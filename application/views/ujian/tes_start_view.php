<div class="container cbt-card-animated" style="margin-top: 25px; margin-bottom: 50px;">
    <div class="row">
        <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12">
            <!-- Modern Test Confirmation Card -->
            <div style="background: #ffffff; border-radius: 20px; box-shadow: 0 20px 40px -10px rgba(15, 23, 42, 0.12), 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; overflow: hidden;">
                <!-- Header with SMKN 1 Kupang Barat Gradient -->
                <div style="background: linear-gradient(135deg, #03045e 0%, #0077b6 60%, #00b4d8 100%); padding: clamp(16px, 4vw, 24px) 24px; color: #ffffff;">
                    <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
                        <img src="<?php echo base_url(); ?>public/images/logo_smk.png" alt="Logo SMKN 1 Kupang Barat" 
                             style="width: 46px; height: 46px; object-fit: contain; filter: drop-shadow(0 2px 8px rgba(0, 180, 216, 0.45)); flex-shrink: 0;" />
                        <div>
                            <h3 style="margin: 0; font-size: clamp(16px, 3vw, 18px); font-weight: 800; letter-spacing: -0.3px;">Konfirmasi Pelaksanaan Tes</h3>
                            <p style="margin: 0; font-size: 12px; color: #e0f2fe;">SMK Negeri 1 Kupang Barat &bull; Standar TKA Pusmendik</p>
                        </div>
                    </div>
                </div>

                <div style="padding: 28px;">
                    <!-- Error / Notice Box -->
                    <div id="form-pesan" style="margin-bottom: 18px;"></div>

                    <?php echo form_open($url.'/mulai_tes', 'id="form-konfirmasi-tes"'); ?>
                        <input type="hidden" name="tes-id" id="tes-id" value="<?php if(!empty($tes_id)){ echo $tes_id; } ?>">

                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; overflow: hidden; margin-bottom: 24px;">
                            <table class="table" style="margin-bottom: 0;">
                                <tbody>
                                    <tr style="border-bottom: 1px solid #e2e8f0;">
                                        <td style="width: 35%; padding: 14px 18px; font-weight: 600; color: #64748b; font-size: 13px;">
                                            <i class="fa fa-user" style="color: #2563eb; margin-right: 6px;"></i> Nama Lengkap Peserta
                                        </td>
                                        <td style="padding: 14px 18px; font-weight: 800; color: #0f172a; font-size: 14px;">
                                            <?php if(!empty($nama)){ echo $nama; } ?>
                                        </td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid #e2e8f0;">
                                        <td style="padding: 14px 18px; font-weight: 600; color: #64748b; font-size: 13px;">
                                            <i class="fa fa-book" style="color: #2563eb; margin-right: 6px;"></i> Mata Pelajaran / Tes
                                        </td>
                                        <td style="padding: 14px 18px; font-weight: 800; color: #0f172a; font-size: 14px;">
                                            <span style="background: #dbeafe; color: #1e40af; padding: 4px 12px; border-radius: 8px;">
                                                <?php if(!empty($tes_nama)){ echo $tes_nama; } ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid #e2e8f0;">
                                        <td style="padding: 14px 18px; font-weight: 600; color: #64748b; font-size: 13px;">
                                            <i class="fa fa-clock-o" style="color: #2563eb; margin-right: 6px;"></i> Alokasi Waktu
                                        </td>
                                        <td style="padding: 14px 18px; font-weight: 700; color: #0f172a; font-size: 13px;">
                                            <?php if(!empty($tes_waktu)){ echo $tes_waktu; } ?>
                                        </td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid #e2e8f0;">
                                        <td style="padding: 14px 18px; font-weight: 600; color: #64748b; font-size: 13px;">
                                            <i class="fa fa-check-circle-o" style="color: #2563eb; margin-right: 6px;"></i> Poin Dasar / Poin Maks
                                        </td>
                                        <td style="padding: 14px 18px; font-weight: 700; color: #0f172a; font-size: 13px;">
                                            <?php if(!empty($tes_poin)){ echo $tes_poin; }else{ echo '0'; } ?> / <?php if(!empty($tes_max_score)){ echo $tes_max_score; }else{ echo '0'; } ?>
                                        </td>
                                    </tr>
                                    <?php if(!empty($tes_token) && strpos($tes_token, 'input') !== false): ?>
                                    <tr>
                                        <td style="padding: 14px 18px; font-weight: 600; color: #64748b; font-size: 13px; vertical-align: middle;">
                                            <i class="fa fa-key" style="color: #eab308; margin-right: 6px;"></i> Token Ujian
                                        </td>
                                        <td style="padding: 14px 18px; vertical-align: middle;">
                                            <?php if(strpos($tes_token, 'type="hidden"') !== false): ?>
                                                <?php echo $tes_token; ?>
                                                <span class="text-muted" style="font-size: 12px;">(Tanpa Token)</span>
                                            <?php else: ?>
                                                <div style="max-width: 260px;">
                                                    <input type="text" name="token" id="token" class="form-control" placeholder="Ketik token dari pengawas" autocomplete="off"
                                                        style="text-transform: uppercase; font-weight: 800; letter-spacing: 2px; height: 42px; border-radius: 8px; font-size: 15px; border: 2px solid #93c5fd; text-align: center;" required>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Action Buttons -->
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 24px;">
                            <a href="<?php echo site_url().'/'.$url; ?>/" class="btn btn-default" style="border-radius: 10px; padding: 10px 20px; font-weight: 600; color: #64748b;">
                                <i class="fa fa-arrow-left" style="margin-right: 6px;"></i> Kembali ke Daftar
                            </a>
                            <button type="submit" id="btn-tambah-simpan" class="btn btn-primary" 
                                style="border-radius: 10px; padding: 12px 32px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; font-size: 15px; font-weight: 700; box-shadow: 0 8px 18px -4px rgba(16, 185, 129, 0.45); cursor: pointer;">
                                <i class="fa fa-play-circle" style="margin-right: 8px;"></i> Mulai Kerjakan Ujian
                            </button>
                        </div>
                    <?php echo form_close(); ?>
                </div>

                <!-- Footer Signature -->
                <div style="background: #f8fafc; padding: 14px 28px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #64748b;">
                    <span><i class="fa fa-clock-o text-blue"></i> Waktu pengerjaan akan mulai dihitung mundur setelah Anda menekan tombol Mulai.</span>
                    <span>Hak Cipta &copy; <b>Vicky Koroh</b></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        if($('#token').length) {
            $('#token').focus();
        }

        $('#form-konfirmasi-tes').submit(function(e){
            e.preventDefault();
            $("#modal-proses").modal('show');
            $.ajax({
                url: "<?php echo site_url().'/'.$url; ?>/mulai_tes",
                type: "POST",
                data: $('#form-konfirmasi-tes').serialize(),
                cache: false,
                timeout: 60000,
                dataType: "json",
                success: function(obj){
                    if(obj.status == 1){
                        $("#modal-proses").modal('hide');
                        $('#form-pesan').html('');
                        window.location.href = "<?php echo site_url(); ?>/tes_kerjakan/index/" + obj.tes_id;
                    } else if(obj.status == 2){
                        window.location.href = "<?php echo site_url().'/'.$url; ?>/";
                    } else {
                        $("#modal-proses").modal('hide');
                        $('#form-pesan').html(
                            '<div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 12px 16px; border-radius: 10px; font-size: 13px; font-weight: 600;">' +
                            '<i class="fa fa-exclamation-triangle" style="margin-right: 8px;"></i>' + (obj.pesan || 'Gagal memulai tes.') +
                            '</div>'
                        );
                    }
                },
                statusCode: {
                    500: function(respon) {
                        $("#modal-proses").modal('hide');
                        $('#form-pesan').html(
                            '<div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 12px 16px; border-radius: 10px; font-size: 13px; font-weight: 600;">' +
                            '<i class="fa fa-exclamation-triangle" style="margin-right: 8px;"></i> Terjadi kesalahan pada persiapan Tes. Silahkan hubungi petugas.' +
                            '</div>'
                        );
                    }
                },
                error: function(xmlhttprequest, textstatus, message) {
                    $("#modal-proses").modal('hide');
                    if(textstatus === "timeout") {
                        alert("Gagal menyiapkan Tes karena timeout, halaman akan dimuat ulang.");
                        window.location.reload();
                    } else {
                        $('#form-pesan').html(
                            '<div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 12px 16px; border-radius: 10px; font-size: 13px; font-weight: 600;">' +
                            '<i class="fa fa-exclamation-triangle" style="margin-right: 8px;"></i> Terjadi kendala komunikasi server.' +
                            '</div>'
                        );
                    }
                }
            });
            return false;
        });
    });
</script>
