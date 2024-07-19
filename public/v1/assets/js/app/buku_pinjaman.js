$(function() {
    loaddatatables();
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
                            return "<div class=\"d-flex justify-content-between gap-2\"><button id=\"ubahpeminjam"+row.id_transaksi_buku+"\" onclick=\"ubahpeminjaman('"+row.id_transaksi+"','"+row.id_transaksi_buku+"')\" class=\"btn btn-outline-success w-100\"><i class=\"ri-shield-user-line\"></i> Ubah</button><button id=\"deletepeminjaman"+row.id_transaksi_buku+"\" onclick=\"deletepeminjaman('"+row.id_transaksi+"','"+row.id_transaksi_buku+"')\" class=\"btn btn-outline-danger w-100\"><i class=\"ri-shield-user-line\"></i> Hapus</button></div>";
                        }
                        return '';
                    }
                },
            ],
        });
    });
}
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
                $('#id_siswa').html(response.data[0].nis)
                $('#nama_siswa').html(response.data[0].nama_lengkap)
                $('#nomor_telp').html(response.data[0].nomor_kontak)
                $('#kelas').html(response.data[0].nama_kelas)
                $('#tahun_ajaran').html(response.data[0].keterangan_tahun_ajaran)
                let totalbaris = 0;
                let currentClasses = $('#tabeldetail').attr('class');
                $('#tabeldetail').find('tbody').empty();
                $.each(response.data, function(index, item) {
                    totalbaris = totalbaris + 1;
                    let row = '<tr>' +
                                '<td>' + item.id_buku + '</td>' +
                                '<td>' + item.nama_buku + '</td>' +
                                '<td>' + item.qty_pinjam + ' Buku</td>' +
                                '<td>' + item.denda + '</td>' +
                             '</tr>';
                    $('#tabeldetail').find('tbody').append(row);
                });
                $('#totaljenisbuku').html(totalbaris)
                $('#tabeldetail').attr('class', currentClasses);
                $('#tabeldetail').addClass('table table-bordered dt-responsive nowrap table-striped align-middle');
                $('#informasi_detail_peminjaman').modal('toggle');
            },
            "error": function(xhr, status, error) {
                toastr.error('Terjadi kesalahan proses PENYIMPANAN INFORMASI PEMINJAMAN. Silahkan hubungi TIM Terkiat. Pesan Kesalahan : ' + xhr.responseJSON.message, 'Pesan REST API Tambah Informasi Buku');
            }
        });
    });
}
function deletepeminjaman(notapeminjaman,id_transaksi_buku){
    Swal.fire({
        html:
            '<div class="mt-3"><lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f06548,secondary:#f7b84b" style="width:120px;height:120px"></lord-icon><div class="pt-2 fs-15"><h4>Konfirmasi Hapus No Transaksi '+notapeminjaman+'!</h4><p class="text-muted mx-4 mb-0">Informasi mengenai peminjaman ini akan dihapus dan data tidak dapat dikembalikan lagi. Apakah anda yakin ?</p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Simpan Informasi',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $('#deletepeminjaman'+id_transaksi_buku).prop("disabled",true);$('#deletepeminjaman'+id_transaksi_buku).html('<i class="mdi mdi-spin mdi-cog-outline fs-15"></i> Proses Hapus');
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    "url": baseurlapi + "/perpustakaan/hapus_peminjaman",
                    "type": 'GET',
                    "beforeSend": function (xhr) { xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('session_id_browser'));},
                    "dataType": 'json',
                    "data": {
                        _token: response.csrf_token,
                        notapeminjaman: notapeminjaman,
                    },
                    "complete": function() {
                        $('#deletepeminjaman'+id_transaksi_buku).prop("disabled",false);$('#deletepeminjaman'+id_transaksi_buku).html('<i class=\"ri-shield-user-line\"></i> Hapus');
                    },
                    "success": function(response) {
                        if (response.success == false) {
                            return toastr.error(response.message, 'Pesan Kesalahan Code : ' + response.rc);
                        }
                        toastr.success("Nomor transaksi peminjaman : "+notapeminjaman+" telah dihapus");
                        $('#daftar_peminjaman').DataTable().ajax.reload();
                    },
                    "error": function(xhr, status, error) {
                        $('#deletepeminjaman'+id_transaksi_buku).prop("disabled",false);$('#deletepeminjaman'+id_transaksi_buku).html('<i class=\"ri-shield-user-line\"></i> Hapus');
                        toastr.error('Terjadi kesalahan proses PENYIMPANAN INFORMASI PEMINJAMAN. Silahkan hubungi TIM Terkiat. Pesan Kesalahan : ' + xhr.responseJSON.message, 'Pesan REST API Tambah Informasi Buku');
                    }
                });
            });
        }else{
            $('#btn_simpan_peminjaman_buku').prop("disabled", false);$('#btn_simpan_peminjaman_buku').html('<i class="ri-database-line"></i> Simpan Informasi Peminjaman Buku');
        }
    });
}
$('#kotak_pencarian').on('input', debounce(function (e) { 
    $('#daftar_peminjaman').DataTable().ajax.reload();
}, 500));
$('#pencarian_data').on('click', function() {
    $('#daftar_peminjaman').DataTable().ajax.reload();
});
function ubahpeminjaman(idpeminjaman){
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            "url": baseurlapi + "/perpustakaan/detail_pinjaman",
            "type": 'GET',
            "beforeSend": function (xhr) { xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('session_id_browser'));},
            "dataType": 'json',
            "data": {
                _token: response.csrf_token,
                idpeminjaman:idpeminjaman,
            },
            "complete": function() {
            },
            "success": function(response) {
                if (response.success == false) {
                    return toastr.error(response.message, 'Pesan Kesalahan Code : ' + response.rc);
                }
                $('#informasi_edit_peminjaman').modal('toggle');
            },
            "error": function(xhr, status, error) {
                toastr.error('Terjadi kesalahan proses PENYIMPANAN INFORMASI PEMINJAMAN. Silahkan hubungi TIM Terkiat. Pesan Kesalahan : ' + xhr.responseJSON.message, 'Pesan REST API Tambah Informasi Buku');
            }
        });
    });  
}