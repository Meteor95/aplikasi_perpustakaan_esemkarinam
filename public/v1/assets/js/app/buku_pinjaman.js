$(function() {
    loaddatatables();
    $("#html5-qrcode-button-camera-start, #html5-qrcode-button-camera-end, #html5-qrcode-button-camera-permission").addClass("btn btn-outline-success");
    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader",
        { fps: 24, qrbox: { width: 512, height: 512 } },
    );
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
});
function loaddatatables(){
    $.get('/generate-csrf-token', function(response) {
         let daftar_peminjaman = $("#daftar_peminjaman").DataTable({
            language:{
                "paginate": {
                    "first": '<i class="ri-arrow-go-back-line"></i>', 
                    "last": '<i class="ri-arrow-go-forward-line"></i>', 
                    "next": '<i class="ri-arrow-right-circle-line"></i>', 
                    "previous": '<i class="ri-arrow-left-circle-line"></i>',
                },
            },
            dom: '<"top"ip>rt<"clear">', 
            scrollCollapse: true,
            scrollX: true,
            bFilter: false,
            bInfo : true,
            ordering: false,
            bPaginate: true,
            bProcessing: true, 
            serverSide: true,
            ajax: {
                "url": baseurlapi + '/perpustakaan/daftar_peminjam',
                "type": "GET",
                "beforeSend": function (xhr) { xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('session_id_browser'));},
                "data": function (d) {
                    d._token = response.csrf_token;
                    d.parameter_pencarian = $('#kotak_pencarian').val();
                },
                "dataSrc": function (json) {
                    let detailData = json.data;
                    let mergedData = detailData.map(item => {
                        return {
                            ...item,
                            recordsFiltered: json.recordsFiltered,
                        };
                    });
                    return mergedData;
                }
            },
            infoCallback: function (settings) {
                if (typeof settings.json !== "undefined"){
                    const currentPage = Math.floor(settings._iDisplayStart / settings._iDisplayLength) + 1;
                    const recordsFiltered = settings.json.recordsFiltered;
                    const infoString = 'Halaman ke: ' + currentPage + ' Ditampilkan: ' + 10 + ' Jumlah Data: ' + recordsFiltered+ ' data';
                    return infoString;
                }
            },
            pagingType: "full_numbers",
            columnDefs: [{
                defaultContent: "-",
                targets: "_all"
            }],
            columns: [
                {
                    title: "No",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return meta.row + 1 + meta.settings._iDisplayStart;
                        }
                        return data;
                    }
                },
                {
                    title: "Nota Peminjam",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return "<a href=\"javascript:void(0)\" id=\"detailinformasi"+row.id_user+"\" onclick=\"detailinformasi('"+row.nomor_transkasi+"')\">"+row.nomor_transkasi+"</a>";
                        }
                        return data;
                    }
                }, 
                {
                    title: "Dipinjam Oleh / Admin Oleh",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return "Member : "+row.nama_lengkap+"<br>Admin : "+row.nama_lengkap_pegawai;
                        }
                        return '';
                    }
                },
                {
                    title: "Waktu Peminjaman",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return 'Dipinjam Pada : '+formatDateddmmyyyhhiiss(row.tanggal_peminjaman)+'<br>Dikembalikan Pada : '+formatDateddmmyyyhhiiss(row.tanggal_pengembalian);
                        }
                        return '';
                    }
                }, 
                {
                    title: "Total Buku Dipinjam",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return row.totaljenisbuku_dipinjam+" Jenis Buku<br>"+row.totalbuku_dipinjam+" Total Buku";
                        }
                        return '';
                    }
                },
                {
                    title: "Aksi",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return "<div class=\"d-flex justify-content-between gap-2\"><button id=\"editinformasi"+row.id_user+"\" onclick=\"editinformasi('"+row.username+"','"+row.id_user+"')\" class=\"btn btn-outline-success w-100\"><i class=\"ri-shield-user-line\"></i> Ubah</button ><button id=\"editinformasi"+row.id_user+"\" onclick=\"editinformasi('"+row.username+"','"+row.id_user+"')\" class=\"btn btn-outline-danger w-100\"><i class=\"ri-shield-user-line\"></i> Hapus</button ></div>";
                        }
                        return '';
                    }
                },
            ],
        });
    });
}
function onScanSuccess(decodedText, decodedResult) {
    console.log(`Scan result: ${decodedText}`, decodedResult);
    html5QrcodeScanner.clear();
}
function onScanFailure(error) {
    console.warn(`Code scan error = ${error}`);
  }
$("#btn_aktifkan_camera").on( "click", function() {
    $('#scan_qrcode').modal('toggle');
});
function detailinformasi(nomornota){
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            "url": baseurlapi + "/perpustakaan/detail_pinjaman",
            "type": 'GET',
            "beforeSend": function (xhr) { xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('session_id_browser'));},
            "dataType": 'json',
            "data": {
                _token: response.csrf_token,
                parameter_pencarian:nomornota,
            },
            "complete": function() {
            },
            "success": function(response) {
                if (response.success == false) {
                    return toastr.error(response.message, 'Pesan Kesalahan Code : ' + response.rc);
                }
                
                $('#informasi_detail_peminjaman').modal('toggle');
            },
            "error": function(xhr, status, error) {
                toastr.error('Terjadi kesalahan proses PENYIMPANAN INFORMASI PEMINJAMAN. Silahkan hubungi TIM Terkiat. Pesan Kesalahan : ' + xhr.responseJSON.message, 'Pesan REST API Tambah Informasi Buku');
            }
        });
    });
}