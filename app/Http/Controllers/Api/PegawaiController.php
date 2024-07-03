<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Helpers\ResponseHelper;

class PegawaiController extends Controller
{
    /* konsep pendaftaran pegawai dengan switch akun, jadi 1 pegawai bisa ganti ganti users*/
    public function addemployed(Request $req){
        try {
            $validasiUser = Validator::make($req->all(),
            [
                'id_user' => 'required',
                'nip' => 'required',
                'nama_lengkap' => 'required',
                'jenis_kelamin' => 'required|string|in:Laki-Laki,Perempuan',
                'tanggal_lahir' => 'required|date_format:d-m-Y',

            ]);
            if ($validasiUser->fails()) {
                $dynamicAttributes = ['errors' => $validasiUser->errors()];
                return ResponseHelper::error_validation(__('auth.ino_required_data'), $dynamicAttributes);
            }
            Pegawai::create([
                'id_user' => $req->input('id_user'),
                'nip' => $req->input('nip'),
                'nama_lengkap' => $req->input('nama_lengkap'),
                'tempat_lahir' => $req->input('tempat_lahir'),
                'tanggal_lahir' => Carbon::parse($req->input('tanggal_lahir'))->format('Y-m-d'),
                'jenis_kelamin' => $req->input('jenis_kelamin'),
                'alamat' => $req->input('alamat'),
                'nomor_telepon' => $req->input('nomor_telepon'),
                'catatan_lain' => $req->input('catatan_lain'),
            ]);
            return ResponseHelper::success(__('common.saving_data_ok', ['proses' => __('auth.ino_prosess_saving')]), []);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function listemployed(Request $req){
        try {
            $nomorHalaman = (int) $req->start / (int)($req->length == 0 ? 1 : $req->length );
            $perHalaman = (int) $req->length;
            $offset = $nomorHalaman * $perHalaman; 
            $users = Pegawai::getUsersWithPegawai($req, $perHalaman, $offset);
            $jumlahdata =  Pegawai::getTotalUsersWithPegawai($req)->count();
            $dynamicAttributes = [
                'data' => $users,
                'recordsFiltered' => $jumlahdata,
                'pages' => [
                    'limit' => $perHalaman,
                    'offset' => $offset,
                ],
            ];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Pegawai']), $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function detailemployed(Request $req){
        try {
            $users = Pegawai::detailOneUserWithPegawai($req->user_id);
            $dynamicAttributes = [ 'data' => $users];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Pegawai']), $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function editemployed(Request $req){
        try {
            $validasiUser = Validator::make($req->all(),[
                'id_user' => 'required',
            ]);
            if ($validasiUser->fails()) {
                $dynamicAttributes = ['errors' => $validasiUser->errors()];
                return ResponseHelper::error_validation(__('auth.ino_required_data'), $dynamicAttributes);
            }
            $affectedRows = Pegawai::getUpdatePegawai($req);
            return ResponseHelper::success(__('data.ino_success_edit', ['namadata' => "Ubah Pegawai BPKAD",'idnya' => $req->input('id_user'),'barispengaruh' =>  $affectedRows]));
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
}
