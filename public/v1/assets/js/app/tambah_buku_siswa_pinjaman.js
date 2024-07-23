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
let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
scanner.addListener('scan', function(content) {
    prosestambahkeranjang(content);
});

let cameras = [];

Instascan.Camera.getCameras().then(function(camList) {
    if (camList.length > 0) {
        let cameras = camList;
        let cameraButtonsContainer = document.getElementById('camera-buttons');
        cameras.forEach((camera, index) => {
            let button = document.createElement('button');
            button.className = 'btn btn-outline-success';
            button.innerHTML = camera.name || `Camera ${index + 1}`;
            button.style.width = '100%';
            button.addEventListener('click', function() {
                scanner.start(camera);
            });
            let col = document.createElement('div');
            col.className = 'col-12 col-md-6';
            col.appendChild(button);
             cameraButtonsContainer.appendChild(col);
        });
        scanner.start(cameras[0]);
    } else {
        console.error('No cameras found.');
    }
}).catch(function(e) {
    console.error(e);
});
$('#tambah_ke_keranjang').on('click', function() {
    prosestambahkeranjang($('#kode_buku').val());
});
$('#kode_buku').keyup(function(e){
    if(e.keyCode == 13){prosestambahkeranjang($('#kode_buku').val());}
});
function prosestambahkeranjang(kodeitem){
    $('#tambah_ke_keranjang').prop("disabled", true);$('#tambah_ke_keranjang').html('<i class="ri-file-add-line align-middle"></i> Keranjang');
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            "url": baseurlapi + "/perpustakaan/daftar_buku",
            "type": 'GET',
            "beforeSend": function (xhr) { xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('session_id_browser'));},
            "dataType": 'json',
            "data": {
                _token: response.csrf_token,
                id_buku: kodeitem,
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
                        let autoNumericInstance = AutoNumeric.getAutoNumericElement("#baris" + response.data.id_buku);
                        let currentQty = autoNumericInstance.getNumber() || 0;
                        autoNumericInstance.set(currentQty + 1);
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
                $('#kode_buku').val("");
            },
            "error": function(xhr, status, error) {
                $('#tambah_ke_keranjang').prop("disabled", false);$('#tambah_ke_keranjang').html('<i class="ri-file-add-line align-middle"></i> Keranjang');
                toastr.error('Terjadi kesalahan proses PENAMBAHAN INFORMASI BUKU. Silahkan hubungi TIM Terkiat. Pesan Kesalahan : ' + xhr.responseJSON.message, 'Pesan REST API Tambah Informasi Buku');
            }
        });
    });
}
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
                        id_petugas:"0",
                        id_member:id_user_login_siswa,
                        nomor_transaksi:($("#nomor_trx").val() === "" ? "" : $("#nomor_trx").val()),
                        tanggal_transaksi:moment().format('DD-MM-YYYY'),
                        keterangan:"Siswa Meminjam",
                        data_buku: tableDataJsonPeminjaman,
                    },
                    "complete": function() {
                        $('#btn_simpan_peminjaman_buku').prop("disabled", false);$('#btn_simpan_peminjaman_buku').html('<i class="ri-database-line"></i> Simpan Informasi Peminjaman Buku');
                    },
                    "success": function(response) {
                        if (response.success == false) {
                            return toastr.error(response.message, 'Pesan Kesalahan Code : ' + response.rc);
                        }
                        toastr.success("Informasi peminjaman sudah berhasil di rekam pada database");
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
        }else{
            $('#btn_simpan_peminjaman_buku').prop("disabled", false);$('#btn_simpan_peminjaman_buku').html('<i class="ri-database-line"></i> Simpan Informasi Peminjaman Buku');
        }
    });
}
$('#btn_simpan_peminjaman_buku').on('click', function() {
    prosessimpanpeminjaman();
});
