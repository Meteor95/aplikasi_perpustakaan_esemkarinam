let formpenerbit = $("#form_penerbit"),formpengarang = $("#form_pengarang"),formkategori = $("#form_kategori"),formlaci = $("#form_laci"),formrak = $("#form_rak"), isedit = false,hrefId = "#penerbit_buku";
$(function () {
    setTimeout(loaddatatables('penerbit'), 10);
    setTimeout(loaddatatables('pengarang'), 10);
    setTimeout(loaddatatables('kategori'), 10);
    setTimeout(loaddatatables('laci'), 10);
    setTimeout(loaddatatables('rak'), 10);
})
function refreshdata(){
    if (hrefId === "#penerbit_buku"){
        $('#tabel_atribut_penerbit').DataTable().ajax.reload();
    }else if(hrefId === "#pengarang_buku"){
        $('#tabel_atribut_pengarang').DataTable().ajax.reload();
    }else if(hrefId === "#kategori_buku"){
        $('#tabel_atribut_kategori').DataTable().ajax.reload();
    }else if(hrefId === "#laci_buku"){
        $('#tabel_atribut_laci').DataTable().ajax.reload();
    }else if(hrefId === "#rak_buku"){
        $('#tabel_atribut_rak').DataTable().ajax.reload();
    }
    $("#penerbit_nama").val("")
    $("#penerbit_keterangan").val("")
    $("#pengarang_nama").val("")
    $("#pengarang_keterangan").val("")
    $("#kategori_nama").val("")
    $("#kategori_keterangan").val("")
    $("#laci_nama").val("")
    $("#laci_keterangan").val("")
    $("#rak_nama").val("")
    $("#rak_keterangan").val("")
    formpenerbit.removeClass('was-validated')
    formpengarang.removeClass('was-validated')
    formkategori.removeClass('was-validated')
    formlaci.removeClass('was-validated')
    formrak.removeClass('was-validated')
    
}
$('.nav-link').on('click', function(){
    hrefId = $(this).attr('href');
    refreshdata(hrefId)
});
$('.btn_simpan_atribut').click(function(event) {
    prosessimpan(event, hrefId);
});
function prosessimpan(event,idatribut){
    event.preventDefault();
    let data = {};
    let field1 = "", field2 = "", pesan = "", buttontext = "";
    function setFormValues(selector1, selector2, formClass, message, buttonText) {
        field1 = $(selector1).val();
        field2 = $(selector2).val();
        formClass.addClass('was-validated');
        pesan = message;
        buttontext = buttonText;
    }    
    if (idatribut === "#penerbit_buku"){
        setFormValues("#penerbit_nama", "#penerbit_keterangan", formpenerbit,
            "Formulir PENERBIT masih belum lengkap. Silahkan lengkapi terlebih dahulu", 
            "Simpan Penerbit");
        data = {
            penerbit_nama: field1,
            penerbit_keterangan: field2,
            kondisi: idatribut.replace('#', ''),
            isedit: isedit,
        };
    }else if(idatribut === "#pengarang_buku"){
        setFormValues("#pengarang_nama", "#pengarang_keterangan", formpengarang,
            "Formulir PENGARANG masih belum lengkap. Silahkan lengkapi terlebih dahulu", 
            "Simpan Pengarang");
        data = {
            pengarang_nama: field1,
            pengarang_keterangan: field2,
            kondisi: idatribut.replace('#', ''),
            isedit: isedit,
        };
    }else if(idatribut === "#kategori_buku"){
        setFormValues("#kategori_nama", "#kategori_keterangan", formkategori,
            "Formulir KATEGORI masih belum lengkap. Silahkan lengkapi terlebih dahulu", 
            "Simpan Kategori");
        data = {
            kategori_nama: field1,
            kategori_keterangan: field2,
            kondisi: idatribut.replace('#', ''),
            isedit: isedit,
        };
    }else if(idatribut === "#laci_buku"){
        setFormValues("#laci_nama", "#laci_keterangan", formkategori,
            "Formulir LACI masih belum lengkap. Silahkan lengkapi terlebih dahulu", 
            "Simpan Laci");
        data = {
            laci_nama: field1,
            laci_keterangan: field2,
            kondisi: idatribut.replace('#', ''),
            isedit: isedit,
        };
    }else if(idatribut === "#rak_buku"){
        setFormValues("#rak_nama", "#rak_keterangan", formkategori,
            "Formulir RAK masih belum lengkap. Silahkan lengkapi terlebih dahulu", 
            "Simpan Rak");
        data = {
            rak_nama: field1,
            rak_keterangan: field2,
            kondisi: idatribut.replace('#', ''),
            isedit: isedit,
        };
    }
    if (field1 === "" || field2 === "") return toastr.error(pesan, 'Pesan Kesalahan');
    Swal.fire({
        html:
            '<div class="mt-3"><lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop" colors="primary:#f06548,secondary:#f7b84b" style="width:120px;height:120px"></lord-icon><div class="pt-2 fs-15"><h4>Konfirmasi Simpan Informasi Penerbit !</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menyimpan informasi terbaru dari '+field1+'</p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: (isedit ? 'Ubah' : 'Simpan')+' Informasi',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            if (idatribut === "#penerbit_buku"){
                $('#btn_tambah_penerbit').prop("disabled",true);$('#btn_tambah_penerbit').html('<i class="mdi mdi-spin mdi-cog-outline fs-15"></i> Proses Simpan Atribut');
            }else if (idatribut === "#pengarang_buku"){
                $('#btn_tambah_pengarang').prop("disabled",true);$('#btn_tambah_pengarang').html('<i class="mdi mdi-spin mdi-cog-outline fs-15"></i> Proses Simpan Atribut');
            }else if (idatribut === "#kategori_buku"){
                $('#btn_tambah_kategori').prop("disabled",true);$('#btn_tambah_kategori').html('<i class="mdi mdi-spin mdi-cog-outline fs-15"></i> Proses Simpan Atribut');
            }else if (idatribut === "#laci_buku"){
                $('#btn_tambah_laci').prop("disabled",true);$('#btn_tambah_laci').html('<i class="mdi mdi-spin mdi-cog-outline fs-15"></i> Proses Simpan Atribut');
            }else if (idatribut === "#rak_buku"){
                $('#btn_tambah_rak').prop("disabled",true);$('#btn_tambah_rak').html('<i class="mdi mdi-spin mdi-cog-outline fs-15"></i> Proses Simpan Atribut');
            }
            
            $.get('/generate-csrf-token', function(response) {
                data._token = response.csrf_token;
                $.ajax({
                    "url": baseurlapi + '/perpustakaan/atribut',
                    "type": 'POST',
                    "beforeSend": function (xhr) { xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('session_id_browser'));},
                    "dataType": 'json',
                    "data": data,
                    "complete": function() {
                        if (idatribut === "#penerbit_buku"){
                            $('#btn_tambah_penerbit').prop("disabled",false);$('#btn_tambah_penerbit').html(buttontext);
                        }else if (idatribut === "#pengarang_buku"){
                            $('#btn_tambah_pengarang').prop("disabled",false);$('#btn_tambah_pengarang').html(buttontext);
                        }else if (idatribut === "#kategori_buku"){
                            $('#btn_tambah_kategori').prop("disabled",false);$('#btn_tambah_kategori').html(buttontext);
                        }else if (idatribut === "#laci_buku"){
                            $('#btn_tambah_laci').prop("disabled",false);$('#btn_tambah_laci').html(buttontext);
                        }else if (idatribut === "#rak_buku"){
                            $('#btn_tambah_rak').prop("disabled",false);$('#btn_tambah_rak').html(buttontext);
                        }
                    },
                    "success": function(response) {
                        if (response.success == false) {
                            return toastr.error(response.message, 'Pesan Kesalahan Code : ' + response.rc);
                        }
                        refreshdata(idatribut)
                        toastr.success("Informasi "+field1+" berhasil ditambahkan pada database","Simpan Atribut "+field1);
                    },
                    "error": function(xhr, status, error) {
                        if (idatribut === "#penerbit_buku"){
                            $('#btn_tambah_penerbit').prop("disabled",false);$('#btn_tambah_penerbit').html(buttontext);
                        }else if (idatribut === "#pengarang_buku"){
                            $('#btn_tambah_pengarang').prop("disabled",false);$('#btn_tambah_pengarang').html(buttontext);
                        }else if (idatribut === "#kategori_buku"){
                            $('#btn_tambah_kategori').prop("disabled",false);$('#btn_tambah_kategori').html(buttontext);
                        }else if (idatribut === "#laci_buku"){
                            $('#btn_tambah_laci').prop("disabled",false);$('#btn_tambah_laci').html(buttontext);
                        }else if (idatribut === "#rak_buku"){
                            $('#btn_tambah_rak').prop("disabled",false);$('#btn_tambah_rak').html(buttontext);
                        }
                        toastr.error('Terjadi kesalahan pada simpan atribut. Silahkan hubungi TIM Terkiat. Pesan Kesalahan : ' + xhr.responseJSON.message, 'Pesan REST API Tambah Pegawai');
                    }
                });
            });
        }
    });
}
function loaddatatables(idtabel){
    let columnsConfig = [];
    if (idtabel === "penerbit") {
        columnsConfig = [
            {
                title: "No",
                render: function (data, type, row, meta) {
                    if (type === 'display') {
                        return meta.row + 1 + meta.settings._iDisplayStart;
                    }
                    return data;
                }
            },
            {
                title: "Nama Penerbit",
                render: function (data, type, row, meta) {
                    if (type === 'display') {
                        return row.nama_penerbit;
                    }
                    return data;
                }
            },
            {
                title: "Keterangan Penerbit",
                render: function (data, type, row, meta) {
                    if (type === 'display') {
                        return row.keterangan;
                    }
                    return '';
                }
            },
            {
                title: "Aksi",
                render: function (data, type, row, meta) {
                    if (type === 'display') {
                        return "<div class=\"d-flex justify-content-between gap-2\"><button id=\"editpenerbit" + row.id + "\" onclick=\"editinformasi('" + row.username + "','" + row.id + "')\" class=\"btn btn-outline-success w-100\"><i class=\"ri-shield-user-line\"></i> Ubah Data</button></div>";
                    }
                    return '';
                }
            }
        ];
    }else if(idtabel === "pengarang") {
        columnsConfig = [
            {
                title: "No",
                render: function (data, type, row, meta) {
                    if (type === 'display') {
                        return meta.row + 1 + meta.settings._iDisplayStart;
                    }
                    return data;
                }
            },
            {
                title: "Nama Pengarang",
                render: function (data, type, row, meta) {
                    if (type === 'display') {
                        return row.nama_pengarang;
                    }
                    return data;
                }
            },
            {
                title: "Keterangan Pengarang",
                render: function (data, type, row, meta) {
                    if (type === 'display') {
                        return row.keterangan;
                    }
                    return '';
                }
            },
            {
                title: "Aksi",
                render: function (data, type, row, meta) {
                    if (type === 'display') {
                        return "<div class=\"d-flex justify-content-between gap-2\"><button id=\"editinformasi" + row.id + "\" onclick=\"hapuspenerbit('" + row.username + "','" + row.id + "')\" class=\"btn btn-outline-success w-100\"><i class=\"ri-shield-user-line\"></i> Hapus Data</button></div>";
                    }
                    return '';
                }
            }
        ];
    }else if(idtabel === "kategori") {
        columnsConfig = [
            {
                title: "No",
                render: function (data, type, row, meta) {
                    if (type === 'display') {
                        return meta.row + 1 + meta.settings._iDisplayStart;
                    }
                    return data;
                }
            },
            {
                title: "Nama Kategori",
                render: function (data, type, row, meta) {
                    if (type === 'display') {
                        return row.nama_kategori;
                    }
                    return data;
                }
            },
            {
                title: "Keterangan Kategori",
                render: function (data, type, row, meta) {
                    if (type === 'display') {
                        return row.keterangan;
                    }
                    return '';
                }
            },
            {
                title: "Aksi",
                render: function (data, type, row, meta) {
                    if (type === 'display') {
                        return "<div class=\"d-flex justify-content-between gap-2\"><button id=\"editinformasi" + row.id + "\" onclick=\"hapuspenerbit('" + row.username + "','" + row.id + "')\" class=\"btn btn-outline-success w-100\"><i class=\"ri-shield-user-line\"></i> Hapus Data</button></div>";
                    }
                    return '';
                }
            }
        ];
    }else if(idtabel === "laci") {
        columnsConfig = [
            {
                title: "No",
                render: function (data, type, row, meta) {
                    if (type === 'display') {
                        return meta.row + 1 + meta.settings._iDisplayStart;
                    }
                    return data;
                }
            },
            {
                title: "Nama Laci",
                render: function (data, type, row, meta) {
                    if (type === 'display') {
                        return row.nama_laci;
                    }
                    return data;
                }
            },
            {
                title: "Keterangan Laci",
                render: function (data, type, row, meta) {
                    if (type === 'display') {
                        return row.keterangan;
                    }
                    return '';
                }
            },
            {
                title: "Aksi",
                render: function (data, type, row, meta) {
                    if (type === 'display') {
                        return "<div class=\"d-flex justify-content-between gap-2\"><button id=\"editinformasi" + row.id + "\" onclick=\"hapuspenerbit('" + row.username + "','" + row.id + "')\" class=\"btn btn-outline-success w-100\"><i class=\"ri-shield-user-line\"></i> Hapus Data</button></div>";
                    }
                    return '';
                }
            }
        ];
    }else if(idtabel === "rak") {
        columnsConfig = [
            {
                title: "No",
                render: function (data, type, row, meta) {
                    if (type === 'display') {
                        return meta.row + 1 + meta.settings._iDisplayStart;
                    }
                    return data;
                }
            },
            {
                title: "Nama Rak",
                render: function (data, type, row, meta) {
                    if (type === 'display') {
                        return row.nama_rak;
                    }
                    return data;
                }
            },
            {
                title: "Keterangan Rak",
                render: function (data, type, row, meta) {
                    if (type === 'display') {
                        return row.keterangan;
                    }
                    return '';
                }
            },
            {
                title: "Aksi",
                render: function (data, type, row, meta) {
                    if (type === 'display') {
                        return "<div class=\"d-flex justify-content-between gap-2\"><button id=\"editinformasi" + row.id + "\" onclick=\"hapuspenerbit('" + row.username + "','" + row.id + "')\" class=\"btn btn-outline-success w-100\"><i class=\"ri-shield-user-line\"></i> Hapus Data</button></div>";
                    }
                    return '';
                }
            }
        ];
    }
    $.get('/generate-csrf-token', function(response) {
        $("#tabel_atribut_"+idtabel).DataTable({
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
                "url": baseurlapi + '/perpustakaan/bacaatribut',
                "type": "GET",
                "beforeSend": function (xhr) { xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('session_id_browser'));},
                "data": function (d) {
                    d._token = response.csrf_token;
                    d.parameter_pencarian = $('#kotak_pencarian_'+idtabel).val();
                    d.kondisi = idtabel;
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
            columns: columnsConfig
        });
    });
}