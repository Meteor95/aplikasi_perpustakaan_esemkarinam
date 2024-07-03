<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Validator,Storage,Hash};
use Maatwebsite\Excel\Facades\Excel;
use App\Helpers\ResponseHelper;
use App\Models\{TahunAjaran,Kelas,Murid,User};
use App\Imports\UsersImport;
use App\Services\UsersService;

class StudentsController extends Controller
{
    public function academicyears(Request $req)
    {
        try {
            $validasiUser = Validator::make($req->all(),
            [
                'isedit' => 'required',
                'kode_tahun_ajaran' => 'required',
                'keterangan_tahun_ajaran' => 'required',

            ]);
            if ($validasiUser->fails()) {
                $dynamicAttributes = ['errors' => $validasiUser->errors()];
                return ResponseHelper::error_validation(__('data.eraya_required_data'), $dynamicAttributes);
            }
            $isedit = $req->input('isedit');
            $id = $req->input('id');
            $kode_tahun_ajaran = $req->input('kode_tahun_ajaran');
            $keterangan_tahun_ajaran = $req->input('keterangan_tahun_ajaran');
            if (!filter_var($isedit, FILTER_VALIDATE_BOOLEAN)){
                TahunAjaran::create([
                    'kode_tahun_ajaran' => $req->input('kode_tahun_ajaran'),
                    'keterangan_tahun_ajaran' => $req->input('keterangan_tahun_ajaran'),
                ]);
            }else{
                $tahunAjaran = TahunAjaran::find($id);
                $tahunAjaran->kode_tahun_ajaran = $kode_tahun_ajaran;
                $tahunAjaran->keterangan_tahun_ajaran = $keterangan_tahun_ajaran;
                $tahunAjaran->save();
            }
            return ResponseHelper::success(__('data.eraya_data_insert_sukses', ['namadata' => "Simpan Tahun Ajaran"]));
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        } 
    }
    public function deleteacademicyears(Request $req)
    {
        try {
            $id = $req->input('id');
            TahunAjaran::destroy($id);
            return ResponseHelper::success(__('data.eraya_data_delete_sukses', ['namadata' => "Hapus Tahun Ajaran",'namapesan' => "Data atas tahun ajaran ini tidak dapat di kelola. Jika ingin mengelola silahkan hubungi TIM IT"]));
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        } 
    }
    public function listtableacademicyears(Request $req)
    {
        try {
            if (filter_var($req->select2, FILTER_VALIDATE_BOOLEAN)){
                $data =  TahunAjaran::get();
                $dynamicAttributes = [
                    'data' => $data,
                ];
            }else{
                $nomorHalaman = (int) $req->start / (int)($req->length == 0 ? 1 : $req->length );
                $perHalaman = (int) $req->length;
                $offset = $nomorHalaman * $perHalaman; 
                $tahunajaran = TahunAjaran::getTahunAjaran($req, $perHalaman, $offset);
                $jumlahdata = $tahunajaran->count();
                $dynamicAttributes = [
                    'data' => $tahunajaran,
                    'recordsFiltered' => $jumlahdata,
                    'pages' => [
                        'limit' => $perHalaman,
                        'offset' => $offset,
                    ],
                ];
            }
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Pegawai']), $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function liststudentsclass(Request $req)
    {
        try {
            if (filter_var($req->select2, FILTER_VALIDATE_BOOLEAN)){
                $data =  Kelas::get();
                $dynamicAttributes = [
                    'data' => $data,
                ];
            }else{
                $nomorHalaman = (int) $req->start / (int)($req->length == 0 ? 1 : $req->length );
                $perHalaman = (int) $req->length;
                $offset = $nomorHalaman * $perHalaman; 
                $kelasajaran = Kelas::getKelasAjaran($req, $perHalaman, $offset);
                $jumlahdata = $kelasajaran->count();
                $dynamicAttributes = [
                    'data' => $kelasajaran,
                    'recordsFiltered' => $jumlahdata,
                    'pages' => [
                        'limit' => $perHalaman,
                        'offset' => $offset,
                    ],
                ];
            }
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Pegawai']), $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function studentsclass(Request $req)
    {
        try {
            $validasiUser = Validator::make($req->all(),
            [
                'isedit' => 'required',
                'kode_kelas' => 'required',
                'nama_kelas' => 'required',
                'total_biaya' => 'required',
                'jumlah_bulan' => 'required',
            ]);
            if ($validasiUser->fails()) {
                $dynamicAttributes = ['errors' => $validasiUser->errors()];
                return ResponseHelper::error_validation(__('data.eraya_required_data'), $dynamicAttributes);
            }
            $isedit = $req->input('isedit');
            $id = $req->input('id');
            $kode_kelas = $req->input('kode_kelas');
            $nama_kelas = $req->input('nama_kelas');
            $total_biaya = $req->input('total_biaya');
            $jumlah_bulan = $req->input('jumlah_bulan');
            if (!filter_var($isedit, FILTER_VALIDATE_BOOLEAN)){
                Kelas::create([
                    'kode_kelas' => $kode_kelas,
                    'nama_kelas' => $nama_kelas,
                    'total_biaya' => $total_biaya,
                    'jumlah_bulan' => $jumlah_bulan,
                ]);
            }else{
                $tahunAjaran = Kelas::find($id);
                $tahunAjaran->kode_kelas = $kode_kelas;
                $tahunAjaran->nama_kelas = $nama_kelas;
                $tahunAjaran->total_biaya = $total_biaya;
                $tahunAjaran->jumlah_bulan = $jumlah_bulan;
                $tahunAjaran->save();
            }
            return ResponseHelper::success(__('data.eraya_data_insert_sukses', ['namadata' => "Simpan Kelas"]));
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        } 
    }
    public function deletestudentsclass(Request $req)
    {
        try {
            $id = $req->input('id');
            Kelas::destroy($id);
            return ResponseHelper::success(__('data.eraya_data_delete_sukses', ['namadata' => "Hapus Kelas",'namapesan' => "Data atas tahun kelas ini tidak dapat di kelola. Jika ingin mengelola silahkan hubungi TIM IT"]));
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        } 
    }
    public function studentsimports(Request $req){
        try {
            $validasiUser = Validator::make($req->all(),
            [
               'file_murid' => 'required|max:2048',
            ]);
            if ($validasiUser->fails()) {
                $dynamicAttributes = ['errors' => $validasiUser->errors()];
                return ResponseHelper::error_validation(__('data.eraya_required_data'), $dynamicAttributes);
            }
            Excel::import(new UsersImport, $req->file('file_murid'));
            return ResponseHelper::success(__('data.eraya_data_insert_sukses', ['namadata' => "Import Data"]));
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        } 
    }
    public function studentslist(Request $req)
    {
        try {
            $nomorHalaman = (int) $req->start / (int)($req->length == 0 ? 1 : $req->length );
            $perHalaman = (int) $req->length;
            $offset = $nomorHalaman * $perHalaman; 
            $kelasajaran = Murid::getListMurid($req, $perHalaman, $offset);
            $jumlahdata = $kelasajaran->count();
            $dynamicAttributes = [
                'data' => $kelasajaran,
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
    public function managestudents(Request $req)
    {
        try {
            $isedit = $req->input('isedit');
            $validasiUser = Validator::make($req->all(),
            [
                'username' => 'required',
                'email' => $isedit ? '' : 'required|email|unique:users',
                'password' => $isedit ? '' : 'required',
                'idhakakses' => 'required',
                'nis' => 'required',
                'id_kelas' => 'required',
                'id_tahun_ajaran' => 'required',
                'nama_lengkap' => 'required',
                'keringanan' => 'required|integer',
            ]);
            if ($validasiUser->fails()) {
                $dynamicAttributes = ['errors' => $validasiUser->errors()];
                return ResponseHelper::error_validation(__('data.eraya_required_data'), $dynamicAttributes);
            }
            $username = $req->input('username');
            $password = $req->input('password');
            $email = $req->input('email');
            $idhakakses = $req->input('idhakakses');
            $nis = $req->input('nis');
            $nisn = $req->input('nisn');
            $id_kelas = $req->input('id_kelas');
            $id_tahun_ajaran = $req->input('id_tahun_ajaran');
            $nama_lengkap = $req->input('nama_lengkap');
            $nama_panggilan = $req->input('nama_panggilan');
            $alamat = $req->input('alamat');
            $nomor_kontak = $req->input('nomor_kontak');
            $nomor_kontak_orang_tua = $req->input('nomor_kontak_orang_tua');
            $keringanan = $req->input('keringanan');
            if (!filter_var($isedit, FILTER_VALIDATE_BOOLEAN)){
                User::create([
                    'uuid' => (string) Str::uuid(),
                    'username' => $username,
                    'email' => $email,
                    'password' => Hash::make($password),
                    'id_hakakses' => $idhakakses,
                ]);
                $datasiswa = User::where('username', $username)->first();
                Murid::create([
                    'user_id' => $datasiswa->id,
                    'nisn' => $nisn,
                    'nis' => $nis,
                    'id_kelas' => $id_kelas,
                    'id_tahun_ajaran' => $id_tahun_ajaran,
                    'nama_lengkap' => $nama_lengkap,
                    'nama_panggilan' => $nama_panggilan,
                    'alamat' => $alamat,
                    'nomor_kontak' => $nomor_kontak,
                    'nomor_kontak_orang_tua' => $nomor_kontak_orang_tua,
                    'keringanan' => $keringanan,
                ]);
            }else{
                $userdata = User::where('username', $username)->first();
                $userdata->username = $username;
                $userdata->email = $email;
                if ($password !== "") $userdata->password = Hash::make($password);
                $userdata->id_hakakses = $idhakakses;
                $userdata->save();
                $datasiswa = Murid::where('nis', $nis)->first();
                $datasiswa->nisn = $nisn;
                $datasiswa->nis = $nis;
                $datasiswa->id_kelas = $id_kelas;
                $datasiswa->id_tahun_ajaran = $id_tahun_ajaran;
                $datasiswa->nama_lengkap = $nama_lengkap;
                $datasiswa->nama_panggilan = $nama_panggilan;
                $datasiswa->alamat = $alamat;
                $datasiswa->nomor_kontak = $nomor_kontak;
                $datasiswa->nomor_kontak_orang_tua = $nomor_kontak_orang_tua;
                $datasiswa->keringanan = $keringanan;
                $datasiswa->save();
            }
            return ResponseHelper::success(__('data.eraya_data_insert_sukses', ['namadata' => "Simpan Data Murid"]));
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        } 
    }
    function detailstudents(){
        try {
            $nomorHalaman = (int) $req->start / (int)($req->length == 0 ? 1 : $req->length );
            $perHalaman = (int) $req->length;
            $offset = $nomorHalaman * $perHalaman; 
            $kelasajaran = Murid::getListMurid($req, $perHalaman, $offset);
            $jumlahdata = $kelasajaran->count();
            $dynamicAttributes = [
                'data' => $kelasajaran,
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
    public function deletestudents(UsersService $userservice,Request $req){
        $data = $req->all();
        $userservice->deleteUserAndStudents($data);
        return ResponseHelper::success(__('data.eraya_data_delete_sukses', ['namadata' => "Hapus Tahun Ajaran",'namapesan' => "Data atas tahun ajaran ini tidak dapat di kelola. Jika ingin mengelola silahkan hubungi TIM IT"]));
    }
}
