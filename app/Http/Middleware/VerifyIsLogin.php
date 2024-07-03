<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Log,Cookie,Hash,Crypt};
use App\Models\{UserToken,UserStudents,Pegawai};

class VerifyIsLogin
{
    public function handle(Request $request, Closure $next)
    {
        $username = base64_decode($request->cookies->get('CookieID'));
        $uuid = base64_decode($request->cookies->get('HashCookieUUID'));
        $tokenstringplain = UserToken::check_user_ready($username);
        $apakahcocok = 0;
        if ($request->isMethod('POST') && $request->input('access_form') === "web_login") $apakahcocok = 1;
        if ($request->cookies->has('HashCookieUUID')) $apakahcocok = Hash::check($tokenstringplain, $uuid);
        if ($apakahcocok) {
            $apakahsiswa = UserStudents::detailOneUserWithPengguna($username);
            if ($apakahsiswa) {
                $request->attributes->add(['informasi_user' => UserStudents::detailOneUserWithPengguna($username)]);
            }else{
                $request->attributes->add(['informasi_user' => Pegawai::detailOneUserWithPegawai($username)]);
            }
            return $next($request);
        } 
        return redirect()->route('loginweb');
    }

}
