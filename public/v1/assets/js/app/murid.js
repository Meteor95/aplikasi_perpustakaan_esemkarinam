let form = $("#form_pendaftaran"), isedit = false;
$(function () {
    setTimeout(loaddatatables(), 10);
    $.get('/generate-csrf-token', function(response) {
        $('#select2_hak_akses').select2({ 
            dropdownParent: $('#modal_tambah_siswa') ,
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
    $.get('/generate-csrf-token', function(response) {
        $('#select2_kelas').select2({ 
            dropdownParent: $('#modal_tambah_siswa') ,
            placeholder: 'Tentukan Kelas',
            ajax: {
                url: baseurlapi + '/murid/ajaxtabelkelas',
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('session_id_browser') },
                method: 'GET',
                dataType: 'json',
                delay: 500,
                data: function (params) {
                    return {
                        _token : response.csrf_token,
                        parameter_pencarian : (typeof params.term === "undefined" ? "" : params.term),
                        select2: true,
                    }
                },
                processResults: function (data) {
                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                text: "["+item.kode_kelas+"] "+item.nama_kelas,
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
    $.get('/generate-csrf-token', function(response) {
        $('#select2_tahun_ajaran').select2({ 
            dropdownParent: $('#modal_tambah_siswa') ,
            placeholder: 'Tentukan Tahun Ajaran Saat Ini',
            ajax: {
                url: baseurlapi + '/murid/ajaxtabeltabelajaran',
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('session_id_browser') },
                method: 'GET',
                dataType: 'json',
                delay: 500,
                data: function (params) {
                    return {
                        _token : response.csrf_token,
                        parameter_pencarian : (typeof params.term === "undefined" ? "" : params.term),
                        select2: true,
                    }
                },
                processResults: function (data) {
                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                text: "["+item.kode_tahun_ajaran+"] "+item.keterangan_tahun_ajaran,
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
})
function loaddatatables(){
    $.get('/generate-csrf-token', function(response) {
        $("#daftar_murid").DataTable({
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
                "url": baseurlapi + '/murid/ajaxtabelmurid',
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
                    title: "ID",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return 'NIS : <a href="javascript:void(0)" onclick="detailinformasi(\''+row.idpengguna+'\',false)">'+row.nis+'</a><br>NISN : '+(row.nisn === "" ? "-" :row.nisn);
                        }
                        return '';
                    }
                }, 
                {
                    title: "Nama",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return "Nama Lengkap : "+row.nama_lengkap+"<br>Nama Panggilan : "+row.nama_panggilan;
                        }
                        return '';
                    }
                },
                {
                    title: "Kelas / TA",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return "Kelas : "+row.nama_kelas+"<br>Tahun Ajaran : "+row.keterangan_tahun_ajaran;
                        }
                        return '';
                    }
                },
                {
                    title: "Kontak",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return "WA : "+row.nomor_kontak+"<br>WA Orang Tua/Wali : "+row.nomor_kontak_orang_tua;
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
                                    <button id="editinformasi${row.idpengguna}" onclick="detailinformasi('${row.idpengguna}',true)" class="btn btn-outline-success w-100">
                                        <i class="ri-shield-user-line"></i> Ubah Data
                                    </button>
                                    <button id="hapusinformasi${row.idpengguna}" onclick="hapusinformasi('${row.idpengguna}','${row.nama_lengkap}','${row.nis}')" class="btn btn-outline-danger w-100">
                                        <i class="ri-shield-user-line"></i> Hapus Data
                                    </button>
                                </div>`;
                        }
                        return '';
                    }
                }
                
            ],
        });
    });
}
$("#import_murid_excel").click(function(){
    $('#modal_konfimasi_upload').modal('toggle');
}); 
$("#tambah_murid").click(function(){
    $('#modal_tambah_siswa').modal('toggle');
}); 
$("#proses_simpan").on("click", function() {
    Swal.fire({
        html:
            '<div class="mt-3"><lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop" colors="primary:#f06548,secondary:#f7b84b" style="width:120px;height:120px"></lord-icon><div class="pt-2 fs-15"><h4>Konfirmasi Simpan Informasi Pegawai !</h4><p class="text-muted mx-4 mb-0">Apakah anda ingin mengimpor semua informasis siswa/i pada file data excel '+$("#title_name_file").html()+'. File tetap tertambahkan mesikipun ada kesalahan asalkan file yang dibutuhkan terpenuhi dan akan terupdate jika ada informasi yang sama</p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Simpan Informasi',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $('#proses_simpan').prop("disabled",true);$('#proses_simpan').html('<i class="mdi mdi-spin mdi-cog-outline fs-15"></i> Proses Impor Informasi');
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    url: baseurlapi + '/murid/importmurid',
                    type: 'POST',
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('session_id_browser'));
                    },
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    data: function() {
                        var formData = new FormData();
                        formData.append('_token', response.csrf_token);
                        formData.append('file_murid', $('#file_murid')[0].files[0]);
                        return formData;
                    }(),
                    complete: function() {
                        $('#proses_simpan').prop("disabled", false);
                        $('#proses_simpan').html('<i class="ri-book-3-fill"></i> Proses Simpan');
                    },
                    success: function(response) {
                        if (response.success == false) {
                            return toastr.error(response.message, 'Pesan Kesalahan Code : ' + response.rc);
                        }
                        $('#modal_detail_pegawai').modal('hide');
                        toastr.success(response.message);
                        $('#daftar_murid').DataTable().ajax.reload();
                    },
                    error: function(xhr, status, error) {
                        $('#proses_simpan').prop("disabled", false);
                        $('#proses_simpan').html('<i class="ri-book-3-fill"></i> Proses Simpan');
                        toastr.error('Terjadi kesalahan proses IMPOR. Silahkan hubungi TIM Terkiat. Pesan Kesalahan : ' + xhr.responseJSON.message, 'Pesan REST API Impor Siswa');
                    }
                });                
            });
        }
    });
});
$("#unggah_preview").click(function(){
    const inputElement = document.getElementById('file_murid');
    $(inputElement).click();
    $(inputElement).on('change', function(event) {
        const fileName = inputElement.files[0] ? inputElement.files[0].name : 'No file selected';
        $("#title_name_file").html(fileName);
        if (!['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'].includes(inputElement.files[0].type)) {
            $('#excel_data').html('<div class="alert alert-danger">Only .xlsx or .xls file formats are allowed</div>');
            inputElement.value = '';
            return false;
        }
        let reader = new FileReader();
        reader.readAsArrayBuffer(event.target.files[0]);
        reader.onload = function(event){
            let data = new Uint8Array(reader.result);
            let work_book = XLSX.read(data, {type:'array'});
            let sheet_name = work_book.SheetNames;
            let sheet_data = XLSX.utils.sheet_to_json(work_book.Sheets[sheet_name[0]], {header:1});
            if(sheet_data.length > 0){
                let table_output = '<div class="table-responsive"><table class="table table-striped table-bordered">';
                for(let row = 0; row < sheet_data.length; row++){
                    table_output += '<tr>';
                    for(let cell = 0; cell < sheet_data[row].length; cell++){
                        if(row == 0){
                            table_output += '<th>'+sheet_data[row][cell]+'</th>';
                        }else{
                            table_output += '<td>'+sheet_data[row][cell]+'</td>';
                        }
                    }
                    table_output += '</tr>';
                    if (row == 6) break;
                }
                table_output += '</table></div>';
                document.getElementById('excel_data').innerHTML = table_output;
                $('#proses_simpan').prop("disabled",false);
                $('#daftar_murid').DataTable().ajax.reload();
            }
        }
    });
});
$('#kotak_pencarian').on('input', debounce(function (e) { 
    $('#daftar_murid').DataTable().ajax.reload();
}, 500));
$('#togglePassword').click(function(event) {
    event.preventDefault();
    let icon = $(this).find('i');
    icon.toggleClass('bi-eye bi-eye-slash');
    let passwordField = $('#katansandi');
    let fieldType = passwordField.attr('type') === 'password' ? 'text' : 'password';
    passwordField.attr('type', fieldType);
    $(this).toggleClass('btn-outline-secondary btn-outline-danger');
});
$("#simpan_informasi_murid").on( "click", function() {
    if (isedit) $('#katansandi').removeAttr('required');

    let username = $("#username").val(),
        email = $("#surel").val(),
        password = $("#katansandi").val(),
        idhakakses = $("#select2_hak_akses").val(),
        nis = $("#nis").val(),
        nama_lengkap = $("#nama_lengkap").val(),
        nama_panggilan = $("#nama_panggilan").val(),
        keringanan = $("#keringanan").val(),
        select2_kelas = $("#select2_kelas").val(),
        select2_tahun_ajaran = $("#select2_tahun_ajaran").val();

    if (username === "" || 
        email === "" || 
        (password === "" && !isedit) || 
        idhakakses === null || 
        nis === "" || 
        nama_lengkap === "" || 
        nama_panggilan === "" || 
        keringanan === "" || 
        select2_kelas === null || 
        select2_tahun_ajaran === null ) {
        return toastr.error("Informasi forumulir murid masih belum lengkap. Formilir yang berwarna merah wajib diisi untuk kebutuhan aplikasi", 'Pesan Kesalahan');
    }
    
    if (!isEmail(email)) return toastr.error('Format surel dibutuhkan. EX: hai@erayadigital.co.id');
    Swal.fire({
        html:
            '<div class="mt-3"><lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop" colors="primary:#f06548,secondary:#f7b84b" style="width:120px;height:120px"></lord-icon><div class="pt-2 fs-15"><h4>Konfirmasi Simpan Informasi Murid !</h4><p class="text-muted mx-4 mb-0">Apakah anda ingin menyimpan informasi murid '+nama_lengkap+' dengan NIS : '+nis+' ke dalam sistem Perpustakaan Esemkarinam</p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: (isedit ? 'Ubah' : 'Simpan')+' Informasi',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $('#simpan_informasi_murid').prop("disabled",true);$('#simpan_informasi_murid').html('<i class="mdi mdi-spin mdi-cog-outline fs-15"></i> Proses Simpan Informasi');
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    "url": baseurlapi + '/murid/kelolamurid',
                    "type": 'POST',
                    "beforeSend": function (xhr) { xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('session_id_browser'));},
                    "dataType": 'json',
                    "data": {
                        _token: response.csrf_token,
                        username: username,
                        email: email,
                        password: password,
                        idhakakses: idhakakses,
                        nis: nis,
                        nisn: $("#nisn").val(),
                        id_kelas: select2_kelas,
                        id_tahun_ajaran: select2_tahun_ajaran,
                        nama_lengkap: nama_lengkap,
                        nama_panggilan: nama_panggilan,
                        alamat: $("#alamat").val(),
                        nomor_kontak: $("#nomor_telepon").val(),
                        nomor_kontak_orang_tua: $("#nomor_telepon_ortu").val(),
                        keringanan: keringanan,
                        isedit: isedit,
                    },
                    "complete": function() {
                        $('#simpan_informasi_murid').prop("disabled", false);$('#simpan_informasi_murid').html('Simpan Informasi Murid');
                    },
                    "success": function(response) {
                        if (response.success == false) {
                            return toastr.error(response.message, 'Pesan Kesalahan Code : ' + response.rc);
                        }
                        refreshform();
                        $('#modal_tambah_siswa').modal('hide');
                        toastr.success('Informasi kredential atas email : '+$("#surel").val()+" berhasil disimpan ke database. Silahkan lakukan operasi App Esemkarinam jika berkenan");
                    },
                    "error": function(xhr, status, error) {
                        $('#simpan_informasi_murid').prop("disabled", false);$('#simpan_informasi_murid').html('Simpan Informasi Murid');
                        toastr.error('Terjadi kesalahan proses Simpan Murid. Silahkan hubungi TIM Terkiat. Pesan Kesalahan : ' + xhr.responseJSON.message, 'Pesan REST API Detail Murid '+nama_lengkap);
                    }
                });                
            });
        }
    });
});
function refreshform(){
    $("#form_pendaftaran")[0].reset()
    $("#select2_hak_akses, #select2_kelas, #select2_tahun_ajaran").val('').trigger('change')
    $('#daftar_murid').DataTable().ajax.reload()
    $('.nav-link[href="#nav-border-justified-home"]').tab('show');
    form.removeClass('was-validated')
}
$("#modal_tambah_siswa").on('hide.bs.modal', function() {
    refreshform()
    isedit = false
});
function hapusinformasi(id,nama_lengkap,nis){
    Swal.fire({
        html:
            '<div class="mt-3"><lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f06548,secondary:#f7b84b" style="width:120px;height:120px"></lord-icon><div class="pt-2 fs-15"><h4>Konfirmasi Hapus Informasi Murid !</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menghapus data murid dengan Nama : '+nama_lengkap+' dengan NIS : '+nis+'? Informasi mengenai data NIS ini akan tetap ada tetapi tidak bisa ditampilkan kedalam laporan</p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Hapus Informasi',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $('#hapusinformasi'+id).prop("disabled",true);$('#hapusinformasi'+id).html('<i class="mdi mdi-spin mdi-cog-outline fs-15"></i> Proses Hapus Informasi');
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    "url": baseurlapi + '/murid/hapusinformasimurid',
                    "type": 'DELETE',
                    "beforeSend": function (xhr) { xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('session_id_browser'));},
                    "dataType": 'json',
                    "data": {
                        _token: response.csrf_token,
                        id: id,
                    },
                    "complete": function() {
                        $('#hapusinformasi'+id).prop("disabled", false);$('#hapusinformasi'+id).html('<i class="ri-shield-user-line"></i> Hapus Data');
                    },
                    "success": function(response) {
                        if (response.success == false) {
                            return toastr.error(response.message, 'Pesan Kesalahan Code : ' + response.rc);
                        }
                        toastr.success('Informasi mengenai murid NAMA : '+nama_lengkap+' dengan NIS : '+nis+' berhasil di hapus di sistem. Informasi mengenai '+nama_lengkap+' tidak dapat dimunculkan');
                        refreshform();
                        $('#daftar_murid').DataTable().ajax.reload()
                    },
                    "error": function(xhr, status, error) {
                        $('#hapusinformasi'+id).prop("disabled", false);$('#hapusinformasi'+id).html('<i class="ri-shield-user-line"></i> Hapus Data');
                        toastr.error('Terjadi kesalahan proses HAPUS. Silahkan hubungi TIM Terkiat. Pesan Kesalahan : ' + xhr.responseJSON.message, 'Pesan REST API Kelola Murid');
                    }
                });
            });
        }
    });
}
function detailinformasi(id,edit){
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            "url": baseurlapi + '/murid/ajaxtabelmurid',
            "type": 'GET',
            "beforeSend": function (xhr) { xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('session_id_browser'));},
            "dataType": 'json',
            "data": {
                _token: response.csrf_token,
                parameter_pencarian: id,
                detail: true,
                length:1,
                start:0,
            },
            "complete": function() {
                $('#simpan_informasi_murid').prop("disabled", false);
                $('#simpan_informasi_murid').html('Simpan Informasi Murid');
                if (isedit) $('#simpan_informasi_murid').html('Ubah Informasi Murid');
            },
            "success": function(response) {
                if (response.success == false) {
                    return toastr.error(response.message, 'Pesan Kesalahan Code : ' + response.rc);
                }
                if (edit){
                    $("#username").val(response.data[0].username)
                    $("#surel").val(response.data[0].email)
                    let newOption = new Option(response.data[0].nama_hak_akses, response.data[0].id_hakakses, true, false);
                    $('#select2_hak_akses').append(newOption).trigger('change');
                    $("#select2_hak_akses").val(response.data[0].id_hakakses).trigger('change');
                    $("#nis").val(response.data[0].nis)
                    $("#nisn").val(response.data[0].nisn)
                    $("#nama_lengkap").val(response.data[0].nama_lengkap)
                    $("#nama_panggilan").val(response.data[0].nama_panggilan)
                    $("#alamat").val(response.data[0].alamat)
                    $("#nomor_telepon").val(response.data[0].nomor_kontak)
                    $("#nomor_telepon_ortu").val(response.data[0].nomor_kontak_orang_tua)
                    $("#keringanan").val(response.data[0].keringanan)
                    newOption = new Option(response.data[0].nama_kelas, response.data[0].id, true, false);
                    $('#select2_kelas').append(newOption).trigger('change');
                    $("#select2_kelas").val(response.data[0].id).trigger('change');
                    newOption = new Option(response.data[0].keterangan_tahun_ajaran, response.data[0].id, true, false);
                    $('#select2_tahun_ajaran').append(newOption).trigger('change');
                    $("#select2_tahun_ajaran").val(response.data[0].id).trigger('change');
                    isedit = true;
                    $('#modal_tambah_siswa').modal('toggle');
                }else{
                    $(".username").html(response.data[0].username)
                    $(".surel").html(response.data[0].email)
                    $(".nis").html(response.data[0].nis)
                    $(".nisn").html(response.data[0].nisn)
                    $(".nama_lengkap").html(response.data[0].nama_lengkap)
                    $(".nama_panggilan").html(response.data[0].nama_panggilan)
                    $(".nama_kelas").html(response.data[0].nama_kelas)
                    $(".keterangan_tahun_ajaran").html(response.data[0].keterangan_tahun_ajaran)
                    $(".alamat").html(response.data[0].alamat)
                    $(".nomor_telepon").html(response.data[0].nomor_kontak)
                    $(".nomor_telepon_orang_tua").html(response.data[0].nomor_kontak_orang_tua)
                    $(".keringanan").html(response.data[0].keringanan)
                    $('#modal_detail_murid').modal('toggle');
                }
            },
            "error": function(xhr, status, error) {
                $('#simpan_informasi_murid').prop("disabled", false);$('#simpan_informasi_murid').html('Simpan Informasi Murid');
                toastr.error('Terjadi kesalahan proses Simpan Murid. Silahkan hubungi TIM Terkiat. Pesan Kesalahan : ' + xhr.responseJSON.message, 'Pesan REST API Simpan Murid');
            }
        });                
    });
}