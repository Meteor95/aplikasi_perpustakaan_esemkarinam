let tabelpengembalian = "";
$(function() {
    tabelpengembalian = $('#tabel_pengembalian').DataTable({
        dom: 't',
        ordering: false,
        iDisplayLength: -1
    });
    tabel_keranjang_pengembalian = $('#tabel_keranjang_pengembalian').DataTable({
        dom: 't',
        ordering: false,
        iDisplayLength: -1
    });
    $("#tanggal_trx").datepicker({
        todayHighlight: true,
        format:'dd-mm-yyyy',
        orientation: "bottom left",
        uiLibrary: 'bootstrap5',
    })
    $('#tanggal_trx').val(moment().format('DD-MM-YYYY'));
});
$('#tabel_keranjang_pengembalian').on('click', '.hapusbariskeranjang', function () {
    let row = $(this).parents('tr');
    if ($(row).hasClass('child')) {
        tabelpengembalian.row($(row).prev('tr')).remove().draw();
    } else {
        tabelpengembalian.row($(this).parents('tr')).remove().draw();
    }
});
function prosestambahkeranjang(){
    $('#tambah_ke_keranjang').prop("disabled", true);$('#tambah_ke_keranjang').html('<i class="ri-file-add-line align-middle"></i> Proses Tambah Keranjang');
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            "url": baseurlapi + "/perpustakaan/daftar_buku",
            "type": 'GET',
            "beforeSend": function (xhr) { xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('session_id_browser'));},
            "dataType": 'json',
            "data": {
                _token: response.csrf_token,
                id_buku: $("#kode_buku").val(),
                id_member: $("#id_member").val(),
                detailbuku:true,
                pengembalian:true,
            },
            "complete": function() {
                $('#tambah_ke_keranjang').prop("disabled", false);$('#tambah_ke_keranjang').html('<i class="ri-file-add-line align-middle"></i> Keranjang');
            },
            "success": function(response) {
                if (response.success == false) {
                    return toastr.error(response.message, 'Pesan Kesalahan Code : ' + response.rc);
                }
                toastr.success("Buku dengan informasi berhasil ditambahkan.","Buku Ditambahkan");
                let data = tabel_keranjang_pengembalian.rows().data(); 
                let found = false;
                for (let i = 0; i < data.length; i++) {
                    if (data[i][0] == response.data.id_buku) {
                        let autoNumericInstance = AutoNumeric.getAutoNumericElement("#baris" + response.data.id_buku);
                        let currentQty = autoNumericInstance.getNumber() || 0;
                        autoNumericInstance.set(currentQty + 1);
                        found = true;
                        break;
                    }
                }
                if (!found) {
                    tabel_keranjang_pengembalian.row.add([
                        "Kode Buku : " + response.data.id_buku + "<br>" +
                        "Nama Buku : " + response.data.nama_buku,
                        "Terlambat : "+response.data.nama_buku.keterlambatan_hari+" Hari <br>"+
                        "Peminjaman : "+formatDateddmmyyyhhiiss(item["tanggal_peminjaman"])+"<br>"+
                        "Pengembalian : "+formatDateddmmyyyhhiiss(item["tanggal_pengembalian"]),
                        response.data.nama_kategori,
                        "<input id=\"baris"+response.data.id_buku+"\" name=\"bonusitem[]\" class=\"qty hanyaangka form-control\" type=\"text\" value=\"1\">",
                        '<div><button class="hapusbariskeranjang btn btn-danger"><i class="ri-delete-bin-line"></i> Hapus Baris Ini</button></div>',
                    ]).draw(false);
                }
                if (!AutoNumeric.getAutoNumericElement("#baris" + response.data.id_buku)) {
                    new AutoNumeric("#baris" + response.data.id_buku, {
                        decimalPlaces: 0,
                        digitGroupSeparator: '.',
                        decimalCharacter: ',',
                        minimumValue: '0',
                        modifyValueOnWheel: false,
                    });
                }
                $("#kode_buku").val("")
                $("#kode_buku").focus();
            },
            "error": function(xhr, status, error) {
                $('#tambah_ke_keranjang').prop("disabled", false);$('#tambah_ke_keranjang').html('<i class="ri-file-add-line align-middle"></i> Keranjang');
                toastr.error('Terjadi kesalahan proses PENAMBAHAN INFORMASI BUKU. Silahkan hubungi TIM Terkiat. Pesan Kesalahan : ' + xhr.responseJSON.message, 'Pesan REST API Tambah Informasi Buku');
            }
        });
    });
}
$('#tambah_ke_keranjang').on('click', function() {
    prosestambahkeranjang();
});
$('#id_member').keyup(function(e){
    if(e.keyCode == 13){
        $('#btn_aktifkan_camera').prop("disabled", true);$('#btn_aktifkan_camera').html('<i class="ri-file-add-line align-middle"></i> Proses Pencairan Informasi');
        $.get('/generate-csrf-token', function(response) {
            $.ajax({
                "url": baseurlapi + '/perpustakaan/ambilpeminjaman',
                "type": 'GET',
                "beforeSend": function (xhr) { xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('session_id_browser'));},
                "dataType": 'json',
                "data": {
                    _token: response.csrf_token,
                    parameter_pencarian: $("#id_member").val(),
                },
                "complete": function() {
                    $('#btn_aktifkan_camera').prop("disabled", false);$('#btn_aktifkan_camera').html('<i class="ri-qr-code-fill"></i> Scan Kartu Anggota');
                },
                "success": function(response) {
                    console.log(response)
                    if (response.recordsFiltered == 0) {
                        $("#list_nis_anggota").html("")
                        $("#list_nama_anggota").html("")
                        return toastr.error("Informasi yang ada cari tidak ditemukan. Silahkan cek lagi NIS atau NISN dari siswa tersebut", 'Pesan Kesalahan Code : 404');
                    }
                    $("#list_nis_anggota").html(response.data[0].nisn)
                    $("#list_nama_anggota").html(response.data[0].nama_lengkap)
                    response.data.forEach(item => {
                        let denda = "";
                        if (item["keterlambatan_hari"] > 0) {
                            denda = formatRupiah(item["keterlambatan_hari"] * item["denda"],"Rp. ");
                        } else {
                            denda = "Rp. 0";
                        }
                        tabelpengembalian.row.add([
                            item["id_transaksi"],
                            "Nama Buku : "+item["nama_buku"]+"<br>"+
                            "Qty Pinjam : "+item["qty_pinjam"]+" Buku",
                            "Terlambat : "+item["keterlambatan_hari"]+" Hari <br>"+
                            "Peminjaman : "+formatDateddmmyyyhhiiss(item["tanggal_peminjaman"])+"<br>"+
                            "Pengembalian : "+formatDateddmmyyyhhiiss(item["tanggal_pengembalian"]),
                            denda,
                            "<button class=\"btn btn-outline-success\"> Pilih </button>",
                        ]).draw(false);
                    });
                },
                "error": function(xhr, status, error) {
                    $('#btn_aktifkan_camera').prop("disabled", false);$('#btn_aktifkan_camera').html('<i class="ri-qr-code-fill"></i> Scan Kartu Anggota');
                    toastr.error('Terjadi kesalahan proses PEMBACAAN INFORMASI PEMINJAMAN. Silahkan hubungi TIM Terkiat. Pesan Kesalahan : ' + xhr.responseJSON.message, 'Pesan REST API');
                }
            });
        });
    }
});
