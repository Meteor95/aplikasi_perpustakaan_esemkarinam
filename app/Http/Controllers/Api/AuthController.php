<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth,Hash,Validator,Mail,Cookie};
use App\Helpers\{ResponseHelper,GlobalHelper};
use Illuminate\Support\Str;
use App\Services\RegisterService;
use App\Models\{User,PersonalAccessToken,UserToken,HakAkses};

class AuthController extends Controller
{
    public function register(RegisterService $registerService,Request $req){
        try {
            $validasiUser = Validator::make($req->all(),
            [
                'username' => 'required',
                'email' => 'required|email|unique:users,email',
                'idhakakses' => 'required',
                'password' => 'required',
                'nip' => 'required',
                'nama_lengkap' => 'required',
            ]);
            if ($validasiUser->fails()) {
                $dynamicAttributes = ['errors' => $validasiUser->errors()];
                return ResponseHelper::error_validation(__('auth.ino_required_data'), $dynamicAttributes);
            }
            $data = $req->all();
            $registerService->createUserAndEmployed($data);
            return ResponseHelper::success(__('common.saving_data_ok', ['proses' => __('auth.ino_prosess_saving')]), []);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function login(Request $req){
        try {
            $tokenstring = __('data.ino_token_not_generate');
            $user = User::getUserWithHakAkses($req);
            if (!$user || !Hash::check($req->input('password'), $user->password)) return ResponseHelper::data_not_found(__('auth.eraya_invalid_credentials'));
            $token = PersonalAccessToken::where('tokenable_id', $user->id)->first();
            if (!$token){
                $tokenstring = $user->createToken($req->input('username').'_API_TOKEN',['*'],Carbon::now()->addDays(6))->plainTextToken;
                UserToken::add_temp_token($tokenstring,$user);
            }else{
                $expiresAt = Carbon::parse($token->expires_at);
                if ($expiresAt->isPast()){
                    $token->delete();
                    $tokenstring = $user->createToken($req->input('username').'_API_TOKEN',['*'],Carbon::now()->addDays(6))->plainTextToken;
                    UserToken::add_temp_token($tokenstring,$user);
                }else{
                    $tokenstring = UserToken::check_user_ready($user->id);
                }
            }
            $dynamicAttributes = [
                'token' => $tokenstring,
                'fitur' => json_decode($user->hakakses_json, true),
            ];
            $cookie1 = Cookie::make('HashCookieUUID', base64_encode(Hash::make($tokenstring)), (60 * 24));
            $cookie2 = Cookie::make('CookieID', base64_encode($user->id), (60 * 24));
            $response = ResponseHelper::success(__('auth.success_login', ['username' => $req->input('username')]), $dynamicAttributes);
            $response->withCookie($cookie1)->withCookie($cookie2);
            return $response;
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function delete(RegisterService $registerService,Request $req)
    {
        try {
            $validasiUser = Validator::make($req->all(),['username' => 'required']);
            if ($validasiUser->fails()) {
                $dynamicAttributes = ['errors' => $validasiUser->errors()];
                return ResponseHelper::error_validation(__('auth.ino_required_data'), $dynamicAttributes);
            }
            $data = $req->all();
            $registerService->deleteUserAndEmployed($data);
            return ResponseHelper::success(__('data.ino_success_delete', ['namadata' => "Hapus Pengguna",'idnya' =>  $req->username,'barispengaruh' => 1]));
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
        
    }
    public function forgotpassword(Request $req)
    {
        try {
            $validasiUser = Validator::make($req->all(),
            [
                'email' => 'required|email',
            ]);
            if ($validasiUser->fails()) {
                $dynamicAttributes = ['errors' => $validasiUser->errors()];
                return ResponseHelper::error_validation(__('auth.ino_required_data'), $dynamicAttributes);
            }
            $email = User::where('email', $req->email)->first();
            if (!$email) return ResponseHelper::data_not_found(__('data.eraya_data_not_found', ['jenisdata' => "E-Mail",'namadata' => $req->email]));
            $token_rest = Str::random(32) . '_' . Carbon::now()->format('YmdHisv');
            UserTokenEmail::add_temp_token_mail($req,$token_rest);
            $token = PersonalAccessToken::where('tokenable_id', $email->id)->first();if ($token) { $token->delete(); }
            $url_reset = url('/auth/forgotpassword?email='.$req->email.'&token='.$token_rest);
            $data = [
                'subject' => 'Lupa Katasandi Aplikasi ',
                'title' => 'Permintaan Kata Sandi Baru Aplikasi Perpustakaan Esemkarinam',
                'username' => GlobalHelper::convertStringToAsterisksmod2($email->username),
                'email' => $email->email,
                'url_token' => $url_reset,
            ];
            Mail::to($req->email)->send(new KirimEmail($data));
            return ResponseHelper::success(__('common.eraya_email_sent', ['tujuanemail' => $req->email]), []);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function tokencheck(Request $req)
    {
       try {
            $validasiUser = Validator::make($req->all(),['id_user' => 'required']);
            $token = PersonalAccessToken::where('tokenable_id', $req->input('id_user'))->first();
            if (!$token) return ResponseHelper::data_not_found(__('auth.eraya_invalid_credentials'));
            return ResponseHelper::success(__('common.eraya_token_ready', ['tanggalhabis' => Carbon::createFromFormat('Y-m-d H:i:s', $token->expires_at)->format('d-m-Y H:i:s')]), []);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function logout(Request $req)
    {
        try {
            PersonalAccessToken::where('tokenable_id',$req->input('username'))->first()->delete();
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function addpermissionuser(Request $req)
    {
        try {
            $data = json_decode($req->input('hakakses_json'));
            if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
                $dynamicAttributes = ['errors' => json_last_error()];
                return ResponseHelper::error_validation(__('auth.ino_required_data'), $dynamicAttributes);
            }
            HakAkses::updateOrCreate(
                ['nama_hak_akses' => $req->input('nama_hak_akses')],
                ['hakakses_json' => $req->input('hakakses_json')]
            );
            return ResponseHelper::success(__('common.saving_data_ok', ['proses' => __('auth.ino_prosess_saving')]), []);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }   
    }
    public function deletepermissionuser(Request $req)
    {
        try {
            $idhakakses = $req->idhakakses;
            $affectedRows = 0;
            if ($idhakakses == 1 || $idhakakses == 2) return ResponseHelper::error(700);
            $userPermission = User::where('id_hakakses', $idhakakses)->first();
            if ($userPermission) {
                $affectedRows = User::where('id_hakakses', $idhakakses)->update(['idhakakses' => 2]);
            }
            HakAkses::find($idhakakses)->delete();
            return ResponseHelper::success(__('data.ino_success_delete', ['namadata' => "Hapus Hak Akses",'idnya' =>  $req->nama_hak_akses,'barispengaruh' => $affectedRows]));
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function listpermissionuser(Request $req)
    {
        try {
            if ($req->parameter_pencarian_tabel == true){
                $nomorHalaman = (int) $req->start / (int)($req->length == 0 ? 1 : $req->length );
                $perHalaman = (int) $req->length;
                $offset = $nomorHalaman * $perHalaman; 
                $users = HakAkses::getUsersWithHakAkses($req, $perHalaman, $offset);
                $jumlahdata =  HakAkses::getTotalUsersWithHakAkses($req)->count();
                $dynamicAttributes = [
                    'data' => $users,
                    'recordsFiltered' => $jumlahdata,
                    'pages' => [
                        'limit' => $perHalaman,
                        'offset' => $offset,
                    ],
                ];
            }else{
                $data =  HakAkses::getTotalUsersWithHakAkses($req);
                $dynamicAttributes = [
                    'data' => $data,
                ];
            }
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Hak Akses']), $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
}
