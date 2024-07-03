<?php

namespace App\Http\Controllers\Web;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Cookie,Hash};
use App\Helpers\{ResponseHelper,GlobalHelper};
use App\Models\{PegawaiTokenEmail,User,PersonalAccessToken,Pegawai};


class PegawaiController extends Controller
{
    private function buildData(Request $req, $menu_utama_aktif, $sub_menu_utama_aktif){
        $userInfo = $req->attributes->get('informasi_user');
        return [
            'tipe_halaman' => 'admin',
            'menu_utama_aktif' => $menu_utama_aktif,
            'sub_menu_utama_aktif' => $sub_menu_utama_aktif,
            'userInfo' => $userInfo,
        ];
    }
    public function listemployed(Request $req)
    {
        $data = $this->buildData($req, 'kredential_dan_petugas','daftar_petugas');
        $breadcrumbs = [
            ['url' => '/beranda', 'label' => 'Home'],
            ['url' => '/pegawai/daftarpengguna', 'label' => 'Daftar Pengguna'],
            ['label' => 'Tabel Pengguna']
        ];
        return view('paneladmin.beranda.pegawai.pegawai_daftar', ['data' => $data])->with('breadcrumbs', $breadcrumbs);
    }
    public function forgotpassword(Request $req)
    {
        $token = PegawaiTokenEmail::check_user_ready_mail($req);
        if ($token === $req->query('token')){
            $data = [
                'tipe_halaman' => 'login',
                'email'=> $req->query('email')
            ];
            return view('paneladmin.lupapassword', ['data' => $data]);
        }
        $data = ['pesan_kesalahan' => base64_encode(__('error.701_error_mail')),];
        return redirect()->route('404',$data);
    }
    public function processchangepassword(Request $req)
    {
        try {
            $password_baru = $req->input('password_baru');
            $password_baru_konfirmasi = $req->input('password_baru_konfirmasi');
            if ($password_baru !== $password_baru_konfirmasi) {
                $dynamicAttributes = ['errors' => __('error.422_error')];
                return ResponseHelper::error_validation(__('auth.ino_required_data'), $dynamicAttributes);
            }
            $users = User::where('email', $req->input('email_reset'))->first();
            if (!$users) return ResponseHelper::data_not_found(__('data.ino_data_not_found', ['jenisdata' => "E-Mail",'namadata' => $req->email]));
            $passwordbaruHash = Hash::make($password_baru_konfirmasi);
            $users->password = $passwordbaruHash;
            $users->save();
            $token = PersonalAccessToken::where('tokenable_id', $users->id)->first();
            if ($token) { 
                $token->delete();
                
            }
            PegawaiTokenEmail::delete_token_reset($users->email);
            return ResponseHelper::success(__('common.saving_data_ok', ['proses' => __('auth.ino_update_credentials')]), []);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function permissionaccess(Request $req)
    {
        $data = $this->buildData($req, 'kredential_dan_petugas','daftar_permision');
        $breadcrumbs = [
            ['url' => '/beranda', 'label' => 'Home'],
            ['url' => '/pegawai/daftarpengguna', 'label' => 'Daftar Hak Akses'],
            ['label' => 'Tabel Hak Akses']
        ];
        return view('paneladmin.beranda.pegawai.hak_akses_sistem', ['data' => $data])->with('breadcrumbs', $breadcrumbs);
    }
}
