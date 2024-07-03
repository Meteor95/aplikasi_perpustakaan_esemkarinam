let form = $("#form_tahun_ajaran"), isedit = false, id_tahun_ajaran = 0 ;
$(function () {
    setTimeout(loaddatatables(), 10);
})
function loaddatatables(){
    $.get('/generate-csrf-token', function(response) {
        $("#daftar_tahun_ajaran").DataTable({
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
                "url": baseurlapi + '/murid/ajaxtabeltabelajaran',
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
                    title: "Kode Tahun Ajaran",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return row.kode_tahun_ajaran;
                        }
                        return '';
                    }
                }, 
                {
                    title: "Keterangan Tahun Ajaran",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return row.keterangan_tahun_ajaran;
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
                                    <button id="editinformasi${row.id}" onclick="editinformasi('${row.id}', '${row.kode_tahun_ajaran}', '${btoa(row.keterangan_tahun_ajaran)}')" class="btn btn-outline-success w-100">
                                        <i class="ri-shield-user-line"></i> Ubah Data
                                    </button>
                                    <button id="hapusinformasi${row.id}" onclick="hapusinformasi('${row.id}', '${row.kode_tahun_ajaran}', '${btoa(row.keterangan_tahun_ajaran)}')" class="btn btn-outline-danger w-100">
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
$('#btn_simpan_tahunajaran').click(function(event) {
    event.preventDefault();
    let kode_tahun_ajaran = $("#kode_tahun_ajaran").val(), keterangan_tahun_ajaran = $("#keterangan_tahun_ajaran").val()
    form.addClass('was-validated')
    if (kode_tahun_ajaran === "" || keterangan_tahun_ajaran === "") return toastr.error("Informasi kode tahun ajaran dan keterangan wajib diisi", 'Pesan Kesalahan');
    Swal.fire({
        html:
            '<div class="mt-3"><lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop" colors="primary:#f06548,secondary:#f7b84b" style="width:120px;height:120px"></lord-icon><div class="pt-2 fs-15"><h4>Konfirmasi Simpan Informasi Tahun Ajaran !</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menyimpan informasi tahun ajaran '+kode_tahun_ajaran+' dengan keterangan '+keterangan_tahun_ajaran+'</p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: (isedit ? 'Ubah' : 'Simpan')+' Informasi',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $('#btn_simpan_tahunajaran').prop("disabled",true);$('#btn_simpan_tahunajaran').html('<i class="mdi mdi-spin mdi-cog-outline fs-15"></i> Proses Simpan Informasi');
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    "url": baseurlapi + '/murid/tahunajaran',
                    "type": 'POST',
                    "beforeSend": function (xhr) { xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('session_id_browser'));},
                    "dataType": 'json',
                    "data": {
                        _token: response.csrf_token,
                        id: id_tahun_ajaran,
                        kode_tahun_ajaran: kode_tahun_ajaran,
                        keterangan_tahun_ajaran: keterangan_tahun_ajaran,
                        isedit: isedit,
                    },
                    "complete": function() {
                        $('#btn_simpan_tahunajaran').prop("disabled", false);$('#btn_simpan_tahunajaran').html('Simpan Tahun Ajaran');
                    },
                    "success": function(response) {
                        if (response.success == false) {
                            return toastr.error(response.message, 'Pesan Kesalahan Code : ' + response.rc);
                        }
                        toastr.success('Informasi tahun ajaran berhasil di simpan dengan kode : '+kode_tahun_ajaran+' dengan keterangan '+keterangan_tahun_ajaran);
                        bersihkanformulir();
                        $('#daftar_tahun_ajaran').DataTable().ajax.reload()
                    },
                    "error": function(xhr, status, error) {
                        $('#btn_simpan_tahunajaran').prop("disabled", false);$('#btn_simpan_tahunajaran').html('Simpan Tahun Ajaran');
                        toastr.error('Terjadi kesalahan proses SIMPAN. Silahkan hubungi TIM Terkiat. Pesan Kesalahan : ' + xhr.responseJSON.message, 'Pesan REST API Kelola Tahun Ajaran');
                    }
                });
            });
        }
    });
});
function bersihkanformulir(){
    $("#kode_tahun_ajaran").val("");
    $("#keterangan_tahun_ajaran").val("");
    isedit = false
    id_tahun_ajaran = 0
    form.removeClass('was-validated')
}
function editinformasi(id,kode_tahun_ajaran,keterangan_tahun_ajaran){
    isedit = true
    $("#kode_tahun_ajaran").val(kode_tahun_ajaran);
    $("#keterangan_tahun_ajaran").val(atob(keterangan_tahun_ajaran));
    id_tahun_ajaran = id
}
function hapusinformasi(id,kode_tahun_ajaran,keterangan_tahun_ajaran){
    Swal.fire({
        html:
            '<div class="mt-3"><lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f06548,secondary:#f7b84b" style="width:120px;height:120px"></lord-icon><div class="pt-2 fs-15"><h4>Konfirmasi Hapus Informasi Tahun Ajaran !</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menghapus informasi tahun ajaran '+kode_tahun_ajaran+' dengan keterangan '+atob(keterangan_tahun_ajaran)+'</p></div></div>',
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
                    "url": baseurlapi + '/murid/hapustahunajaran',
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
                        toastr.success('Informasi tahun ajaran berhasil di terhapus dari database dengan kode : '+kode_tahun_ajaran+' dengan keterangan '+atob(keterangan_tahun_ajaran));
                        bersihkanformulir();
                        $('#daftar_tahun_ajaran').DataTable().ajax.reload()
                    },
                    "error": function(xhr, status, error) {
                        $('#hapusinformasi'+id).prop("disabled", false);$('#hapusinformasi'+id).html('<i class="ri-shield-user-line"></i> Hapus Data');
                        toastr.error('Terjadi kesalahan proses HAPUS. Silahkan hubungi TIM Terkiat. Pesan Kesalahan : ' + xhr.responseJSON.message, 'Pesan REST API Kelola Tahun Ajaran');
                    }
                });
            });
        }
    });
}
$('#kotak_pencarian').on('input', debounce(function (e) { 
    $('#daftar_tahun_ajaran').DataTable().ajax.reload();
}, 500));