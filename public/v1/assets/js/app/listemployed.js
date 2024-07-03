let form = $("#form_pendaftaran"), isedit = false;
$(function () {
    setTimeout(loaddatatables(), 10);
    $('#select2_pegawai').select2({ dropdownParent: $('#modal_tambah_pegawai') });
    $('#select2_agama').select2({ dropdownParent: $('#modal_tambah_pegawai') });
    $('#select2_jeniskelamin').select2({ dropdownParent: $('#modal_tambah_pegawai') });
    $.get('/generate-csrf-token', function(response) {
        $('#select2_hak_akses').select2({ 
            dropdownParent: $('#modal_tambah_pegawai') ,
            placeholder: 'Tentukan Hak Akses Fitur',
            ajax: {
                url: baseurlapi + '/auth/daftarhakakses',
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('session_id_browser') },
                method: 'GET',
                dataType: 'json',
                delay: 500,
                data: function (params) {
                    return {
                        _token : response.csrf_token,
                        parameter_pencarian : (typeof params.term === "undefined" ? "" : params.term),
                    }
                },
                processResults: function (data) {
                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                text: item.nama_hak_akses,
                                id: item.id,
                            }
                        })
                    }
                    
                },
                error: function(xhr, status, error) {
                    
                }
            },
        });
    });
    $('#tanggal_lahir').datepicker({uiLibrary: 'bootstrap5',format: 'dd-mm-yyyy'});
});
function loaddatatables(){
$.get('/generate-csrf-token', function(response) {
    $("#daftar_pegawai").DataTable({
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
            "url": baseurlapi + '/pegawai/daftar',
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
                title: "NIP",
                render: function(data, type, row, meta) {
                    if (type === 'display') {
                        return "<a href=\"javascript:void(0)\" id=\"detailinformasi"+row.id_user+"\" onclick=\"detailinformasi('"+row.username+"','"+row.id_user+"')\">"+row.nip+"</a>";
                    }
                    return data;
                }
            }, 
            {
                title: "Nama Lengkap",
                render: function(data, type, row, meta) {
                    if (type === 'display') {
                        return "<a href=\"javascript:void(0)\" id=\"detailinformasi"+row.id_user+"\" onclick=\"detailinformasi('"+row.username+"','"+row.id_user+"')\">"+row.nama_lengkap+"</a>";
                    }
                    return '';
                }
            },
            {
                title: "Surel",
                render: function(data, type, row, meta) {
                    if (type === 'display') {
                        return row.email;
                    }
                    return '';
                }
            }, 
            {
                title: "Nama Pengguna",
                render: function(data, type, row, meta) {
                    if (type === 'display') {
                        return row.username;
                    }
                    return '';
                }
            },
            {
                title: "Status Hak Akses",
                render: function(data, type, row, meta) {
                    if (type === 'display') {
                        return row.nama_hak_akses;
                    }
                    return '';
                }
            },
            {
                title: "Aksi",
                render: function(data, type, row, meta) {
                    if (type === 'display') {
                        return "<div class=\"d-flex justify-content-between gap-2\"><button id=\"editinformasi"+row.id_user+"\" onclick=\"editinformasi('"+row.username+"','"+row.id_user+"')\" class=\"btn btn-outline-success w-100\"><i class=\"ri-shield-user-line\"></i> Ubah Data</button ></div>";
                    }
                    return '';
                }
            },
        ],
    });
});
}
$('#kotak_pencarian').on('input', debounce(function (e) { 
    $('#daftar_pegawai').DataTable().ajax.reload();
}, 500));
$("#btn_tambah_pengguna" ).on( "click", function() {
    $('#modal_tambah_pegawai').modal('toggle');
});
$('#togglePassword').click(function(event) {
    event.preventDefault();
    let icon = $(this).find('i');
    icon.toggleClass('bi-eye bi-eye-slash');
    let passwordField = $('#katansandi');
    let fieldType = passwordField.attr('type') === 'password' ? 'text' : 'password';
    passwordField.attr('type', fieldType);
    $(this).toggleClass('btn-outline-secondary btn-outline-danger');
});
$('#simpan_informasi_kredential').click(function() {
    let username = $("#username").val(), email = $("#surel").val(), password = $("#katansandi").val(), nip = $("#nip").val(), nama_lengkap = $("#nama_lengkap").val(),idhakakses = $("#select2_hak_akses").val()
    isedit ? $('#katansandi').removeAttr('required') : '' ;
    form.addClass('was-validated')
    if (username === "" || email === "" || (password === "" && !isedit) || idhakakses === null || nip === "" || nama_lengkap === "") return toastr.error("Informasi Haks Akses, Alamat Surel, Nama Pengguna, dan " + (isedit ? 'NIP' : 'Kata Sandi') + " dalam kredential dibutuhkan. Sedangkan NIP dana Nama Lengkap sebagai identitas pegawai", 'Pesan Kesalahan');
    if (!isEmail(email)) return toastr.error('Format surel dibutuhkan. EX: hai@erayadigital.co.id');
    let endpoint = (isedit ? '/pegawai/ubahpegawai' : '/auth/pendaftaran_web')
    Swal.fire({
        html:
            '<div class="mt-3"><lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop" colors="primary:#f06548,secondary:#f7b84b" style="width:120px;height:120px"></lord-icon><div class="pt-2 fs-15"><h4>Konfirmasi Simpan Informasi Pegawai !</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menyimpan informasi terbaru dari '+$("#username").val()+' dengan alamat surel '+$("#surel").val()+'</p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: (isedit ? 'Ubah' : 'Simpan')+' Informasi',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $('#simpan_informasi_kredential').prop("disabled",true);$('#simpan_informasi_kredential').html('<i class="mdi mdi-spin mdi-cog-outline fs-15"></i> Proses Simpan Informasi');
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    "url": baseurlapi + endpoint,
                    "type": 'POST',
                    "beforeSend": function (xhr) { xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('session_id_browser'));},
                    "dataType": 'json',
                    "data": {
                        _token: response.csrf_token,
                        username: username,
                        email: email,
                        password: password,
                        idhakakses: idhakakses,
                        /*informasipengguna*/
                        nip: $("#nip").val(),
                        nama_lengkap: $("#nama_lengkap").val(),
                        tempat_lahir: $("#tempat_lahir").val(),
                        tanggal_lahir: $("#tanggal_lahir").val(),
                        jenis_kelamin: $("#select2_jeniskelamin").val(),
                        agama: $("#select2_agama").val(),
                        alamat: $("#alamat").val(),
                        nomor_telepon: $("#nomor_telepon").val(),
                        jabatan: $("#jabatan").val(),
                        unit_kerja: $("#unit_kerja").val(),
                        status_kepegawaian: $("#select2_pegawai").val(),
                        catatan_lain: $("#catatan_lain").val(),
                        isedit: isedit,
                        id_user: $("#user_id").val(),
                    },
                    "complete": function() {
                        $('#simpan_informasi_kredential').prop("disabled", false);$('#simpan_informasi_kredential').html('Simpan Kredential dan Info Pengguna');
                    },
                    "success": function(response) {
                        if (response.success == false) {
                            return toastr.error(response.message, 'Pesan Kesalahan Code : ' + response.rc);
                        }
                        refreshform();
                        $('#modal_tambah_pegawai').modal('hide');
                        toastr.success('Informasi kredential atas email : '+$("#surel").val()+" berhasil disimpan ke database. Silahkan lakukan operasi App Esemkarinam jika berkenan");
                        $('#daftar_pegawai').DataTable().ajax.reload()
                    },
                    "error": function(xhr, status, error) {
                        $('#simpan_informasi_kredential').prop("disabled", false);$('#simpan_informasi_kredential').html('Simpan Kredential dan Info Pengguna');
                        toastr.error('Terjadi kesalahan proses LOGIN. Silahkan hubungi TIM Terkiat. Pesan Kesalahan : ' + xhr.responseJSON.message, 'Pesan REST API Tambah Pegawai');
                    }
                });
            });
        }
    });
});
function editinformasi(username,user_id){
    isedit = true;
    $('#user_id').val(user_id)
    $('#editinformasi'+user_id).prop("disabled",true);$('#editinformasi'+user_id).html('<i class="mdi mdi-spin mdi-cog-outline fs-15"></i> Sedang Diubah');
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            "url": baseurlapi + '/pegawai/detailpegawai',
            "type": 'GET',
            "beforeSend": function (xhr) { xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('session_id_browser'));},
            "dataType": 'json',
            "data": {
                _token: response.csrf_token,
                user_id: user_id,
            },
            "complete": function() {
                $('#editinformasi'+user_id).prop("disabled",false);$('#editinformasi'+user_id).html('<i class="ri-shield-user-line"></i> Ubah Data');
            },
            "success": function(response) {
                if (response.success == false) {
                    return toastr.error(response.message, 'Pesan Kesalahan Code : ' + response.rc);
                }
                $("#katansandi").attr("placeholder", "Kosongkan jikalau anda tidak mengubah kata sandi");
                $("#username").val(response.data.id_user)
                $("#username").val(response.data.username)
                $("#surel").val(response.data.email)
                let newOption = new Option(response.data.nama_hak_akses, response.data.id_hakakses, true, false);
                $('#select2_hak_akses').append(newOption).trigger('change');
                $("#select2_hak_akses").val(response.data.id_hakakses).trigger('change');
                $("#nip").val(response.data.nip)
                $("#nama_lengkap").val(response.data.nama_lengkap)
                $("#tempat_lahir").val(response.data.tempat_lahir)
                $("#tanggal_lahir").val(response.data.tanggal_lahir == null ? "" : response.data.tanggal_lahir.split("-").reverse().join("-"))
                $("#select2_jeniskelamin").val(response.data.jenis_kelamin).trigger('change');
                $("#alamat").val(response.data.alamat)
                $("#nomor_telepon").val(response.data.nomor_telepon)
                $("#catatan_lain").val(response.data.catatan_lain)
                $('#modal_tambah_pegawai').modal('show');
            },
            "error": function(xhr, status, error) {
                $('#editinformasi'+user_id).prop("disabled",false);$('#editinformasi'+user_id).html('<i class="ri-shield-user-line"></i> Ubah Data');
                toastr.error('Terjadi kesalahan proses UBAH DATA '+username+'. Silahkan hubungi TIM Terkiat. Pesan Kesalahan : ' + xhr.responseJSON.message, 'Pesan Rest API Ubah Data '+username);
            }
        });
    });
}
$("#modal_tambah_pegawai").on('hide.bs.modal', function() {
    refreshform()
    isedit = false
});
function detailinformasi(username,user_id){
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            "url": baseurlapi + '/pegawai/detailpegawai',
            "type": 'GET',
            "beforeSend": function (xhr) { xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('session_id_browser'));},
            "dataType": 'json',
            "data": {
                _token: response.csrf_token,
                user_id: user_id,
            },
            "complete": function() {
                $('#editinformasi'+user_id).prop("disabled",false);$('#editinformasi'+user_id).html('<i class="ri-shield-user-line"></i> Ubah Data');
            },
            "success": function(response) {
                if (response.success == false) {
                    return toastr.error(response.message, 'Pesan Kesalahan Code : ' + response.rc);
                }
                $(".user_id").html(response.data.id_user)
                $(".username").html(response.data.username)
                $(".surel").html(response.data.email)
                //informasipegawai
                $(".nip").html(response.data.nip)
                $(".nama_lengkap").html(response.data.nama_lengkap)
                $(".tempat_lahir").html(response.data.tempat_lahir == "" ? "Tidak Diketahui" : response.data.tempat_lahir)
                $(".tanggal_lahir").html(response.data.tanggal_lahir == null ? "??-??-????" : response.data.tanggal_lahir.split("-").reverse().join("-"))
                $(".jenis_kelamin").html(response.data.jenis_kelamin)
                $(".agama").html(response.data.agama)
                $(".alamat").html(response.data.alamat == "" ? "Pengguna ini masih malu malu untuk memberitahukan alamatnya" :response.data.alamat)
                $(".nomor_telepon").html(response.data.nomor_telepon == "" ? "Ciee artis, nomor telepon dirahasiakan": response.data.nomor_telepon)
                $(".jabatan").html(response.data.jabatan == "" ? "Jabatan belum ditentukan" : response.data.jabatan)
                $(".unit_kerja").html(response.data.unit_kerja == "" ? "Unit Kerja belum ditentukan" : response.data.unit_kerja)
                $(".status_pegawai").html(response.data.status_kepegawaian)
                $(".catatan_lain").html(response.data.catatan_lain)
                $('#modal_detail_pegawai').modal('show');
            },
            "error": function(xhr, status, error) {
                $('#editinformasi'+user_id).prop("disabled",false);$('#editinformasi'+user_id).html('<i class="ri-shield-user-line"></i> Ubah Data');
                toastr.error('Terjadi kesalahan proses UBAH DATA '+username+'. Silahkan hubungi TIM Terkiat. Pesan Kesalahan : ' + xhr.responseJSON.message, 'Pesan Rest API Ubah Data '+username);
            }
        });
    });
}
$("#hapus_pengguna").on( "click", function() {
    Swal.fire({
        html:
            '<div class="mt-3"><lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f06548,secondary:#f7b84b" style="width:120px;height:120px"></lord-icon><div class="pt-2 fs-15"><h4>Konfirmasi Hapus Informasi Pegawai !</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menghapus informasi dari '+$(".username").html()+' dengan alamat surel '+$(".surel").html()+'. Pengguna yang dihapus akan terlogout dan tidak bisa digunakan lagi. Tetapi BERKAS SURAT masih tetap ada</p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Hapus Informasi',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $('#hapus_pengguna').prop("disabled",true);$('#hapus_pengguna').html('<i class="mdi mdi-spin mdi-cog-outline fs-15"></i> Proses Hapus Informasi');
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    "url": baseurlapi + '/auth/hapuspegawai',
                    "type": 'POST',
                    "beforeSend": function (xhr) { xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('session_id_browser'));},
                    "dataType": 'json',
                    "data": {
                        _token: response.csrf_token,
                        username: $(".username").html(),
                    },
                    "complete": function() {
                        $('#hapus_pengguna').prop("disabled", false);$('#hapus_pengguna').html('<i class="ri-delete-bin-5-line"></i> Hapus Pengguna');
                    },
                    "success": function(response) {
                        if (response.success == false) {
                            return toastr.error(response.message, 'Pesan Kesalahan Code : ' + response.rc);
                        }
                        $('#modal_detail_pegawai').modal('hide');
                        toastr.success('Informasi kredential atas email : '+$("#surel").val()+" berhasil dihapus dari database. Data yang dihapus tidak bisa dikembalikan dengan cara apapun");
                        $('#daftar_pegawai').DataTable().ajax.reload()
                    },
                    "error": function(xhr, status, error) {
                        $('#hapus_pengguna').prop("disabled", false);$('#hapus_pengguna').html('<i class="ri-delete-bin-5-line"></i> Hapus Pengguna');
                        toastr.error('Terjadi kesalahan proses LOGIN. Silahkan hubungi TIM Terkiat. Pesan Kesalahan : ' + xhr.responseJSON.message, 'Pesan REST API Hapus Pegawai');
                    }
                });
            });
        }
    });
});
function refreshform(){ 
    form.removeClass('was-validated');
    $(':input','#form_pendaftaran').val('');
    isedit = false
}