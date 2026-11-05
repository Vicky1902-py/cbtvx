<div class="container cbt-card-animated" style="margin-top: 25px; margin-bottom: 50px;">
    <div class="row">
        <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-12">
            <!-- Modern Student Login Card -->
            <div style="background: rgba(15, 23, 42, 0.78); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 24px; padding: 36px 32px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); position: relative; overflow: hidden;">
                <!-- Decorative Glow -->
                <div style="position: absolute; top: -60px; left: -60px; width: 160px; height: 160px; background: radial-gradient(circle, rgba(16, 185, 129, 0.25) 0%, rgba(16, 185, 129, 0) 70%); border-radius: 50%; pointer-events: none;"></div>

                <div class="text-center" style="margin-bottom: 26px;">
                    <div style="display: inline-flex; align-items: center; justify-content: center; width: 64px; height: 64px; border-radius: 18px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #ffffff; font-size: 28px; box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.4); margin-bottom: 16px;">
                        <i class="fa fa-graduation-cap"></i>
                    </div>
                    <div style="display: inline-block; background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3); color: #34d399; font-size: 11px; font-weight: 700; padding: 4px 14px; border-radius: 20px; letter-spacing: 0.8px; text-transform: uppercase; margin-bottom: 8px;">
                        PORTAL ASESMEN SISWA
                    </div>
                    <h2 style="font-size: 24px; font-weight: 800; color: #ffffff; margin: 0 0 6px 0; letter-spacing: -0.5px;">
                        Selamat Datang Peserta Ujian
                    </h2>
                    <p style="font-size: 13px; color: #94a3b8; margin: 0;">
                        <?php if(!empty($site_name)){ echo $site_name; }else{ echo 'Ujian Berbasis Komputer Standar TKA Kemdikbud'; } ?>
                    </p>
                </div>

                <!-- Alert Box Pesan Error -->
                <div id="form-pesan" style="margin-bottom: 18px;"></div>

                <!-- Form Login Siswa -->
                <?php echo form_open('welcome/login', 'id="form-login"'); ?>
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="username" style="font-size: 13px; font-weight: 600; color: #cbd5e1; margin-bottom: 8px; display: block;">
                            <i class="fa fa-id-card-o" style="color: #34d399; margin-right: 6px;"></i> Nomor Peserta / Username
                        </label>
                        <div style="position: relative;">
                            <input type="text" class="form-control" id="username" name="username" autocomplete="off" placeholder="Masukkan NIS atau Username" required
                                style="height: 48px; border-radius: 12px; background: rgba(2, 6, 23, 0.6); border: 1px solid rgba(255, 255, 255, 0.15); color: #ffffff; font-size: 14px; padding: 10px 16px; transition: all 0.25s ease;"
                                onfocus="this.style.borderColor='#34d399'; this.style.boxShadow='0 0 0 3px rgba(16, 185, 129, 0.2)';"
                                onblur="this.style.borderColor='rgba(255, 255, 255, 0.15)'; this.style.boxShadow='none';" />
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 18px;">
                        <label for="password" style="font-size: 13px; font-weight: 600; color: #cbd5e1; margin-bottom: 8px; display: block;">
                            <i class="fa fa-key" style="color: #34d399; margin-right: 6px;"></i> Password / PIN Ujian
                        </label>
                        <div style="position: relative;">
                            <input type="password" class="form-control" id="password" name="password" autocomplete="off" placeholder="Masukkan password kartu ujian" required
                                style="height: 48px; border-radius: 12px; background: rgba(2, 6, 23, 0.6); border: 1px solid rgba(255, 255, 255, 0.15); color: #ffffff; font-size: 14px; padding: 10px 48px 10px 16px; transition: all 0.25s ease;"
                                onfocus="this.style.borderColor='#34d399'; this.style.boxShadow='0 0 0 3px rgba(16, 185, 129, 0.2)';"
                                onblur="this.style.borderColor='rgba(255, 255, 255, 0.15)'; this.style.boxShadow='none';" />
                            <span onclick="togglePasswordVisibility()" style="position: absolute; right: 14px; top: 14px; color: #94a3b8; cursor: pointer; font-size: 16px;" title="Lihat/Sembunyikan Password">
                                <i class="fa fa-eye" id="toggle-pwd-icon"></i>
                            </span>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                        <label style="color: #94a3b8; font-size: 13px; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; margin: 0;">
                            <input type="checkbox" id="show-password-chk" onchange="togglePasswordVisibility()" style="cursor: pointer; width: 16px; height: 16px;">
                            <span>Tampilkan Karakter</span>
                        </label>
                        <span style="font-size: 12px; color: #64748b;">
                            <i class="fa fa-lock"></i> Sesi Aman
                        </span>
                    </div>

                    <button type="submit" id="btn-login-siswa" class="btn btn-block" 
                        style="height: 48px; border-radius: 12px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #ffffff; font-size: 15px; font-weight: 700; border: none; box-shadow: 0 10px 20px -5px rgba(16, 185, 129, 0.4); transition: all 0.25s ease; cursor: pointer;">
                        <i class="fa fa-sign-in" style="margin-right: 8px;"></i> Mulai Sesi Ujian
                    </button>
                <?php echo form_close(); ?>

                <!-- Footer Author Signature -->
                <div style="margin-top: 26px; padding-top: 18px; border-top: 1px solid rgba(255, 255, 255, 0.08); text-align: center;">
                    <div style="font-size: 12px; color: #94a3b8; font-weight: 500;">
                        Hak Cipta &copy; <b>Vicky Koroh</b>
                    </div>
                    <div style="font-size: 11px; color: #64748b; margin-top: 3px;">
                        CBT Modern Berstandar TKA Kemdikbud • SMKN 1 Kupang Barat
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function togglePasswordVisibility() {
        var pwd = document.getElementById("password");
        var icon = document.getElementById("toggle-pwd-icon");
        var chk = document.getElementById("show-password-chk");
        if (pwd.type === "password") {
            pwd.type = "text";
            icon.className = "fa fa-eye-slash";
            if (chk) chk.checked = true;
        } else {
            pwd.type = "password";
            icon.className = "fa fa-eye";
            if (chk) chk.checked = false;
        }
    }

    $(function () {
        $('#username').focus(); 
        
        $('#form-login').submit(function(e){
            e.preventDefault();
            $("#modal-proses").modal('show');
            $.ajax({
                url: "<?php echo site_url(); ?>/welcome/login",
                type: "POST",
                data: $('#form-login').serialize(),
                cache: false,
                dataType: "json",
                success: function(obj){
                    if(obj.status == 1){
                        window.location.href = "<?php echo site_url(); ?>/tes_dashboard";
                    } else {
                        $("#modal-proses").modal('hide');
                        $('#form-pesan').html(
                            '<div style="background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4); color: #fca5a5; padding: 12px 16px; border-radius: 12px; font-size: 13px; font-weight: 600;">' +
                            '<i class="fa fa-exclamation-triangle" style="margin-right: 8px;"></i>' + (obj.error || 'Username atau password peserta tidak sesuai.') +
                            '</div>'
                        );
                        $('#username').focus();
                    }
                },
                error: function() {
                    $("#modal-proses").modal('hide');
                    $('#form-pesan').html(
                        '<div style="background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4); color: #fca5a5; padding: 12px 16px; border-radius: 12px; font-size: 13px; font-weight: 600;">' +
                        '<i class="fa fa-exclamation-triangle" style="margin-right: 8px;"></i> Terjadi kendala koneksi server.' +
                        '</div>'
                    );
                }
            });
            return false;
        });    
    });
</script>
