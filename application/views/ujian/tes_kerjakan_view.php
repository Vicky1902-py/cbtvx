<div class="row">
    <!-- Top Action Bar (Mata Uji, Timer, Daftar Soal Button, Font Resizer) -->
    <div class="col-xs-12" style="margin-bottom: 15px;">
        <div style="background: #ffffff; border-radius: 10px; padding: 12px 20px; border: 1px solid #e2e8f0; box-shadow: 0 2px 6px rgba(0,0,0,0.04); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <!-- Nama Tes -->
            <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                <span style="background: #e0f2fe; color: #0077b6; padding: 6px 12px; border-radius: 8px; font-weight: 700; font-size: 13px; border: 1px solid rgba(0, 180, 216, 0.3);">
                    <i class="fa fa-book"></i> MATA UJI
                </span>
                <span style="font-weight: 800; font-size: clamp(14px, 2.5vw, 16px); color: #001233;">
                    <?php if(!empty($tes_name)){ echo $tes_name; }else{ echo 'Ujian'; } ?>
                </span>
            </div>

            <!-- Timer & Controls -->
            <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                <!-- Ukuran Font -->
                <div style="display: inline-flex; align-items: center; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; padding: 3px 6px;">
                    <span style="font-size: 11px; font-weight: 600; color: #64748b; margin-right: 4px;" class="hidden-xs">Font:</span>
                    <button type="button" class="tka-zoom-btn" onclick="zoomkecil()" title="Ukuran Font Kecil" style="font-size: 12px; margin-right: 2px;">A-</button>
                    <button type="button" class="tka-zoom-btn" onclick="zoomnormal()" title="Ukuran Font Normal" style="font-size: 13px; margin-right: 2px;">A</button>
                    <button type="button" class="tka-zoom-btn" onclick="zoombesar()" title="Ukuran Font Besar" style="font-size: 15px;">A+</button>
                </div>

                <!-- Timer Sisa Waktu -->
                <div class="tka-timer-box" id="tka-timer-display" title="Sisa Waktu Pengerjaan" style="border-radius: 8px; font-size: 15px; padding: 6px 12px;">
                    <i class="fa fa-clock-o"></i> <span id="sisa-waktu-digital">--:--:--</span>
                </div>

                <!-- Tombol Daftar Soal Modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-daftar-soal" style="background: linear-gradient(135deg, #0077b6 0%, #0096c7 100%); border: none; font-weight: 700; padding: 7px 14px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0, 119, 182, 0.3);">
                    <i class="fa fa-th"></i> Daftar Soal
                </button>
            </div>
        </div>
    </div>

    <!-- Kolom Soal Utama -->
    <div class="col-xs-12">
        <?php echo form_open('tes_kerjakan/simpan_jawaban','id="form-kerjakan"')?>
            <input type="hidden" name="tes-id" id="tes-id" value="<?php if(!empty($tes_id)){ echo $tes_id; } ?>">
            <input type="hidden" name="tes-user-id" id="tes-user-id" value="<?php if(!empty($tes_user_id)){ echo $tes_user_id; } ?>">
            <input type="hidden" name="tes-soal-id" id="tes-soal-id" value="<?php if(!empty($tes_soal_id)){ echo $tes_soal_id; } ?>">
            <input type="hidden" name="tes-soal-nomor" id="tes-soal-nomor" value="<?php if(!empty($tes_soal_nomor)){ echo $tes_soal_nomor; } ?>">
            <input type="hidden" name="tes-soal-jml" id="tes-soal-jml" value="<?php if(!empty($tes_soal_jml)){ echo $tes_soal_jml; } ?>">
            <input type="hidden" name="tes-soal-ragu" id="tes-soal-ragu" value="<?php if(!empty($tes_ragu)){ echo $tes_ragu; } ?>">

            <div class="tka-card-soal">
                <!-- Header Soal -->
                <div class="tka-card-header">
                    <div>
                        <span class="tka-soal-pill" id="badge-soal-nomor">
                            Soal No. <span id="judul-soal"><?php if(!empty($tes_soal_nomor)){ echo $tes_soal_nomor; }else{ echo '1'; } ?></span> dari <?php if(!empty($tes_soal_jml)){ echo $tes_soal_jml; } ?>
                        </span>
                        <span class="tka-tipe-pill tka-tipe-pg" id="badge-soal-tipe">
                            Pilihan Ganda
                        </span>
                    </div>

                    <div style="color: #64748b; font-size: 13px;">
                        <i class="fa fa-info-circle"></i> Klik pada opsi pilihan untuk menjawab secara instan.
                    </div>
                </div>

                <!-- Body Soal -->
                <div class="tka-card-body">
                    <div id="isi-tes-soal" style="font-size: 16px; line-height: 1.7;">
                        <?php if(!empty($tes_soal)){ echo $tes_soal; } ?>
                    </div>
                </div>

                <!-- Footer Navigasi Standar ANBK -->
                <div class="tka-card-footer">
                    <!-- Tombol Sebelumnya -->
                    <div>
                        <button type="button" class="tka-btn-nav tka-btn-prev <?php if(empty($tes_soal_nomor) || $tes_soal_nomor <= 1){ echo 'hide'; } ?>" id="btn-sebelumnya">
                            <i class="fa fa-chevron-left"></i> Soal Sebelumnya
                        </button>
                    </div>

                    <!-- Tombol Ragu-Ragu -->
                    <div>
                        <button type="button" class="tka-btn-nav tka-btn-ragu" id="btn-ragu" onclick="ragu()">
                            <input type="checkbox" style="width:16px; height:16px; cursor: pointer; vertical-align: middle; margin: 0 4px 0 0;" name="btn-ragu-checkbox" id="btn-ragu-checkbox" <?php if(!empty($tes_ragu)){ echo "checked"; } ?> />
                            <span>Ragu - Ragu</span>
                        </button>
                    </div>

                    <!-- Tombol Selanjutnya / Hentikan -->
                    <div>
                        <button type="button" class="tka-btn-nav tka-btn-next" id="btn-selanjutnya">
                            Soal Selanjutnya <i class="fa fa-chevron-right"></i>
                        </button>
                        <button type="button" class="tka-btn-nav tka-btn-selesai hide" id="btn-selesai-inline" onclick="hentikan_tes()">
                            <i class="fa fa-check-square-o"></i> Selesai Ujian
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Daftar Soal (Popup Drawer Grid Standar TKA / ANBK) -->
<div class="modal fade" id="modal-daftar-soal" tabindex="-1" role="dialog" aria-labelledby="daftarSoalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
            <div class="modal-header" style="background: #0f2b48; color: #ffffff; padding: 16px 20px;">
                <button type="button" class="close" data-dismiss="modal" style="color: #ffffff; opacity: 0.9; font-size: 24px;">&times;</button>
                <h4 class="modal-title" id="daftarSoalLabel"><i class="fa fa-th"></i> <b>Daftar Seluruh Soal Ujian</b></h4>
            </div>
            <div class="modal-body" style="padding: 20px; background: #f8fafc;">
                <!-- Keterangan Warna / Status -->
                <div style="background: #ffffff; padding: 12px 16px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 20px; display: flex; gap: 20px; flex-wrap: wrap; align-items: center; justify-content: center;">
                    <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600;">
                        <span style="width: 22px; height: 22px; background: #2563eb; border-radius: 4px; display: inline-block;"></span>
                        <span>Sudah Dijawab</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600;">
                        <span style="width: 22px; height: 22px; background: #f59e0b; border-radius: 4px; display: inline-block;"></span>
                        <span>Ragu - Ragu</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600;">
                        <span style="width: 22px; height: 22px; background: #ffffff; border: 2px solid #cbd5e1; border-radius: 4px; display: inline-block;"></span>
                        <span>Belum Dijawab</span>
                    </div>
                </div>

                <!-- Grid Tombol Nomor Soal -->
                <div id="grid-container-soal" style="max-height: 400px; overflow-y: auto; padding: 5px;">
                    <div class="tka-grid-soal">
                        <?php if(!empty($tes_daftar_soal)){ echo $tes_daftar_soal; } ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="background: #ffffff; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-danger" onclick="$('#modal-daftar-soal').modal('hide'); hentikan_tes();" style="font-weight: 700;">
                    <i class="fa fa-flag-checkered"></i> Akhiri & Kumpulkan Ujian
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hentikan / Selesai Ujian -->
<div class="modal fade" id="modal-hentikan" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalHentikanLabel" aria-hidden="true">
    <?php echo form_open($url.'/hentikan_tes','id="form-hentikan"'); ?>
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
            <div class="modal-header" style="background: #dc2626; color: #ffffff;">
                <button class="close" type="button" data-dismiss="modal" style="color: #ffffff; opacity: 0.9;">&times;</button>
                <h4 class="modal-title" id="modalHentikanLabel"><i class="fa fa-exclamation-triangle"></i> <b>Konfirmasi Selesai Ujian</b></h4>
            </div>
            <div class="modal-body" style="padding: 24px;">
                <div id="form-pesan-hentikan"></div>
                <div class="alert alert-warning" style="border-radius: 8px;">
                    <h4 style="margin-top: 0; font-size: 16px;"><i class="fa fa-warning"></i> Perhatian:</h4>
                    <p>Apakah Anda yakin ingin mengakhiri mata uji ini sekarang? Setelah tes dihentikan, Anda tidak dapat mengubah jawaban kembali.</p>
                </div>
                
                <div class="form-group">
                    <label>Nama Mata Uji:</label>
                    <input type="hidden" name="hentikan-tes-id" id="hentikan-tes-id" >
                    <input type="hidden" name="hentikan-tes-user-id" id="hentikan-tes-user-id" >
                    <input type="text" class="form-control" id="hentikan-tes-nama" name="hentikan-tes-nama" readonly style="font-weight: bold; background: #f8fafc;">
                </div>

                <div class="form-group">
                    <label>Rekap Status Jawaban:</label>
                    <input type="text" class="form-control" id="hentikan-dijawab" name="hentikan-dijawab" readonly style="background: #f8fafc; font-weight: bold; color: #2563eb;">
                </div>

                <div class="form-group" style="margin-top: 20px; background: #fef2f2; padding: 12px; border-radius: 6px; border: 1px solid #fecaca;">
                    <div class="checkbox" style="margin: 0;">
                        <label style="font-weight: 700; color: #991b1b; cursor: pointer;">
                            <input type="checkbox" id="hentikan-centang" name="hentikan-centang" value="1" style="width: 18px; height: 18px; margin-top: 1px;">
                            <span style="margin-left: 6px;">Saya telah selesai memeriksa seluruh jawaban dan siap mengakhiri tes.</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="background: #f8fafc;">
                <button type="button" class="btn btn-default" data-dismiss="modal">Kembali Periksa Soal</button>
                <button type="submit" id="btn-konfirmasi-hentikan" class="btn btn-danger" style="font-weight: 700;"><i class="fa fa-check-circle"></i> Ya, Akhiri Tes</button>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">
    var currentFontSize = 16;

    function zoombesar(){
        currentFontSize = Math.min(24, currentFontSize + 2);
        $('#isi-tes-soal').css("font-size", currentFontSize + "px");
    }

    function zoomnormal(){
        currentFontSize = 16;
        $('#isi-tes-soal').css("font-size", "16px");
    }

    function zoomkecil(){
        currentFontSize = Math.max(13, currentFontSize - 2);
        $('#isi-tes-soal').css("font-size", currentFontSize + "px");
    }

    /**
     * Memilih opsi jawaban (Pilihan Ganda / Benar-Salah) secara instan
     */
    function tkaPilihOpsi(element, jawabanId){
        // Tandai tampilan terpilih
        $(element).closest('.tka-options-container, .tka-bs-container').find('.tka-option-item, .tka-bs-btn').removeClass('selected');
        $(element).addClass('selected');

        // Check radio input
        var radioInput = $(element).find('input[type="radio"]');
        if(radioInput.length > 0){
            radioInput.prop('checked', true);
        } else {
            // fallback jika radio berada di dalam form
            $('input[name="soal-jawaban"][value="' + jawabanId + '"]').prop('checked', true);
        }

        // Simpan jawaban via AJAX
        simpanJawabanOtomatis();
    }

    function simpanJawabanOtomatis(){
        var nomorSoal = $('#tes-soal-nomor').val();
        
        $.ajax({
            url: "<?php echo site_url().'/'.$url; ?>/simpan_jawaban",
            type: "POST",
            data: $('#form-kerjakan').serialize(),
            cache: false,
            timeout: 10000,
            success: function(respon){
                var obj = $.parseJSON(respon);
                if(obj.status == 1){
                    // Update tampilan nomor pada daftar soal menjadi Biru (terjawab)
                    var btnSoal = $('#btn-soal-' + nomorSoal);
                    btnSoal.removeClass('btn-default btn-warning').addClass('btn-primary answered');
                    // notify_success(obj.pesan);
                } else if(obj.status == 2){
                    window.location.reload();
                } else {
                    notify_error(obj.pesan);
                }
            },
            error: function(xmlhttprequest, textstatus, message) {
                if(textstatus === "timeout") {
                    notify_error("Koneksi timeout saat menyimpan jawaban. Periksa jaringan.");
                } else {
                    notify_error("Gagal menyimpan: " + textstatus);
                }
            }
        });
    }

    function ragu(){
        $("#modal-proses").modal('show');
        var nomorSoal = $('#tes-soal-nomor').val();
        var soalId = $('#tes-soal-id').val();
        var statusRaguSekarang = $('#tes-soal-ragu').val();
        var statusRaguBaru = (statusRaguSekarang == 0) ? 1 : 0;

        $.ajax({
            url: '<?php echo site_url().'/'.$url; ?>/update_tes_soal_ragu/' + soalId + '/' + statusRaguBaru,
            type: "POST",
            cache: false,
            timeout: 5000,
            success: function(respon){
                var data = $.parseJSON(respon);
                if(data.data == 1){
                    $('#tes-soal-ragu').val(statusRaguBaru);
                    $('#btn-ragu-checkbox').prop("checked", statusRaguBaru == 1);
                    
                    var btnSoal = $('#btn-soal-' + nomorSoal);
                    if(statusRaguBaru == 1){
                        btnSoal.removeClass('btn-primary btn-default').addClass('btn-warning ragu');
                        notify_info('Soal ditandai Ragu-Ragu');
                    } else {
                        btnSoal.removeClass('btn-warning ragu');
                        // Cek apakah sudah dijawab atau belum
                        if(btnSoal.hasClass('answered')){
                            btnSoal.addClass('btn-primary');
                        } else {
                            btnSoal.addClass('btn-default');
                        }
                        notify_success('Tanda Ragu-Ragu dicabut');
                    }
                }
                $("#modal-proses").modal('hide');
            },
            error: function(xmlhttprequest, textstatus, message) {
                $("#modal-proses").modal('hide');
                notify_error("Gagal mengubah status ragu-ragu: " + textstatus);
            }
        });
    }

    function soal(tessoal_id){
        $("#modal-proses").modal('show');
        $('#modal-daftar-soal').modal('hide');

        $.ajax({
            url: '<?php echo site_url().'/'.$url; ?>/get_soal_by_tessoal/' + tessoal_id + '/' + $('#tes-user-id').val(),
            type: "POST",
            cache: false,
            timeout: 10000,
            success: function(respon){
                var data = $.parseJSON(respon);
                if(data.data == 1){
                    $('#tes-soal-id').val(data.tes_soal_id);
                    $('#tes-soal-nomor').val(data.tes_soal_nomor);
                    $('#isi-tes-soal').html(data.tes_soal);
                    $('#tes-soal-ragu').val(data.tes_ragu);
                    $('#judul-soal').html(data.tes_soal_nomor);

                    // Update Badge Tipe Soal
                    var tipeLabel = data.tes_soal_tipe_label || 'Pilihan Ganda';
                    $('#badge-soal-tipe').text(tipeLabel);
                    $('#badge-soal-tipe').removeClass('tka-tipe-pg tka-tipe-bs tka-tipe-essay tka-tipe-isian');
                    if(tipeLabel.indexOf('Benar') !== -1){
                        $('#badge-soal-tipe').addClass('tka-tipe-bs');
                    } else if(tipeLabel.indexOf('Uraian') !== -1 || tipeLabel.indexOf('Esai') !== -1){
                        $('#badge-soal-tipe').addClass('tka-tipe-essay');
                    } else if(tipeLabel.indexOf('Isian') !== -1){
                        $('#badge-soal-tipe').addClass('tka-tipe-isian');
                    } else {
                        $('#badge-soal-tipe').addClass('tka-tipe-pg');
                    }

                    // Status Ragu-ragu
                    $('#btn-ragu-checkbox').prop("checked", data.tes_ragu == 1);

                    // Navigasi Prev / Next
                    var tes_soal_nomor = parseInt(data.tes_soal_nomor);
                    var tes_soal_jml = parseInt($('#tes-soal-jml').val());

                    if(tes_soal_nomor <= 1){
                        $('#btn-sebelumnya').addClass('hide');
                    } else {
                        $('#btn-sebelumnya').removeClass('hide');
                    }

                    if(tes_soal_nomor >= tes_soal_jml){
                        $('#btn-selanjutnya').addClass('hide');
                        $('#btn-selesai-inline').removeClass('hide');
                    } else {
                        $('#btn-selanjutnya').removeClass('hide');
                        $('#btn-selesai-inline').addClass('hide');
                    }

                    // Tandai tombol soal aktif di grid
                    $('.tka-soal-btn, [id^="btn-soal-"]').removeClass('active');
                    $('#btn-soal-' + tes_soal_nomor).addClass('active');

                    // Render KaTeX Math pada isi soal yang baru dimuat
                    if(typeof renderMathOffline === 'function'){
                        renderMathOffline(document.getElementById('isi-tes-soal'));
                    }

                    // Terapkan kembali font size pilihan user
                    $('#isi-tes-soal').css("font-size", currentFontSize + "px");

                    // Scroll ke atas dengan halus
                    $('html, body').animate({ scrollTop: 0 }, 200);

                } else if(data.data == 2){
                    window.location.reload();
                }
                $("#modal-proses").modal('hide');
            },
            error: function(xmlhttprequest, textstatus, message) {
                $("#modal-proses").modal('hide');
                notify_error("Gagal mengambil soal, silahkan coba lagi.");
            }
        });
    }

    function soal_navigasi(step){
        var cur = parseInt($('#tes-soal-nomor').val());
        var total = parseInt($('#tes-soal-jml').val());
        var target = cur + step;

        if(target >= 1 && target <= total){
            $('#btn-soal-' + target).trigger('click');
        }
    }

    function hentikan_tes(){
        $("#modal-proses").modal('show');
        $('#hentikan-centang').prop("checked", false);
        $('#form-pesan-hentikan').html('');

        $.getJSON('<?php echo site_url().'/'.$url; ?>/get_tes_info/' + $('#tes-id').val(), function(data){
            if(data.data == 1){
                $('#hentikan-tes-id').val(data.tes_id);
                $('#hentikan-tes-user-id').val(data.tes_user_id);
                $('#hentikan-tes-nama').val(data.tes_nama);
                $('#hentikan-dijawab').val(data.tes_dijawab + " sudah dijawab, " + data.tes_blum_dijawab + " belum dijawab.");
                $("#modal-hentikan").modal('show');
            } else {
                window.location.reload();
            }
            $("#modal-proses").modal('hide');
        });
    }

    $(function(){
        // Hitung mundur sisa waktu
        var sisa_detik = <?php if(!empty($detik_sisa)){ echo $detik_sisa; }else{ echo 0; } ?>;
        
        function formatWaktuDigital(totalDetik){
            var hours = Math.floor(totalDetik / 3600);
            var minutes = Math.floor((totalDetik % 3600) / 60);
            var seconds = totalDetik % 60;

            var pad = function(n){ return (n < 10 ? '0' : '') + n; };
            return pad(hours) + " : " + pad(minutes) + " : " + pad(seconds);
        }

        if(sisa_detik > 0){
            $("#sisa-waktu-digital").html(formatWaktuDigital(sisa_detik));
            var timerInterval = setInterval(function() {
                sisa_detik--;
                $("#sisa-waktu-digital").html(formatWaktuDigital(sisa_detik));

                // Peringatan 5 menit terakhir
                if(sisa_detik <= 300 && sisa_detik > 295){
                    notify_error("Peringatan: Waktu pengerjaan tersisa kurang dari 5 menit!");
                }

                if(sisa_detik < 1){
                    clearInterval(timerInterval);
                    notify_error("Waktu telah habis. Mengirim lembar jawaban...");
                    setTimeout(function(){ window.location.reload(); }, 1500);
                }
            }, 1000);
        }

        $('#btn-sebelumnya').click(function(){ soal_navigasi(-1); });
        $('#btn-selanjutnya').click(function(){ soal_navigasi(1); });

        // Styling awal tombol di grid daftar soal
        $('#modal-daftar-soal').on('show.bs.modal', function(){
            var cur = $('#tes-soal-nomor').val();
            $('[id^="btn-soal-"]').each(function(){
                $(this).addClass('tka-soal-btn');
                if($(this).hasClass('btn-primary')){
                    $(this).addClass('answered');
                }
                if($(this).hasClass('btn-warning')){
                    $(this).addClass('ragu');
                }
            });
            $('#btn-soal-' + cur).addClass('active');
        });

        // Submit form hentikan tes
        $('#form-hentikan').submit(function(){
            if(!$('#hentikan-centang').is(':checked')){
                notify_error('Anda harus mencentang pernyataan konfirmasi terlebih dahulu.');
                return false;
            }

            $("#modal-proses").modal('show');
            $.ajax({
                url: "<?php echo site_url().'/'.$url; ?>/hentikan_tes",
                type: "POST",
                data: $('#form-hentikan').serialize(),
                cache: false,
                timeout: 10000,
                success: function(respon){
                    var obj = $.parseJSON(respon);
                    if(obj.status == 1){
                        window.location.reload();
                    } else {
                        $("#modal-proses").modal('hide');
                        $('#form-pesan-hentikan').html(pesan_err(obj.pesan));
                    }
                },
                error: function(xmlhttprequest, textstatus, message) {
                    $("#modal-proses").modal('hide');
                    notify_error("Gagal mengakhiri tes: " + textstatus);
                }
            });
            return false;
        });

        // Tandai nomor awal aktif
        var initialNomor = $('#tes-soal-nomor').val() || 1;
        $('#btn-soal-' + initialNomor).addClass('active');

        // Render KaTeX pada pemuatan pertama
        if(typeof renderMathOffline === 'function'){
            renderMathOffline(document.getElementById('isi-tes-soal'));
        }
    });
</script>