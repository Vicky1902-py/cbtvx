<div class="container smk-animated-box" style="margin-top: clamp(10px, 3vh, 30px); margin-bottom: 40px; padding: 0 15px;">
    <div class="row">
        <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-12">
            <!-- Modern SMKN 1 Kupang Barat Glassmorphism Card -->
            <div style="background: rgba(3, 18, 48, 0.82); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1.5px solid rgba(0, 180, 216, 0.3); border-radius: 24px; padding: clamp(22px, 5vw, 36px); box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6); position: relative; overflow: hidden; width: 100%;">
                
                <!-- Glowing Ambient Accent -->
                <div style="position: absolute; top: -50px; right: -50px; width: 160px; height: 160px; background: radial-gradient(circle, rgba(0, 180, 216, 0.28) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>

                <!-- Header Logo & Identity -->
                <div class="text-center" style="margin-bottom: 24px;">
                    <div style="display: inline-block; position: relative; margin-bottom: 14px;">
                        <img src="<?php echo base_url(); ?>public/images/logo_smk.png" alt="Logo SMKN 1 Kupang Barat" 
                             style="width: 76px; height: 76px; object-fit: contain; filter: drop-shadow(0 4px 14px rgba(0, 180, 216, 0.5));" />
                    </div>
                    
                    <div style="display: inline-block; background: rgba(0, 180, 216, 0.15); border: 1px solid rgba(0, 180, 216, 0.35); color: #38bdf8; font-size: 11px; font-weight: 700; padding: 4px 14px; border-radius: 20px; letter-spacing: 0.8px; text-transform: uppercase; margin-bottom: 8px;">
                        PANEL OPERATOR & GURU
                    </div>
                    <h2 style="font-size: clamp(20px, 4vw, 24px); font-weight: 800; color: #ffffff; margin: 0 0 6px 0; letter-spacing: -0.5px;">
                        Login Administrator CBT
                    </h2>
                    <p style="font-size: 13px; color: #94a3b8; margin: 0;">
                        SMK Negeri 1 Kupang Barat &bull; Standar TKA Pusmendik
                    </p>
                </div>

                <!-- Alert Box Pesan Error -->
                <div id="form-pesan" style="margin-bottom: 18px;"></div>

                <!-- Form Login Operator -->
                <?php echo form_open('welcome/login', 'id="form-login"'); ?>
                    <div class="form-group" style="margin-bottom: 18px;">
                        <label for="username" style="font-size: 13px; font-weight: 600; color: #cbd5e1; margin-bottom: 8px; display: block;">
                            <i class="fa fa-user" style="color: #00b4d8; margin-right: 6px;"></i> Username Operator
                        </label>
                        <div style="position: relative;">
                            <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username admin" required
                                style="height: 48px; border-radius: 12px; background: rgba(2, 8, 24, 0.7); border: 1px solid rgba(0, 180, 216, 0.25); color: #ffffff; font-size: 14px; padding: 10px 16px; transition: all 0.25s ease;"
                                onfocus="this.style.borderColor='#00b4d8'; this.style.boxShadow='0 0 0 3px rgba(0, 180, 216, 0.25)';"
                                onblur="this.style.borderColor='rgba(0, 180, 216, 0.25)'; this.style.boxShadow='none';" />
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 22px;">
                        <label for="password" style="font-size: 13px; font-weight: 600; color: #cbd5e1; margin-bottom: 8px; display: block;">
                            <i class="fa fa-lock" style="color: #00b4d8; margin-right: 6px;"></i> Password Operator
                        </label>
                        <div style="position: relative;">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan kata sandi" required
                                style="height: 48px; border-radius: 12px; background: rgba(2, 8, 24, 0.7); border: 1px solid rgba(0, 180, 216, 0.25); color: #ffffff; font-size: 14px; padding: 10px 16px; transition: all 0.25s ease;"
                                onfocus="this.style.borderColor='#00b4d8'; this.style.boxShadow='0 0 0 3px rgba(0, 180, 216, 0.25)';"
                                onblur="this.style.borderColor='rgba(0, 180, 216, 0.25)'; this.style.boxShadow='none';" />
                        </div>
                    </div>

                    <button type="submit" id="btn-login" class="btn btn-block" 
                        style="height: 48px; border-radius: 12px; background: linear-gradient(135deg, #0077b6 0%, #00b4d8 100%); color: #ffffff; font-size: 15px; font-weight: 700; border: none; box-shadow: 0 10px 20px -5px rgba(0, 180, 216, 0.45); transition: all 0.25s ease; cursor: pointer;">
                        <i class="fa fa-sign-in" style="margin-right: 8px;"></i> Masuk ke Panel Kontrol
                    </button>
                <?php echo form_close(); ?>

                <!-- Footer Author Signature -->
                <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid rgba(0, 180, 216, 0.15); text-align: center;">
                    <div style="font-size: 12px; color: #94a3b8; font-weight: 500;">
                        Hak Cipta &copy; <b>Vicky Koroh</b>
                    </div>
                    <div style="font-size: 11px; color: #64748b; margin-top: 3px;">
                        SMK Negeri 1 Kupang Barat &bull; Pilihan Ganda, Benar/Salah, Uraian
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $('#username').focus();   
        
        $('#form-login').submit(function(e){
            e.preventDefault();
            $("#modal-proses").modal('show');
            $.ajax({
                url: "<?php echo site_url(); ?>/manager/welcome/login",
                type: "POST",
                data: $('#form-login').serialize(),
                cache: false,
                dataType: "json",
                success: function(obj){
                    if(obj.status == 1){
                        window.location.href = "<?php echo site_url(); ?>/manager/dashboard";
                    } else {
                        $("#modal-proses").modal('hide');
                        $('#form-pesan').html(
                            '<div style="background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4); color: #fca5a5; padding: 12px 16px; border-radius: 12px; font-size: 13px; font-weight: 600;">' +
                            '<i class="fa fa-exclamation-triangle" style="margin-right: 8px;"></i>' + (obj.error || 'Username atau password salah.') +
                            '</div>'
                        );
                        $('#username').focus();
                    }
                },
                error: function() {
                    $("#modal-proses").modal('hide');
                    $('#form-pesan').html(
                        '<div style="background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4); color: #fca5a5; padding: 12px 16px; border-radius: 12px; font-size: 13px; font-weight: 600;">' +
                        '<i class="fa fa-exclamation-triangle" style="margin-right: 8px;"></i> Terjadi gangguan komunikasi server.' +
                        '</div>'
                    );
                }
            });
            return false;
        });    
    });
</script>
