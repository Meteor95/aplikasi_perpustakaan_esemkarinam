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
        iDisplayLength: -1,
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
        tabel_keranjang_pengembalian.row($(row).prev('tr')).remove().draw();
    } else {
        tabel_keranjang_pengembalian.row($(this).parents('tr')).remove().draw();
    }
});
function prosestambahkeranjang(){
    $('#tambah_ke_keranjang').prop("disabled", true);$('#tambah_ke_keranjang').html('<i class="ri-file-add-line align-middle"></i> Proses Tambah Keranjang');
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            "url": baseurlapi + "/perpustakaan/ambilpeminjaman",
            "type": 'GET',
            "beforeSend": function (xhr) { xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('session_id_browser'));},
            "dataType": 'json',
            "data": {
                _token: response.csrf_token,
                id_buku: $("#kode_buku").val(),
                id_member: $("#id_member").val(),
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
                    let denda = "";
                    if (response.data.keterlambatan_hari > 0) {
                        denda = formatRupiah(response.data.keterlambatan_hari * response.data.denda,"Rp. ");
                    } else {
                        denda = "Rp. 0";
                    }
                    tabel_keranjang_pengembalian.row.add([
                        response.data.id_buku,
                        "Nama Buku : " + response.data.nama_buku + "<br>"+
                        "Qty Dipinjam : " + response.data.qty_pinjam,
                        "Peminjaman : "+formatDateddmmyyyhhiiss(response.data.tanggal_peminjaman)+"<br>"+
                        "Pengembalian : "+formatDateddmmyyyhhiiss(response.data.tanggal_pengembalian),
                        response.data.keterlambatan_hari,
                        denda,
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
                        maximumValue: response.data.qty_pinjam,
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
$('#kode_buku').keyup(function(e){
    if(e.keyCode == 13){prosestambahkeranjang();}
});
$('#id_member').keyup(function(e){
    if(e.keyCode == 13){
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
                "complete": function() {},
                "success": function(response) {
                    if (response.data.length == 0) {
                        $("#list_nis_anggota").html("")
                        $("#list_nama_anggota").html("")
                        return toastr.error("Informasi yang ada cari tidak ditemukan. Silahkan cek lagi NIS atau NISN dari siswa tersebut", 'Pesan Kesalahan Code : 404');
                    }
                    $("#list_nis_anggota").html(response.data[0].nis)
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
                            "Qty Pinjam : "+item["qty_pinjam"]+" Buku <br>"+
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
$('#btn_simpan_pengembalian_buku').on('click', function() {
    simpanpengembalian();
});
function simpanpengembalian(){
    if ($("#id_member").val() === "" || $("#tanggal_trx").val() === ""){
        return toastr.error("Informasi Member dan Tanggal Transaksi dibutuhkan dalam sistem. Silahkan isikan formulir tersebut", 'Pesan Kesalahan');
    }
    if (tabel_keranjang_pengembalian.data().count() == 0){
        return toastr.error("Gagal menyimpan. Dikarenakan tidak ada jumlah buku yang ingin dikembalikan. Silahkan masukan 1 barang untuk melanjutkan transaksi", 'Pesan Kesalahan');
    }
    $('#btn_simpan_pengembalian_buku').prop("disabled", true);$('#btn_simpan_pengembalian_buku').html('<i class="ri-file-add-line align-middle"></i> Proses Simpan');
    let tableDataJsonPengembalian = [];
    let totaldenda = 0;
    $('#tabel_keranjang_pengembalian tbody tr').each(function (isidatatable, index) {
        let row = $(this);
        let rowData = {
            id_buku: row.find('td').eq(0).text(),
            total_yang_dikembalikan: tabel_keranjang_pengembalian.cell(index,5).nodes().to$().find('input').val(),
            denda_per_buku: parseInt(row.find('td').eq(4).text().replace("Rp. ", "").replace(/\./g, "",10)),
            keterangan: "",
            terlambat_hari:row.find('td').eq(3).text(),
        };
        totaldenda = totaldenda + parseInt(row.find('td').eq(4).text().replace("Rp. ", "").replace(/\./g, "",10));
        tableDataJsonPengembalian.push(rowData);
    });
    Swal.fire({
        html:
            '<div class="mt-3"><lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop" colors="primary:#f06548,secondary:#f7b84b" style="width:120px;height:120px"></lord-icon><div class="pt-2 fs-15"><h4>Konfirmasi Simpan Pengembalian Buku !</h4><p class="text-muted mx-4 mb-0">Apakah anda ingin mencatata pengembalian buku yang ada dikeranjang pengembalian</p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Simpan Informasi',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $('#btn_simpan_pengembalian_buku').prop("disabled",true);$('#btn_simpan_pengembalian_buku').html('<i class="mdi mdi-spin mdi-cog-outline fs-15"></i> Proses Simpan Informasi');
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    "url": baseurlapi + "/perpustakaan/proses_pengembalian",
                    "type": 'POST',
                    "beforeSend": function (xhr) { xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('session_id_browser'));},
                    "dataType": 'json',
                    "data": {
                        _token: response.csrf_token,
                        id_petugas: id_user_login,
                        id_member: $("#list_nis_anggota").html(),
                        tanggal_dikembalikan: $("#tanggal_trx").val(),
                        denda: totaldenda,
                        data_buku: tableDataJsonPengembalian,
                    },
                    "complete": function() {
                        $('#btn_simpan_pengembalian_buku').prop("disabled", false);$('#btn_simpan_pengembalian_buku').html('<i class="ri-database-line"></i> Simpan Informasi Pengembalian Buku');
                    },
                    "success": function(response) {
                        console.log(response)
                        if (response.success == false) {
                            return toastr.error(response.message, 'Pesan Kesalahan Code : ' + response.rc);
                        }
                        toastr.success("Informasi peminjaman sudah berhasil di rekam pada database dengan Nama Peminjam : "+$("#list_nama_anggota").html());        
                    },
                    "error": function(xhr, status, error) {
                        $('#btn_simpan_pengembalian_buku').prop("disabled", false);$('#btn_simpan_pengembalian_buku').html('<i class="ri-database-line"></i> Simpan Informasi Peminjaman Buku');
                        toastr.error('Terjadi kesalahan proses PENYIMPANAN INFORMASI PENGEMBALIAN. Silahkan hubungi TIM Terkiat. Pesan Kesalahan : ' + xhr.responseJSON.message, 'Pesan REST API Tambah Informasi Buku');
                    }
                });
            });
        }else{
            $('#btn_simpan_pengembalian_buku').prop("disabled", false);$('#btn_simpan_pengembalian_buku').html('<i class="ri-database-line"></i> Simpan Informasi Pengembalian Buku');
        }
    });
}