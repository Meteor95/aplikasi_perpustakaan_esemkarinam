let form = $("#formulir_identitas_kelas"), isedit = false, id_kelas = 0 ;
$(function () {
    setTimeout(loaddatatables(), 10);
})
function loaddatatables(){
    $.get('/generate-csrf-token', function(response) {
        $("#daftar_kelas").DataTable({
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
                "url": baseurlapi + '/murid/ajaxtabelkelas',
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
                    title: "Kode Kelas",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return row.kode_kelas;
                        }
                        return '';
                    }
                }, 
                {
                    title: "Nama Kelas",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return row.nama_kelas;
                        }
                        return '';
                    }
                },
                {
                    title: "Besaran Biaya (IDR)",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return formatRupiah(row.total_biaya)+" per Bulan selama "+formatRupiah(row.jumlah_bulan)+" Bulan";
                        }
                        return '';
                    }
                },
                {
                    title: "Total Besaran Biaya (IDR)",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return formatRupiah(row.total_biaya * row.jumlah_bulan);
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
                                    <button id="editinformasi${row.id}" onclick="editinformasi('${row.id}', '${row.kode_kelas}', '${row.nama_kelas}', '${row.total_biaya}', '${row.jumlah_bulan}')" class="btn btn-outline-success w-100">
                                        <i class="ri-shield-user-line"></i> Ubah Data
                                    </button>
                                    <button id="hapusinformasi${row.id}" onclick="hapusinformasi('${row.id}', '${row.kode_kelas}', '${row.nama_kelas}', '${row.total_biaya}', '${row.jumlah_bulan}')" class="btn btn-outline-danger w-100">
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
$('#btn_tambah_kelas').click(function(event) {
    event.preventDefault();
    let kode_kelas = $("#kode_kelas").val(), nama_kelas = $("#nama_kelas").val(), total_biaya = total_biaya_.getNumber(), jumlah_bulan = jumlah_bulan_.getNumber()
    form.addClass('was-validated')
    if (kode_kelas === "" || nama_kelas === "" || total_biaya == 0 || jumlah_bulan == 0) return toastr.error("Informasi atribut kelas wajib diisi semua mulai dari kode ,nama kelas, total biaya per bulan serta jumlah maximal bulan transaksi", 'Pesan Kesalahan');
    Swal.fire({
        html:
            '<div class="mt-3"><lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop" colors="primary:#f06548,secondary:#f7b84b" style="width:120px;height:120px"></lord-icon><div class="pt-2 fs-15"><h4>Konfirmasi Simpan Informasi Kelas !</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menyimpan informasi tahun ajaran '+kode_kelas+' dengan keterangan '+nama_kelas+'</p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: (isedit ? 'Ubah' : 'Simpan')+' Informasi',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $('#btn_tambah_kelas').prop("disabled",true);$('#btn_tambah_kelas').html('<i class="mdi mdi-spin mdi-cog-outline fs-15"></i> Proses Simpan Informasi');
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    "url": baseurlapi + '/murid/kelas',
                    "type": 'POST',
                    "beforeSend": function (xhr) { xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('session_id_browser'));},
                    "dataType": 'json',
                    "data": {
                        _token: response.csrf_token,
                        id: id_kelas,
                        kode_kelas: kode_kelas,
                        nama_kelas: nama_kelas,
                        total_biaya: total_biaya,
                        jumlah_bulan: jumlah_bulan,
                        isedit: isedit,
                    },
                    "complete": function() {
                        $('#btn_tambah_kelas').prop("disabled", false);$('#btn_tambah_kelas').html('Simpan Kelas');
                    },
                    "success": function(response) {
                        if (response.success == false) {
                            return toastr.error(response.message, 'Pesan Kesalahan Code : ' + response.rc);
                        }
                        toastr.success('Informasi tahun ajaran berhasil di simpan dengan kode : '+kode_kelas+' dengan keterangan '+nama_kelas);
                        bersihkanformulir();
                        $('#daftar_kelas').DataTable().ajax.reload()
                    },
                    "error": function(xhr, status, error) {
                        $('#btn_tambah_kelas').prop("disabled", false);$('#btn_tambah_kelas').html('Simpan Kelas');
                        toastr.error('Terjadi kesalahan proses SIMPAN. Silahkan hubungi TIM Terkiat. Pesan Kesalahan : ' + xhr.responseJSON.message, 'Pesan REST API Kelola Kelas');
                    }
                });
            });
        }
    });
});
function bersihkanformulir(){
    $("#kode_kelas").val("")
    $("#nama_kelas").val("")
    total_biaya_.set(0)
    jumlah_bulan_.set(0)
    isedit = false
    id_kelas = 0
    form.removeClass('was-validated')
}
function editinformasi(id,kode_kelas,nama_kelas,total_biaya,jumlah_bulan){
    $("#kode_kelas").val(kode_kelas);
    $("#nama_kelas").val(nama_kelas);
    total_biaya_.set(total_biaya);
    jumlah_bulan_.set(jumlah_bulan);
    isedit = true
    id_kelas = id
}
function hapusinformasi(id,kode_kelas,nama_kelas,total_biaya,jumlah_bulan){
    Swal.fire({
        html:
            '<div class="mt-3"><lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f06548,secondary:#f7b84b" style="width:120px;height:120px"></lord-icon><div class="pt-2 fs-15"><h4>Konfirmasi Hapus Informasi Kelas !</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menghapus informasi kelas '+kode_kelas+' dengan keterangan '+nama_kelas+'</p></div></div>',
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
                    "url": baseurlapi + '/murid/hapuskelas',
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
                        toastr.success('Informasi tahun ajaran berhasil di terhapus dari database dengan kode : '+kode_kelas+' dengan keterangan '+atob(nama_kelas));
                        bersihkanformulir();
                        $('#daftar_kelas').DataTable().ajax.reload()
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
    $('#daftar_kelas').DataTable().ajax.reload();
}, 500));