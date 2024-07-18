let tablepeminjaman = "";
$(function() {
    tablepeminjaman = $('#tabel_peminjaman').DataTable({
        dom: 't',
        ordering: false
    });
    $("#tanggal_trx").datepicker({
        todayHighlight: true,
        format:'dd-mm-yyyy',
        orientation: "bottom left",
        uiLibrary: 'bootstrap5',
    })
    $('#tanggal_trx').val(moment().format('DD-MM-YYYY'));
});
function prosestambahkeranjang(){
    if ($("#kode_buku").val() == ""){
        return toastr.error("Informasi tidak boleh kosong. Silahkan ketikan nomor, kode, atau nama buku untuk dimasukkan ke keranjang peminjaman", 'Pesan Kesalahan');
    }
    $('#tambah_ke_keranjang').prop("disabled", true);$('#tambah_ke_keranjang').html('<i class="ri-file-add-line align-middle"></i> Keranjang');
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            "url": baseurlapi + "/perpustakaan/daftar_buku",
            "type": 'GET',
            "beforeSend": function (xhr) { xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('session_id_browser'));},
            "dataType": 'json',
            "data": {
                _token: response.csrf_token,
                id_buku: $("#kode_buku").val(),
                detailbuku:true,
            },
            "complete": function() {
                $('#tambah_ke_keranjang').prop("disabled", false);$('#tambah_ke_keranjang').html('<i class="ri-file-add-line align-middle"></i> Keranjang');
            },
            "success": function(response) {
                if (response.success == false) {
                    return toastr.error(response.message, 'Pesan Kesalahan Code : ' + response.rc);
                }
                toastr.success("Buku dengan informasi berhasil ditambahkan.","Buku Ditambahkan");
                let data = tablepeminjaman.rows().data(); 
                let found = false;
                for (let i = 0; i < data.length; i++) {
                    if (data[i][0] == response.data.id_buku) {
                        let qtyInput = $(tablepeminjaman.row(i).node()).find('input.qty');
                        let currentQty = parseInt(qtyInput.val()) || 0; 
                        qtyInput.val(currentQty + 1); 
                        found = true;
                        break;
                    }
                }
                if (!found) {
                    tablepeminjaman.row.add([
                        response.data.id_buku,
                        "Nama Buku : " + response.data.nama_buku + "<br>" +
                        "Nama Penerbit : " + response.data.nama_penerbit + "<br>" +
                        "Nama Pengarang : " + response.data.nama_pengarang + "<br>" +
                        "Lokasi Laci : " + response.data.nama_laci + "<br>" +
                        "Lokasi Rak : " + response.data.nama_rak,
                        response.data.nama_kategori,
                        "<input name=\"bonusitem[]\" class=\"qty hanyaangka form-control\" type=\"text\" value=\"1\">",
                        '<div><button class="hapusbariskeranjang btn btn-danger"><i class="ri-delete-bin-line"></i> Hapus Baris Ini</button></div>',
                    ]).draw(false);
                }
                new AutoNumeric('.hanyaangka', {
                    decimalPlaces: 0,
                    digitGroupSeparator: '.',
                    decimalCharacter: ',',
                    minimumValue: '0',
                    modifyValueOnWheel: false,
                });
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
$('#kode_buku').keyup(function(e){
    if(e.keyCode == 13){prosestambahkeranjang();}
});
$('#tabel_peminjaman').on('click', '.hapusbariskeranjang', function () {
    let row = $(this).parents('tr');
    if ($(row).hasClass('child')) {
        tablepeminjaman.row($(row).prev('tr')).remove().draw();
    } else {
        tablepeminjaman.row($(this).parents('tr')).remove().draw();
    }
});
function prosessimpanpeminjaman(){
    if ($("#id_member").val() === "" || $("#tanggal_trx").val() === ""){
        return toastr.error("Informasi Member dan Tanggal Transaksi dibutuhkan dalam sistem. Silahkan isikan formulir tersebut", 'Pesan Kesalahan');
    }
    if (tablepeminjaman.data().count() == 0){
        return toastr.error("Gagal menyimpan. Dikarenakan tidak ada jumlah buku yang ingin dipunjam. Silahkan masukan 1 barang untuk melanjutkan transaksi", 'Pesan Kesalahan');
    }
    $('#btn_simpan_peminjaman_buku').prop("disabled", true);$('#btn_simpan_peminjaman_buku').html('<i class="ri-file-add-line align-middle"></i> Proses Simpan');
    let tableDataJsonPeminjaman = [];
    $('#tabel_peminjaman tbody tr').each(function (isidatatable, index) {
        let row = $(this);
        let rowData = {
            id_buku: row.find('td').eq(0).text(),
            total_yang_dipinjam: tablepeminjaman.cell(index,3).nodes().to$().find('input').val(),
        };
        tableDataJsonPeminjaman.push(rowData);
    });
    Swal.fire({
        html:
            '<div class="mt-3"><lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop" colors="primary:#f06548,secondary:#f7b84b" style="width:120px;height:120px"></lord-icon><div class="pt-2 fs-15"><h4>Konfirmasi Simpan Peminjaman Buku !</h4><p class="text-muted mx-4 mb-0">Apakah anda ingin meminjamankan buku yang ada dikeranjang peminjaman kepada '+$("#list_nama_anggota").html()+'</p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Simpan Informasi',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $('#btn_simpan_tahunajaran').prop("disabled",true);$('#btn_simpan_tahunajaran').html('<i class="mdi mdi-spin mdi-cog-outline fs-15"></i> Proses Simpan Informasi');
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    "url": baseurlapi + "/perpustakaan/proses_peminjaman",
                    "type": 'POST',
                    "beforeSend": function (xhr) { xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('session_id_browser'));},
                    "dataType": 'json',
                    "data": {
                        _token: response.csrf_token,
                        id_petugas: id_user_login,
                        id_member:$("#id_member").val(),
                        nomor_transaksi:($("#nomor_trx").val() === "" ? "" : $("#nomor_trx").val()),
                        tanggal_transaksi:$("#tanggal_trx").val(),
                        keterangan:$("#keterangan_trx").val(),
                        data_buku: tableDataJsonPeminjaman,
                    },
                    "complete": function() {
                        $('#btn_simpan_peminjaman_buku').prop("disabled", false);$('#btn_simpan_peminjaman_buku').html('<i class="ri-database-line"></i> Simpan Informasi Peminjaman Buku');
                    },
                    "success": function(response) {
                        console.log(response)
                        if (response.success == false) {
                            return toastr.error(response.message, 'Pesan Kesalahan Code : ' + response.rc);
                        }
                        toastr.success("Informasi peminjaman sudah berhasil di rekam pada database dengan Nama Peminjam : "+$("#list_nama_anggota").html());
                        tablepeminjaman.clear().draw();
                        $("#list_nis_anggota").html("")
                        $("#list_nama_anggota").html("")
                        $("#id_member").val("")
                        $("#nomor_trx").val("")
                        $("#keterangan_trx").val("")
                        $("#tanggal_trx").val(moment().format('DD-MM-YYYY'))
                        $("#kode_buku").val("")
                        $("#id_member").focus()
        
                    },
                    "error": function(xhr, status, error) {
                        $('#btn_simpan_peminjaman_buku').prop("disabled", false);$('#btn_simpan_peminjaman_buku').html('<i class="ri-database-line"></i> Simpan Informasi Peminjaman Buku');
                        toastr.error('Terjadi kesalahan proses PENYIMPANAN INFORMASI PEMINJAMAN. Silahkan hubungi TIM Terkiat. Pesan Kesalahan : ' + xhr.responseJSON.message, 'Pesan REST API Tambah Informasi Buku');
                    }
                });
            });
        }
    });
}
$('#btn_simpan_peminjaman_buku').on('click', function() {
    prosessimpanpeminjaman();
});
$('#id_member').keyup(function(e){
    if(e.keyCode == 13){
        $('#btn_aktifkan_camera').prop("disabled", true);$('#btn_aktifkan_camera').html('<i class="ri-file-add-line align-middle"></i> Proses Pencairan Informasi');
        $.get('/generate-csrf-token', function(response) {
            $.ajax({
                "url": baseurlapi + '/murid/ajaxtabelmurid',
                "type": 'GET',
                "beforeSend": function (xhr) { xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('session_id_browser'));},
                "dataType": 'json',
                "data": {
                    _token: response.csrf_token,
                    parameter_pencarian: $("#id_member").val(),
                    detail: true,
                    length:1,
                    start:0,
                },
                "complete": function() {
                    $('#btn_aktifkan_camera').prop("disabled", false);$('#btn_aktifkan_camera').html('<i class="ri-qr-code-fill"></i> Scan Kartu Anggota');
                },
                "success": function(response) {
                    if (response.recordsFiltered == 0) {
                        $("#list_nis_anggota").html("")
                        $("#list_nama_anggota").html("")
                        return toastr.error("Informasi yang ada cari tidak ditemukan. Silahkan cek lagi NIS atau NISN dari siswa tersebut", 'Pesan Kesalahan Code : 404');
                    }
                    $("#list_nis_anggota").html(response.data[0].nisn)
                    $("#list_nama_anggota").html(response.data[0].nama_lengkap)
                },
                "error": function(xhr, status, error) {
                    $('#btn_aktifkan_camera').prop("disabled", false);$('#btn_aktifkan_camera').html('<i class="ri-qr-code-fill"></i> Scan Kartu Anggota');
                    toastr.error('Terjadi kesalahan proses PEMBACAAN INFORMASI PEMINJAMAN. Silahkan hubungi TIM Terkiat. Pesan Kesalahan : ' + xhr.responseJSON.message, 'Pesan REST API');
                }
            });
        });
    }
});
