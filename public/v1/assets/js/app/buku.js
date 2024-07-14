let formtambahbuku = $("#form_pendaftaran_buku"), isedit = false;
$(function () {
    $('#status_buku').select2({ dropdownParent: $('#modal_tambah_buku') });
    var placeholders = {
        'select_kategori': 'Tentukan Kategori Buku',
        'select_penerbit': 'Tentukan Penerbit Buku',
        'select_pengarang': 'Tentukan Pengarang Buku',
        'select_laci': 'Tentukan Laci Buku',
        'select_rak': 'Tentukan Rak Buku',
        'select_kategori_form': 'Tentukan Kategori Buku',
        'select_penerbit_form': 'Tentukan Penerbit Buku',
        'select_pengarang_form': 'Tentukan Pengarang Buku',
        'select_laci_form': 'Tentukan Laci Buku',
        'select_rak_form': 'Tentukan Rak Buku'
    };
    loaddatatables();
    $.get('/generate-csrf-token', function(response) {
        $('#select_kategori, #select_penerbit, #select_pengarang, #select_laci, #select_rak, #select_kategori_form, #select_penerbit_form, #select_pengarang_form, #select_laci_form, #select_rak_form').each(function() {
            let $this = $(this);
            let id = $this.attr('id');
            let selectedId = "";
            let selectedElement = $this.attr('id'); 
            let text_data_atribut = "Tidak Ada Ditampilkan";

            let select2Options = {
                placeholder: placeholders[id],
                allowClear: true,
                ajax: {
                    url: baseurlapi + '/perpustakaan/bacaatribut',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('session_id_browser') },
                    method: 'GET',
                    dataType: 'json',
                    delay: 500,
                    data: function (params) {
                        if (selectedElement === 'select_kategori' || selectedElement === 'select_kategori_form') {
                            selectedId = 'kategori';
                        } else if (selectedElement === 'select_penerbit' || selectedElement === 'select_penerbit_form') {
                            selectedId = 'penerbit';
                        } else if (selectedElement === 'select_pengarang' || selectedElement === 'select_pengarang_form') {
                            selectedId = 'pengarang';
                        } else if (selectedElement === 'select_laci' || selectedElement === 'select_laci_form') {
                            selectedId = 'laci';
                        } else if (selectedElement === 'select_rak' || selectedElement === 'select_rak_form') {
                            selectedId = 'rak';
                        }
                        return {
                            _token: response.csrf_token,
                            parameter_pencarian: (typeof params.term === "undefined" ? "" : params.term),
                            kondisi: selectedId,
                            length: 1,
                            start: 0,
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data.data, function (item) {
                                if (selectedElement === 'select_kategori' || selectedElement === 'select_kategori_form') {
                                    text_data_atribut = item.nama_kategori;
                                } else if (selectedElement === 'select_penerbit' || selectedElement === 'select_penerbit_form') {
                                    text_data_atribut = item.nama_penerbit;
                                } else if (selectedElement === 'select_pengarang' || selectedElement === 'select_pengarang_form') {
                                    text_data_atribut = item.nama_pengarang;
                                } else if (selectedElement === 'select_laci' || selectedElement === 'select_laci_form') {
                                    text_data_atribut = item.nama_laci;
                                } else if (selectedElement === 'select_rak' || selectedElement === 'select_rak_form') {
                                    text_data_atribut = item.nama_rak;
                                }
                                return {
                                    text: text_data_atribut,
                                    id: item.id,
                                };
                            })
                        };
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching data:', error);
                    }
                }
            };
            if (selectedElement === 'select_kategori_form' || selectedElement === 'select_penerbit_form' || selectedElement === 'select_pengarang_form' || selectedElement === 'select_laci_form' || selectedElement === 'select_rak_form') {
                select2Options.dropdownParent = $('#modal_tambah_buku');
            }
            $this.select2(select2Options);
        });
    });
});
function loaddatatables(){
$.get('/generate-csrf-token', function(response) {
    $("#daftar_buku").DataTable({
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
            "url": baseurlapi + '/perpustakaan/daftar_buku',
            "type": "GET",
            "beforeSend": function (xhr) { xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('session_id_browser'));},
            "data": function (d) {
                d._token = response.csrf_token;
                d.parameter_pencarian = $('#kotak_pencarian').val();
                d.id_pengarang = $('#select_pengarang').val();
                d.id_rak = $('#select_rak').val();
                d.id_laci = $('#select_laci').val();
                d.id_kategori = $('#select_kategori').val();
                d.id_penerbit = $('#select_penerbit').val();
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
                title: "Informasi Buku",
                render: function(data, type, row, meta) {
                    if (type === 'display') {
                        return "ID Buku : "+row.id_buku+"<br>"+
                        "Nomor Buku : "+row.nomor_buku+"<br>"+
                        "<a href='javascript:void(0)' onclick='detailbuku(" + row.id_buku_id + ")'>Nama Buku: " + row.nama_buku + "</a>";
                    }
                    return data;
                }
            }, 
            {
                title: "Atribut Buku",
                render: function(data, type, row, meta) {
                    if (type === 'display') {
                        return "Pengarang : "+row.nama_pengarang+"<br>"+
                        "Penerbit : "+row.nama_penerbit+"<br>"+
                        "Tahun Terbit : "+row.tahun_terbit+"<br>"
                        ;
                    }
                    return '';
                }
            },
            {
                title: "Lokasi",
                render: function(data, type, row, meta) {
                    if (type === 'display') {
                        return "Lokasi RAK : "+row.nama_rak+"<br>"+
                        "Lokasi Laci : "+row.nama_laci+"<br>"
                        ;
                    }
                    return '';
                }
            }, 
            {
                title: "Status",
                render: function(data, type, row, meta) {
                    if (type === 'display') {
                        return "Total Stok : "+row.stok+"<br>"+
                        "Status : "+(row.status == 1 ? "Aktif" : "Tidak Aktif")+"<br>"
                        ;
                    }
                    return '';
                }
            },
            {
                title: "Aksi",
                render: function(data, type, row, meta) {
                    if (type === 'display') {
                        return `
                        <div class="d-flex justify-content-between gap-2">
                            <button id="editinformasi${row.id_buku_id}" onclick='detailbuku(${row.id_buku_id}, "detail_form")' class="btn btn-outline-success w-100">
                                <i class="ri-shield-user-line"></i> Ubah
                            </button>
                            <button id="hapusinformasi${row.id}" onclick="hapusinformasi('${row.id}', '${row.kode_kelas}', '${row.nama_kelas}', '${row.total_biaya}', '${row.jumlah_bulan}')" class="btn btn-outline-danger w-100">
                                <i class="ri-shield-user-line"></i> Hapus
                            </button>
                        </div>`;
                    }
                    return '';
                }
            },
        ],
    });
});
}
$("#tambah_informasi_buku").on( "click", function() {
    $('#modal_tambah_buku').modal('toggle');
});
$("#filter_tambah_buku").on( "click", function() {
    $('#daftar_buku').DataTable().ajax.reload();
});
$(document).on('click', '.toggle-qrcode', function() {
    let qrCodeId = $(this).data('qrcode-id');
    $("#" + qrCodeId).toggle();
    let buttonText = $(this).text() == 'Show QR Code' ? 'Hide QR Code' : 'Show QR Code';
    $(this).text(buttonText);
});
$("#simpan_buku").on( "click", function() {
    formtambahbuku.addClass('was-validated')
    let idbuku = $("#id_buku").val(), nomor_buku = $("#nomor_buku").val(), nama_buku = $("#nama_buku").val(),kategori = $("#select_kategori_form").val(),pengarang = $("#select_pengarang_form").val(),rak = $("#select_rak_form").val(),laci = $("#select_laci_form").val(),penerbit = $("#select_penerbit_form").val(), tahun_terbit = $("#tahun_terbit").val(),stok_tersedia = $("#stok_tersedia").val(), status_buku = $("#status_buku").val(), keterangan = $("#keterangan").val();   
    if (idbuku === "" || nomor_buku === "" || nama_buku === "" || kategori === "" || pengarang === "" || rak === "" || laci === "" || penerbit === "") return toastr.error("Formulir data buku harus diisi dikarenakan sistem membutuhkan informasi tersebut untuk di kelola", 'Pesan Kesalahan');
    Swal.fire({
        html:
            '<div class="mt-3"><lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop" colors="primary:#f06548,secondary:#f7b84b" style="width:120px;height:120px"></lord-icon><div class="pt-2 fs-15"><h4>Konfirmasi Simpan Informasi Buku!</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menyimpan informasi terbaru dari '+nama_buku+' dengan nomor buku '+nomor_buku+'</p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: (isedit ? 'Ubah' : 'Simpan')+' Informasi',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $('#simpan_buku').prop("disabled",true);$('#simpan_buku').html('<i class="mdi mdi-spin mdi-cog-outline fs-15"></i> Proses Simpan Informasi');
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    "url": baseurlapi + "/perpustakaan/tambah_buku",
                    "type": 'POST',
                    "beforeSend": function (xhr) { xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('session_id_browser'));},
                    "dataType": 'json',
                    "data": {
                        _token: response.csrf_token,
                        id_buku: idbuku,
                        nomor_buku: nomor_buku,
                        nama_buku: nama_buku,
                        id_pengarang: pengarang,
                        id_rak: rak,
                        id_laci: laci,
                        id_kategori: kategori,
                        tahun_terbit: tahun_terbit,
                        id_penerbit: penerbit,
                        stok: stok_tersedia,
                        status: status_buku,
                        keterangan: keterangan,
                        id_penerima: id_user_login,
                        isedit: isedit,
                    },
                    "complete": function() {
                        $('#simpan_buku').prop("disabled", false);$('#simpan_buku').html('Simpan Informasi Buku');
                    },
                    "success": function(response) {
                        if (response.success == false) {
                            return toastr.error(response.message, 'Pesan Kesalahan Code : ' + response.rc);
                        }
                        refreshform();
                        $('#modal_tambah_buku').modal('hide');
                        toastr.success("Proses penyimpanan informasi buku berhasil. Silahkan kelola informasi buku yang sudah ada.");
                        $('#daftar_buku').DataTable().ajax.reload()
                    },
                    "error": function(xhr, status, error) {
                        $('#simpan_buku').prop("disabled", false);$('#simpan_buku').html('Simpan Informasi Buku');
                        toastr.error('Terjadi kesalahan proses PENAMBAHAN INFORMASI BUKU. Silahkan hubungi TIM Terkiat. Pesan Kesalahan : ' + xhr.responseJSON.message, 'Pesan REST API Tambah Informasi Buku');
                    }
                });
            });
        }
    });
});
function refreshform(){
    formtambahbuku.removeClass('was-validated');
    $(':input','#form_pendaftaran_buku').val('');
    $('#select_kategori, #select_penerbit, #select_pengarang, #select_laci, #select_rak, #select_kategori_form, #select_penerbit_form, #select_pengarang_form, #select_laci_form, #select_rak_form').val(null).trigger('change');
    isedit = false
}
function detailbuku(id_buku,jenis){
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            "url": baseurlapi + '/perpustakaan/daftar_buku',
            "type": 'GET',
            "beforeSend": function (xhr) { xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('session_id_browser'));},
            "dataType": 'json',
            "data": {
                _token: response.csrf_token,
                id_buku: id_buku,
                detailbuku:true,
            },
            "complete": function() {},
            "success": function(response) {
                if (response.success == false) {
                    return toastr.error(response.message, 'Pesan Kesalahan Code : ' + response.rc);
                }
                if (jenis === "detail_form"){
                    $('#id_buku').val(response.data.id_buku);
                    $('#nomor_buku').val(response.data.nomor_buku);
                    $('#nama_buku').val(response.data.nama_buku);
                    $('#tahun_terbit').val(response.data.tahun_terbit);
                    $('#stok_tersedia').val(response.data.stok);
                    $('#keterangan').val(response.data.keterangan);
                    let select_kategori_form = new Option(response.data.nama_kategori, response.data.id_kategori, true, false);
                    $('#select_kategori_form').append(select_kategori_form).trigger('change');
                    $("#select_kategori_form").val(response.data.id_kategori).trigger('change');
                    let select_pengarang_form = new Option(response.data.nama_pengarang, response.data.id_pengarang, true, false);
                    $('#select_pengarang_form').append(select_pengarang_form).trigger('change');
                    $("#select_pengarang_form").val(response.data.id_pengarang).trigger('change');
                    let select_rak_form = new Option(response.data.nama_rak, response.data.id_rak, true, false);
                    $('#select_rak_form').append(select_rak_form).trigger('change');
                    $("#select_rak_form").val(response.data.id_rak).trigger('change');
                    let select_laci_form = new Option(response.data.nama_laci, response.data.id_laci, true, false);
                    $('#select_laci_form').append(select_laci_form).trigger('change');
                    $("#select_laci_form").val(response.data.id_hakakses).trigger('change');
                    let select_penerbit_form = new Option(response.data.nama_penerbit, response.data.id_penerbit, true, false);
                    $('#select_penerbit_form').append(select_penerbit_form).trigger('change');
                    $("#select_penerbit_form").val(response.data.id_penerbit).trigger('change');
                    $('#modal_tambah_buku').modal('show');
                }else{
                    $('.id_nomor_buku').html(response.data.id_buku);
                    $('.nomor_buku').html(response.data.nomor_buku);
                    $('.nama_buku').html(response.data.nama_buku);
                    $('.pengarang').html(response.data.nama_pengarang);
                    $('.tahun_terbit').html(response.data.tahun_terbit);
                    $('.kategori_buku').html(response.data.nama_kategori);
                    $('.rak').html(response.data.nama_rak);
                    $('.laci').html(response.data.nama_laci);
                    $('.stok').html(response.data.stok);
                    $('.status').html(response.data.status == 1 ? "Status Aktif" : "Status Tidak Aktif");
                    $('.keterangan_buku').html(response.data.keterangan);
                    $("#qrcode_buku").html("")
                    $("#qrcode_buku").qrcode({text: response.data.id_buku});
                    $('#modal_detail_buku').modal('show');
                }
            },
            "error": function(xhr, status, error) {
                $('#simpan_buku').prop("disabled", false);$('#simpan_buku').html('Simpan Informasi Buku');
                toastr.error('Terjadi kesalahan proses MEMBACA INFORMASI BUKU. Silahkan hubungi TIM Terkiat. Pesan Kesalahan : ' + xhr.responseJSON.message, 'Pesan REST API Tambah Detail Informasi Buku');
            }
        });
    });
}