$('#togglePassword_awal').click(function() {
    $(this).children('i').toggleClass('bi-eye bi-eye-slash');
    if ($('#sandibaru').attr('type') === 'password') {
        $('#sandibaru').attr('type', 'text');
    } else {
        $('#sandibaru').attr('type', 'password');
    }
});
$('#togglePassword_konfirmasi').click(function() {
    $(this).children('i').toggleClass('bi-eye bi-eye-slash');
    if ($('#konfirmasisandibaru').attr('type') === 'password') {
        $('#konfirmasisandibaru').attr('type', 'text');
    } else {
        $('#konfirmasisandibaru').attr('type', 'password');
    }
});
$("#btn_ubah_katasandi" ).on( "click", function() {
    if ($("#sandibaru").val() == "" || $("#konfirmasisandibaru").val() == "") return toastr.error('Kata sandi tidak boleh kosong pada salah satu atribut. Sandi Baru dan Konfirmasi Sandi harus diisi', 'Pesan Kesalahan')
    if ($("#sandibaru").val() !== $("#konfirmasisandibaru").val()) return toastr.error('Aduh.. anda typo ya. Mohon cek ulang apakah konfirmasi sandi sudah sama dengan sandi baru', 'Pesan Kesalahan')
    $('#btn_ubah_katasandi').prop("disabled",true);$('#btn_ubah_katasandi').html('<i class="mdi mdi-spin mdi-cog-outline fs-15"></i> Proses Ubah Kata Sandi');
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurl + '/ubahpassword',
            type: 'POST',
            dataType: 'json',
            data: {
                _token: response.csrf_token,
                email_reset: $("#email_reset").html(),
                password_baru: $("#sandibaru").val().trim(),
                password_baru_konfirmasi: $("#konfirmasisandibaru").val().trim(),
            },
            complete: function() {
                $('#btn_ubah_katasandi').prop("disabled", false);$('#btn_ubah_katasandi').html('Ubah Kata Sandi');
            },
            success: function(response) {
                if (response.success == false) {
                    return toastr.error(response.message, 'Pesan Kesalahan Code : ' + response.rc);
                }
                return toastr.success(response.message);
            },
            error: function(xhr, status, error) {
                $('#btn_ubah_katasandi').prop("disabled", false);$('#btn_ubah_katasandi').html('Ubah Kata Sandi');
                toastr.error('Terjadi kesalahan proses UBAH KATA SANDI. Silahkan hubungi TIM Terkiat. Pesan Kesalahan : ' + xhr.responseJSON.message, 'Pesan REST API Login');
            }
        });
    });
});