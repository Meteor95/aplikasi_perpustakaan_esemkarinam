<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\{Penerbit,Pengarang,Kategori,Laci,Rak,Buku,Transaksi,TransaksiDetail,HakAkses};
use App\Helpers\ResponseHelper;
use App\Services\TransaksiService;
use Illuminate\Support\Facades\Log;

class PerpustakaanController extends Controller
{
    public function attribut(Request $req){
        try {
            $kondisi = $req->input('kondisi');
            $validationRules = [];
            $model = "";
            $attributes = [];
            if ($kondisi === "penerbit_buku") {
                $validationRules = [
                    'penerbit_nama' => 'required',
                    'penerbit_keterangan' => 'required',
                ];
                $model = Penerbit::class;
                $attributes = [
                    'nama_penerbit' => $req->input('penerbit_nama'),
                    'keterangan' => $req->input('penerbit_keterangan'),
                ];
            }else if ($kondisi === "pengarang_buku") {
                $validationRules = [
                    'pengarang_nama' => 'required',
                    'pengarang_keterangan' => 'required',
                ];
                $model = Pengarang::class;
                $attributes = [
                    'nama_pengarang' => $req->input('pengarang_nama'),
                    'keterangan' => $req->input('pengarang_keterangan'),
                ];
            }else if ($kondisi === "kategori_buku") {
                $validationRules = [
                    'kategori_nama' => 'required',
                    'kategori_keterangan' => 'required',
                ];
                $model = Kategori::class;
                $attributes = [
                    'nama_kategori' => $req->input('kategori_nama'),
                    'keterangan' => $req->input('kategori_keterangan'),
                ];
            }else if ($kondisi === "laci_buku") {
                $validationRules = [
                    'laci_nama' => 'required',
                    'laci_keterangan' => 'required',
                ];
                $model = Laci::class;
                $attributes = [
                    'nama_laci' => $req->input('laci_nama'),
                    'keterangan' => $req->input('laci_keterangan'),
                ];
            }else if ($kondisi === "rak_buku") {
                $validationRules = [
                    'rak_nama' => 'required',
                    'rak_keterangan' => 'required',
                ];
                $model = Rak::class;
                $attributes = [
                    'nama_rak' => $req->input('rak_nama'),
                    'keterangan' => $req->input('rak_keterangan'),
                ];
            }
            $validator = Validator::make($req->all(), $validationRules);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.ino_required_data'), $dynamicAttributes);
            }
            $model::create($attributes);
            return ResponseHelper::success(__('common.saving_data_ok', ['proses' => __('auth.ino_prosess_saving')]), []);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function loadatribut(Request $req){
        try {
            $nomorHalaman = (int) $req->start / (int)($req->length == 0 ? 1 : $req->length );
            $perHalaman = (int) $req->length;
            $offset = $nomorHalaman * $perHalaman; 
            if ($req->input('kondisi') === "penerbit"){
                $penerbit = Penerbit::getPenerbit($req, $perHalaman, $offset);
                $jumlahdata =  Penerbit::getTotalPenerbit($req)->count();
            }else if ($req->input('kondisi') === "pengarang"){
                $penerbit = Pengarang::getPengarang($req, $perHalaman, $offset);
                $jumlahdata =  Pengarang::getTotalPengarang($req)->count();
            }else if ($req->input('kondisi') === "kategori"){
                $penerbit = Kategori::getPengarang($req, $perHalaman, $offset);
                $jumlahdata =  Kategori::getTotalPengarang($req)->count();
            }else if ($req->input('kondisi') === "laci"){
                $penerbit = Laci::getPengarang($req, $perHalaman, $offset);
                $jumlahdata =  Laci::getTotalPengarang($req)->count();
            }else if ($req->input('kondisi') === "rak"){
                $penerbit = Rak::getPengarang($req, $perHalaman, $offset);
                $jumlahdata =  Rak::getTotalPengarang($req)->count();
            }
            $dynamicAttributes = [
                'data' => $penerbit,
                'recordsFiltered' => $jumlahdata,
                'pages' => [
                    'limit' => $perHalaman,
                    'offset' => $offset,
                ],
            ];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Atribut '.$req->input('kondisi')]), $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function listofbook(Request $req){
        try {
            if (filter_var($req->detailbuku, FILTER_VALIDATE_BOOLEAN)){
                $buku = Buku::detailOneBookDetail($req->id_buku);
                $dynamicAttributes = [ 'data' => $buku];
                if (empty($buku)) {
                    return ResponseHelper::data_not_found(__('Informasi data tidak ditemukan. Silahkan cek lagi parameter pencarian'));
                } else {
                    return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Buku ']), $dynamicAttributes);
                }
            }else{
                $nomorHalaman = (int) $req->start / (int)($req->length == 0 ? 1 : $req->length );
                $perHalaman = (int) $req->length;
                $offset = $nomorHalaman * $perHalaman; 
                $penerbit = Buku::getBuku($req, $perHalaman, $offset);
                $jumlahdata =  Buku::getTotalBuku($req)->count();
                $dynamicAttributes = [
                    'data' => $penerbit,
                    'recordsFiltered' => $jumlahdata,
                    'pages' => [
                        'limit' => $perHalaman,
                        'offset' => $offset,
                    ],
                ];
                return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Buku ']), $dynamicAttributes);
            }
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function listofatribut(Request $req){
        if ($req->input('kondisi') === "penerbit"){
            $data =  HakAkses::getTotalUsersWithHakAkses($req);
        }else if ($req->input('kondisi') === "pengarang"){
            $data =  HakAkses::getTotalUsersWithHakAkses($req);
        }else if ($req->input('kondisi') === "kategori"){
            $data =  HakAkses::getTotalUsersWithHakAkses($req);
        }else if ($req->input('kondisi') === "laci"){
            $data =  HakAkses::getTotalUsersWithHakAkses($req);
        }else if ($req->input('kondisi') === "rak"){
            $data =  HakAkses::getTotalUsersWithHakAkses($req);
        }
        $dynamicAttributes = [
            'data' => $data,
        ];
    }  
    public function insertofbook(Request $req){
        try {
            $validasiUser = Validator::make($req->all(),
            [
                'id_buku' => 'required',
                'nomor_buku' => 'required',
                'nama_buku' => 'required',
                'id_pengarang' => 'required',
                'id_rak' => 'required',
                'id_laci' => 'required',
                'id_kategori' => 'required',
                'tahun_terbit' => 'required',
                'id_penerbit' => 'required',
                'stok' => 'required',
                'status' => 'required',
                'id_penerima' => 'required',
            ]);
            if ($validasiUser->fails()) {
                $dynamicAttributes = ['errors' => $validasiUser->errors()];
                return ResponseHelper::error_validation(__('auth.ino_required_data'), $dynamicAttributes);
            }
            Buku::create([
                'id_buku' => $req->input('id_buku'),
                'nomor_buku' => $req->input('nomor_buku'),
                'nama_buku' => $req->input('nama_buku'),
                'id_pengarang' => $req->input('id_pengarang'),
                'id_rak' => $req->input('id_rak'),
                'id_laci' => $req->input('id_laci'),
                'id_kategori' => $req->input('id_kategori'),
                'tahun_terbit' => $req->input('tahun_terbit'),
                'id_penerbit' => $req->input('id_penerbit'),
                'stok' => $req->input('stok'),
                'status' => $req->input('status'),
                'keterangan' => $req->input('keterangan'),
                'id_penerima' => $req->input('id_penerima'),
            ]);
            return ResponseHelper::success(__('common.saving_data_ok', ['proses' => __('auth.ino_prosess_saving')]), []);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function save_rent_of_books(TransaksiService $transaksiservices,Request $req){
        try {
            $data = $req->all();
            $validator = Validator::make($data, [
                'data_buku' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.ino_required_data'), $dynamicAttributes);
            }
            $databuku = json_encode($req->input('data_buku'));
            $datanya = json_decode($databuku, true);
            $totaldipinjam = 0;
            $limitpinjaman = HakAkses::globalPengaturan(2);
            foreach ($datanya as $item) {
                $totaldipinjam = $totaldipinjam + $item['total_yang_dipinjam'];
                $buku_sama = Transaksi::cekBukuSamaDipinjam($req,$item['id_buku']);
                if ($buku_sama->buku_sama > 0) return ResponseHelper::error_validation("Nama Buku : ".$buku_sama->nama_buku." tidak dapat dipijam dikarenakan anda belum mengembalikan buku yang sama.", []);
            }
            if ($limitpinjaman->nilai < $totaldipinjam) return ResponseHelper::error_validation("Mohon maaf, maksimal peminjaman hanya ".$limitpinjaman->nilai." buku", []);
            $transaksiservices->addTransaksiPeminjamanBuku($databuku,$data);
            return ResponseHelper::success(__('common.saving_data_ok', ['proses' => __('auth.ino_prosess_saving')]), []);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function listloanofbook(Request $req){
        try {
            $nomorHalaman = (int) $req->start / (int)($req->length == 0 ? 1 : $req->length );
            $perHalaman = (int) $req->length;
            $offset = $nomorHalaman * $perHalaman; 
            $peminjam = Transaksi::getBukuWithPeminjaman($req, $perHalaman, $offset);
            $jumlahdata =  Transaksi::getTotalBukuWithPeminjaman($req)->count();
            $dynamicAttributes = [
                'data' => $peminjam,
                'recordsFiltered' => $jumlahdata,
                'pages' => [
                    'limit' => $perHalaman,
                    'offset' => $offset,
                ],
            ];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Buku ']), $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function listloanofbookreturn(Request $req){
        try {
            $nomorHalaman = (int) $req->start / (int)($req->length == 0 ? 1 : $req->length );
            $perHalaman = (int) $req->length;
            $offset = $nomorHalaman * $perHalaman; 
            $peminjam = Transaksi::getBukuWithPeminjaman($req, $perHalaman, $offset);
            $jumlahdata =  Transaksi::getTotalBukuWithPeminjaman($req)->count();
            $dynamicAttributes = [
                'data' => $peminjam,
                'recordsFiltered' => $jumlahdata,
                'pages' => [
                    'limit' => $perHalaman,
                    'offset' => $offset,
                ],
            ];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Buku ']), $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function detailrentofbook(Request $req){
        try {
            $nomorHalaman = (int) $req->start / (int)($req->length == 0 ? 1 : $req->length );
            $perHalaman = (int) $req->length;
            $offset = $nomorHalaman * $perHalaman; 
            $peminjam = Transaksi::getBukuWithPeminjamanDetail($req, $perHalaman, $offset);
            $dynamicAttributes = [
                'data' => $peminjam,
            ];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Peminjam ']), $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function hapus_peminjaman(TransaksiService $transaksiservices,Request $req){
        try {
            $transaksiservices->hapusTranskasiPeminjaman($req);
            return ResponseHelper::success(__('data.ino_success_delete', ['namadata' => "Hapus Transaksi Peminjaman"]));
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        } 
    }
    public function proses_pengembalian(TransaksiService $transaksiservices,Request $req){
        try {
            $data = $req->all();
            $validator = Validator::make($data, [
                'data_buku' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.ino_required_data'), $dynamicAttributes);
            }
            $databuku = json_encode($req->input('data_buku'));
            $transaksiservices->addTransaksiPengembalianBuku($databuku,$data);
            return ResponseHelper::success(__('common.saving_data_ok', ['proses' => __('auth.ino_prosess_saving')]), []);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function ambilpeminjaman(Request $req){
        try {
            if (filter_var($req->pengembalian, FILTER_VALIDATE_BOOLEAN)){
                $buku_sama = Transaksi::cekBukuSamaDipinjam($req,$req->id_buku);
                if ($buku_sama->buku_sama == 0) return ResponseHelper::error_validation("Nama Buku : ".$buku_sama->nama_buku." tidak ditemukan. Pastikan siswa meminjam buku yang benar.", []);
            }
            if (filter_var($req->pengembalian, FILTER_VALIDATE_BOOLEAN)){
                $buku = Transaksi::keranjangPengembalianBukuCek($req,$req->id_buku);
            }else{
                $buku = Transaksi::keranjangPengembalianBuku($req);
            }
            $dynamicAttributes = [ 'data' => $buku];
            if (empty($buku)) {
                return ResponseHelper::data_not_found(__('Informasi data tidak ditemukan. Silahkan cek lagi parameter pencarian'));
            } else {
                return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Buku ']), $dynamicAttributes);
            }
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
}
