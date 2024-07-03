<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Pegawai extends Model
{
    protected $table = 'users_pegawai';
    protected $fillable = [
        'id_user',
        'nip',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'nomor_telepon',
        'catatan_lain',
    ];
    public static function detailOneUserWithPegawai($user_id){
        return DB::table('users')
        ->join('users_pegawai', 'users.id', '=', 'users_pegawai.id_user')
        ->join('users_hakakses', 'users.id_hakakses', '=', 'users_hakakses.id')
        ->select(
            'users.*',
            'users_pegawai.*',
            'users_pegawai.created_at as pegawai_created_at',
            'users_pegawai.updated_at as pegawai_updated_at',
            'users_hakakses.nama_hak_akses',
        )->where('users_pegawai.id_user', '=', $user_id)->first();
    }
    public static function getTotalUsersWithPegawai($req){
        $parameterpencarian = $req->parameter_pencarian;
        return DB::table('users')
            ->join('users_pegawai', 'users.id', '=', 'users_pegawai.id_user')
            ->join('users_hakakses', 'users.id_hakakses', '=', 'users_hakakses.id')
            ->where(function ($query) use ($parameterpencarian) {
                $query->where('users_pegawai.id_user', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('users_pegawai.nip', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('users_pegawai.nama_lengkap', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('users.username', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('users.email', 'like', '%' . $parameterpencarian . '%');
            })->get();
    }
    public static function getUsersWithPegawai($req, $perHalaman, $offset){
        $parameterpencarian = $req->parameter_pencarian;
        return DB::table('users')
            ->join('users_pegawai', 'users.id', '=', 'users_pegawai.id_user')
            ->join('users_hakakses', 'users.id_hakakses', '=', 'users_hakakses.id')
            ->select(
                'users.*',
                'users_pegawai.*',
                'users_pegawai.created_at as pegawai_created_at',
                'users_pegawai.updated_at as pegawai_updated_at',
                'users_hakakses.nama_hak_akses',
            )
            ->where(function ($query) use ($parameterpencarian) {
                $query->where('users_pegawai.id_user', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('users_pegawai.nip', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('users_pegawai.nama_lengkap', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('users.username', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('users.email', 'like', '%' . $parameterpencarian . '%');
            })
            ->take($perHalaman)
            ->skip($offset)
            ->orderBy('users_pegawai.nama_lengkap')
            ->get();
    }
    public static function getUpdatePegawai($req){
        return DB::table('users')
        ->join('users_pegawai', 'users.id', '=', 'users_pegawai.id_user')
        ->where('users_pegawai.id_user', '=', $req->input('id_user'))
        ->update([
            'users.username' => $req->input('username') ?? '',
            'users.email' => $req->input('email') ?? '',
            'users.id_hakakses' => $req->input('idhakakses') ?? '',
            'users.password' => ($req->input('password') == "" ? DB::raw('password') : Hash::make($req->input('password'))),
            'users_pegawai.nip' => $req->input('nip') ?? '',
            'users_pegawai.nama_lengkap' => $req->input('nama_lengkap') ?? '',
            'users_pegawai.tempat_lahir' => $req->input('tempat_lahir') ?? '',
            'users_pegawai.tanggal_lahir' => Carbon::parse($req->input('tanggal_lahir'))->format('Y-m-d'),
            'users_pegawai.jenis_kelamin' => $req->input('jenis_kelamin'),
            'users_pegawai.alamat' => $req->input('alamat') ?? '',
            'users_pegawai.catatan_lain' => $req->input('catatan_lain') ?? '',
        ]);
    }
}
