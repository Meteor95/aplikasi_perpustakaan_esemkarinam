$("#btn_login" ).on( "click", function() {
    if ($("#username").val() == "" || $("#password-input").val() == "") return toastr.error('Kredential yang dibutuhkan masih kurang. Silahkan lengkapi Nama Pengguna dan Kata Sandi!', 'Pesan Kesalahan')
    $('#btn_login').prop("disabled",true);$('#btn_login').html('<i class="mdi mdi-spin mdi-cog-outline fs-15"></i> Proses Autentifikasi');
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/auth/pintumasuk',
            type: 'POST',
            dataType: 'json',
            data: {
                _token: response.csrf_token,
                username: $("#username").val().trim(),
                password: $("#password-input").val().trim(),
                access_form: "web_login",
            },
            complete: function() {
                $('#btn_login').prop("disabled", false);$('#btn_login').html('Masuk Ke Panel Beranda App Esemkarinam');
            },
            success: function(response) {
                if (response.success == false) {
                    return toastr.error(response.message, 'Pesan Kesalahan Code : ' + response.rc);
                }
                localStorage.setItem("session_id_browser", response.token);
                window.location.href = baseurl + '/beranda';
            },
            error: function(xhr, status, error) {
                $('#btn_login').prop("disabled", false);$('#btn_login').html('Masuk Ke Panel Beranda App Esemkarinam');
                toastr.error('Terjadi kesalahan proses LOGIN. Silahkan hubungi TIM Terkiat. Pesan Kesalahan : ' + xhr.responseJSON.message, 'Pesan REST API Login');
            }
        });
    });
});
$("#bantuan" ).on( "click", function() {
    toastr.info('Jika butuh bantuan. Silahakan hubungi TIM Terkait mengenai hak akses masuk kedalam App Esemkarinam');
});
$("#lupa_password_modal").on( "click", function() {
    $('#modal_lupa_password').modal('toggle');
});
$('#togglePassword').click(function() {
    $(this).children('i').toggleClass('bi-eye bi-eye-slash');
    if ($('#password-input').attr('type') === 'password') {
        $('#password-input').attr('type', 'text');
    } else {
        $('#password-input').attr('type', 'password');
    }
});
$("#kirim_perimintaan_atur_ulang_sandi" ).on( "click", function() {
    if ($("#email_terkait").val() == "") return toastr.error('Bidang atribut surel dibutuhkan untuk melakukan atur ulang kata sandi');
    if (!isEmail($("#email_terkait").val())) return toastr.error('Format surel dibutuhkan. EX: inodreamstudio@gmai.com');
    $('#kirim_perimintaan_atur_ulang_sandi').prop("disabled",true);$('#kirim_perimintaan_atur_ulang_sandi').html('<i class="mdi mdi-spin mdi-cog-outline fs-15"></i> Mengirim Surel');
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/auth/lupapassword',
            type: 'GET',
            dataType: 'json',
            data: {
                _token: response.csrf_token,
                email: $("#email_terkait").val().trim(),
            },
            complete: function() {
                $('#kirim_perimintaan_atur_ulang_sandi').prop("disabled", false);$('#kirim_perimintaan_atur_ulang_sandi').html('Masuk Ke Panel Beranda App Esemkarinam');
            },
            success: function(response) {
                if (response.success == false) {
                    return toastr.error(response.message, 'Pesan Kesalahan Code : ' + response.rc);
                }
                toastr.success('Permintaan atur ulang kata sandi sudah kami kirim ke email : '+$("#email_terkait").val()+'. Silahkan cek surel anda. Terima Kasih');
                $('#modal_lupa_password').modal('toggle');
            },
            error: function(xhr, status, error) {
                $('#kirim_perimintaan_atur_ulang_sandi').prop("disabled", false);$('#kirim_perimintaan_atur_ulang_sandi').html('Masuk Ke Panel Beranda App Esemkarinam');
                toastr.error('Terjadi kesalahan proses LOGIN. Silahkan hubungi TIM Terkiat. Pesan Kesalahan : ' + xhr.responseJSON.message, 'Pesan REST API Login');
            }
        });
    });
});  
