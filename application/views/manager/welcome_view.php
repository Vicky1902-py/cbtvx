<div class="container cbt-card-animated" style="margin-top: 30px; margin-bottom: 50px;">
    <div class="row">
        <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-12">
            <!-- Glassmorphism Card Operator Login -->
            <div style="background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 24px; padding: 36px 32px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); position: relative; overflow: hidden;">
                <!-- Glowing Corner Accent -->
                <div style="position: absolute; top: -50px; right: -50px; width: 150px; height: 150px; background: radial-gradient(circle, rgba(56, 189, 248, 0.25) 0%, rgba(56, 189, 248, 0) 70%); border-radius: 50%; pointer-events: none;"></div>

                <div class="text-center" style="margin-bottom: 28px;">
                    <div style="display: inline-flex; align-items: center; justify-content: center; width: 64px; height: 64px; border-radius: 18px; background: linear-gradient(135deg, #2563eb 0%, #0284c7 100%); color: #ffffff; font-size: 28px; box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.5); margin-bottom: 16px;">
                        <i class="fa fa-user-secret"></i>
                    </div>
                    <div style="display: inline-block; background: rgba(56, 189, 248, 0.15); border: 1px solid rgba(56, 189, 248, 0.3); color: #38bdf8; font-size: 11px; font-weight: 700; padding: 4px 14px; border-radius: 20px; letter-spacing: 0.8px; text-transform: uppercase; margin-bottom: 8px;">
                        PANEL OPERATOR & GURU
                    </div>
                    <h2 style="font-size: 24px; font-weight: 800; color: #ffffff; margin: 0 0 6px 0; letter-spacing: -0.5px;">
                        Login Administrator CBT
                    </h2>
                    <p style="font-size: 13px; color: #94a3b8; margin: 0;">
                        Standar TKA Kemdikbud • SMKN 1 Kupang Barat
                    </p>
                </div>

                <!-- Alert Box Pesan Error -->
                <div id="form-pesan" style="margin-bottom: 18px;"></div>

                <!-- Form Login Operator -->
                <?php echo form_open('welcome/login', 'id="form-login"'); ?>
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="username" style="font-size: 13px; font-weight: 600; color: #cbd5e1; margin-bottom: 8px; display: block;">
                            <i class="fa fa-user" style="color: #38bdf8; margin-right: 6px;"></i> Username Operator
                        </label>
                        <div style="position: relative;">
                            <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username admin" required
                                style="height: 48px; border-radius: 12px; background: rgba(2, 6, 23, 0.6); border: 1px solid rgba(255, 255, 255, 0.15); color: #ffffff; font-size: 14px; padding: 10px 16px; transition: all 0.25s ease;"
                                onfocus="this.style.borderColor='#38bdf8'; this.style.boxShadow='0 0 0 3px rgba(56, 189, 248, 0.2)';"
                                onblur="this.style.borderColor='rgba(255, 255, 255, 0.15)'; this.style.boxShadow='none';" />
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 24px;">
                        <label for="password" style="font-size: 13px; font-weight: 600; color: #cbd5e1; margin-bottom: 8px; display: block;">
                            <i class="fa fa-lock" style="color: #38bdf8; margin-right: 6px;"></i> Password Operator
                        </label>
                        <div style="position: relative;">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan kata sandi" required
                                style="height: 48px; border-radius: 12px; background: rgba(2, 6, 23, 0.6); border: 1px solid rgba(255, 255, 255, 0.15); color: #ffffff; font-size: 14px; padding: 10px 16px; transition: all 0.25s ease;"
                                onfocus="this.style.borderColor='#38bdf8'; this.style.boxShadow='0 0 0 3px rgba(56, 189, 248, 0.2)';"
                                onblur="this.style.borderColor='rgba(255, 255, 255, 0.15)'; this.style.boxShadow='none';" />
                        </div>
                    </div>

                    <button type="submit" id="btn-login" class="btn btn-block" 
                        style="height: 48px; border-radius: 12px; background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: #ffffff; font-size: 15px; font-weight: 700; border: none; box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.5); transition: all 0.25s ease; cursor: pointer;">
                        <i class="fa fa-sign-in" style="margin-right: 8px;"></i> Masuk ke Panel Kontrol
                    </button>
                <?php echo form_close(); ?>

                <!-- Footer Author Signature -->
                <div style="margin-top: 28px; padding-top: 18px; border-top: 1px solid rgba(255, 255, 255, 0.08); text-align: center;">
                    <div style="font-size: 12px; color: #94a3b8; font-weight: 500;">
                        Sistem Hak Cipta &copy; <b>Vicky Koroh</b>
                    </div>
                    <div style="font-size: 11px; color: #64748b; margin-top: 3px;">
                        Mendukung Pilihan Ganda, Benar/Salah, Uraian & Formula LaTeX
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
