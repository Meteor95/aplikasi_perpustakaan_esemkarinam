<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HakAkses extends Model
{
    use HasFactory;
    protected $table = 'users_hakakses';
    protected $fillable = [
        'nama_hak_akses',
        'hakakses_json',
    ];
    public static function globalPengaturan($id_pengaturan_global){
        return DB::table('tms_pengaturan_global')->where('id_pengaturan', '=', $id_pengaturan_global)->first();
    }
    public static function getTotalUsersWithHakAkses($req){
        $parameterpencarian = $req->parameter_pencarian;
        return DB::table('users_hakakses')
            ->where(function ($query) use ($parameterpencarian) {
                $query->where('nama_hak_akses', 'like', '%' . $parameterpencarian . '%');
            })->get();
    }
    public static function getUsersWithHakAkses($req, $perHalaman, $offset){
        $parameterpencarian = $req->parameter_pencarian;
        return DB::table('users_hakakses')
            ->where(function ($query) use ($parameterpencarian) {
                $query->where('nama_hak_akses', 'like', '%' . $parameterpencarian . '%');
            })
            ->take($perHalaman)
            ->skip($offset)
            ->orderBy('nama_hak_akses')
            ->get();
    }
}
