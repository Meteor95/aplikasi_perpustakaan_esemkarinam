let form = $("#form_pendaftaran_ha")
$(function () {
    setTimeout(loaddatatables(), 10);
});
function loaddatatables(){
    $.get('/generate-csrf-token', function(response) {
        $("#daftar_ha").DataTable({
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
                "url": baseurlapi + '/auth/daftarhakakses',
                "type": "GET",
                "beforeSend": function (xhr) { xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('session_id_browser'));},
                "data": function (d) {
                    d._token = response.csrf_token;
                    d.parameter_pencarian = $('#kotak_pencarian').val();
                    d.parameter_pencarian_tabel = true;
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
                defaultContent: "Hak Akses Belum Di Set",
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
                    title: "Nama Grup Hak Akses",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return row.nama_hak_akses;
                        }
                        return data;
                    }
                },
                {
                    title: "Hak Akses Diizinkan",
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            if (row.hakakses_json !== "") {
                                const jsonData = JSON.parse(row.hakakses_json);
                                let buttonHtml = '<div class="row">';
                                jsonData[0].permissions.menus.forEach(menu => {
                                    if (menu.access) {
                                        buttonHtml += '<div class="col-4">';
                                        buttonHtml += `<button class="btn btn-outline-success btn-sm w-100 mt-2">${menu.menu_name}</button>`;
                                        buttonHtml += '</div>';
                                    }
                                });
                                buttonHtml += '</div>';
                                return buttonHtml;
                            }
                        }
                        return data;
                        
                    }
                },
                {
                    title: "Hak Akses Ditolak",
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            if (row.hakakses_json !== "") {
                                const jsonData = JSON.parse(row.hakakses_json);
                                let buttonHtml = '<div class="row">';
                                jsonData[0].permissions.menus.forEach(menu => {
                                    if (!menu.access) {
                                        buttonHtml += '<div class="col-4">';
                                        buttonHtml += `<button class="btn btn-outline-danger btn-sm w-100 mt-2">${menu.menu_name}</button>`;
                                        buttonHtml += '</div>';
                                    }
                                });
                                buttonHtml += '</div>';
                                return buttonHtml;
                            }
                        }
                        return data;
                    }
                },
                {
                    title: "Aksi",
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return "<div class=\"d-flex justify-content-between gap-2\"><button id=\"editha"+row.id+"\" onclick=\"editha('"+row.id+"','"+row.nama_hak_akses+"','"+btoa(row.hakakses_json)+"')\" class=\"btn btn-outline-success w-100\"><i class=\"ri-shield-user-line\"></i> Ubah </button > <button id=\"hapusha"+row.id+"\" onclick=\"hapusha('"+row.id+"','"+row.nama_hak_akses+"')\" class=\"btn btn-outline-danger w-100\"><i class=\"mdi mdi-trash-can-outline\"></i> Hapus</button ></div>";
                        }
                        return data;
                    }
                }
            ],
        });
    });
}
$("#simpan_hak_akses" ).on( "click", function(event) {
    event.preventDefault();
    nama_hak_akses = $("#nama_hak_akses").val()
    form.addClass('was-validated')
    if (nama_hak_akses === "") return toastr.error("Nama dari hak akses harus didefinisikan atau tidak boleh kosong agar sistem mengerti", 'Pesan REST API Hak Akses');
    let checkboxes = $("input[type='checkbox']:checked");
    if (checkboxes.length === 0) return toastr.error('Nampaknya anda belum mengisi 1 pun hak akses mana yang dizinkan untuk grup ini. Silahkan pilih minimal 1 hak akses', 'Pesan REST API Hak Akses');
    Swal.fire({
        html: '<div class="mt-3"><lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop" colors="primary:#f06548,secondary:#f7b84b" style="width:120px;height:120px"></lord-icon><div class="pt-2 fs-15"><h4>Konfirmasi Simpan Informasi !</h4><p class="text-muted mx-4 mb-0">Apakah anda ingin menyimpan infomrasi Hak Akses : <strong>'+$("#nama_hak_akses").val()+'</strong>. Anda dapat mengubah informasi hak akses di kemudian hari sesuai dengan SOP atau Keputusan anda</p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Simpan Informasi',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        $('#simpan_hak_akses').prop("disabled",true);$('#simpan_hak_akses').html('<i class="mdi mdi-spin mdi-cog-outline fs-15"></i> Proses Simpan Hak Akses');
        if (result.isConfirmed) {
            let data = {}, hakAkses = [], roleName = $("#nama_hak_akses").val()
            let permissions = {
                "menus": [],
                "crud_permissions": {
                    "SuratKeluar": {
                        "create": false,
                        "read": false,
                        "update": false,
                        "delete": false
                    }
                }
            };
            $("#tabel_hak_akses tbody tr").each(function(index){
                var menuAkses = $(this).find("td:eq(0)").text();
                var status = $(this).find("input[type=checkbox]").prop("checked");
                var menu = {
                    "menu_name": menuAkses,
                    "access": status
                };
                permissions.menus.push(menu);
                if (menuAkses === "Surat Keluar") {
                    permissions.crud_permissions.SuratKeluar.create = false;
                    permissions.crud_permissions.SuratKeluar.read = false;
                    permissions.crud_permissions.SuratKeluar.update = false;
                    permissions.crud_permissions.SuratKeluar.delete = false;
                }
            });
            var hakAksesObj = {
                "role_name": roleName,
                "permissions": permissions
            };
            hakAkses.push(hakAksesObj);
            data.hak_akses = hakAkses;
            let jsonData = JSON.stringify(hakAkses);
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    "url": baseurlapi + '/auth/tambahhakakses',
                    "type": 'POST',
                    "beforeSend": function (xhr) { xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('session_id_browser'));},
                    "dataType": 'json',
                    "data": {
                        _token: response.csrf_token,
                        nama_hak_akses: nama_hak_akses,
                        hakakses_json: jsonData,
                    },
                    "complete": function() {
                        $('#simpan_hak_akses').prop("disabled", false);$('#simpan_hak_akses').html('Simpan Hak Akses');
                    },
                    "success": function(response) {
                        if (response.success == false) {
                            return toastr.error(response.message, 'Pesan Kesalahan Code : ' + response.rc);
                        }
                        $("#nama_hak_akses").val("").prop("disabled",false)
                        form.removeClass('was-validated')
                        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
                        checkboxes.forEach(checkbox => {checkbox.checked = false;})
                        toastr.success('Hak akses dengan nama group '+nama_hak_akses+' berhasil disimpan','Pesan REST API Hak Akses');
                        $('#daftar_ha').DataTable().ajax.reload();
                    },
                    "error": function(xhr, status, error) {
                        $('#simpan_hak_akses').prop("disabled", false);$('#simpan_hak_akses').html('Simpan Hak Akses');
                        toastr.error('Terjadi kesalahan proses LOGIN. Silahkan hubungi TIM Terkiat. Pesan Kesalahan : ' + xhr.responseJSON.message, 'Pesan REST API Hak Akses');
                    }
                });
            });
        }
    })
});
$('#kotak_pencarian').on('input', debounce(function (e) { 
    $('#daftar_ha').DataTable().ajax.reload();
}, 500));
function editha(id, nama_hak_akses,json_base64){
    const json_hak_akses = JSON.parse(atob(json_base64))
    if (id == 1 || id == 2) return toastr.error('Hak Akses '+nama_hak_akses+' tidak bisa dihapus karena dibutuhkan oleh sistem App Esemkarinam', 'Pesan REST API Hak Akses');
    const configTabLink = document.querySelector('a[href="#nav-border-justified-profile"]');
    $("#nama_hak_akses").val(nama_hak_akses).prop("disabled",true)
    const menus = json_hak_akses[0].permissions.menus;
    menus.forEach(menu => {
        const menuName = menu.menu_name.replace(/ /g, '_').toLowerCase();
        const checkbox = document.getElementById(menuName);
        if (checkbox) {
            checkbox.checked = menu.access;
        }
    });
    var tab = new bootstrap.Tab(configTabLink);
    tab.show();
}
function hapusha(id, nama_hak_akses){
    if (id == 1 || id == 2) return toastr.error('Hak Akses '+nama_hak_akses+' tidak bisa dihapus karena dibutuhkan oleh sistem App Esemkarinam', 'Pesan REST API Hak Akses');
    $('#hapusha'+id).prop("disabled",true);$('#hapusha'+id).html('<i class="mdi mdi-spin mdi-cog-outline fs-15"></i> Proses Hapus');
    Swal.fire({
        html:
            '<div class="mt-3"><lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f06548,secondary:#f7b84b" style="width:120px;height:120px"></lord-icon><div class="pt-2 fs-15"><h4>Konfirmasi Hapus Hak Akses '+nama_hak_akses+'!</h4><p class="text-muted mx-4 mb-0">Jika hak akses ini dihapus maka semua pengguna yng menggunakan hak akses ini akan dialihkan ke HAK AKSES <strong>FULL NON EMPLOYED</strong>. Jadi admin harus merubah 1 per 1 jika ingin mengembalikan seperti semula</p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Hapus Hak Akses',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $('#hapus_pengguna').prop("disabled",true);$('#hapus_pengguna').html('<i class="mdi mdi-spin mdi-cog-outline fs-15"></i> Proses Hapus Informasi');
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    "url": baseurlapi + '/auth/hapushakakses',
                    "type": 'DELETE',
                    "beforeSend": function (xhr) { xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('session_id_browser'));},
                    "dataType": 'json',
                    "data": {
                        _token: response.csrf_token,
                        idhakakses:id,
                        nama_hak_akses:nama_hak_akses,
                    },
                    "complete": function() {
                        $('#hapusha'+id).prop("disabled",false);$('#hapusha'+id).html('<i class=\"mdi mdi-trash-can-outline\"></i> Hapus');
                    },
                    "success": function(response) {
                        if (response.success == false) {
                            return toastr.error(response.message, 'Pesan Kesalahan Code : ' + response.rc);
                        }
                        toastr.success('Semua pengguna hak akses yang memiliki ID HAK AKSES '+nama_hak_akses+' akan dialihkan ke ID FULL NON EMPLOYED '+response.message);
                        $('#daftar_ha').DataTable().ajax.reload()
                    },
                    "error": function(xhr, status, error) {
                        $('#hapusha'+id).prop("disabled",false);$('#hapusha'+id).html('<i class=\"mdi mdi-trash-can-outline\"></i> Hapus');
                        toastr.error('Terjadi kesalahan proses LOGIN. Silahkan hubungi TIM Terkiat. Pesan Kesalahan : ' + xhr.responseJSON.message, 'Pesan REST API Hapus Hak Akses');
                    }
                });
            });
        }
        $('#hapusha'+id).prop("disabled",false);$('#hapusha'+id).html('<i class=\"mdi mdi-trash-can-outline\"></i> Hapus');
    });
}

