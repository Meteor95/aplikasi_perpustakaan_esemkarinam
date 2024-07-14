<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Murid extends Model
{
    use HasFactory;
    protected $table = 'users_siswa';
    protected $fillable = [
        'user_id',
        'nisn',
        'nis',
        'id_kelas',
        'id_tahun_ajaran',
        'nama_lengkap',
        'nama_panggilan',
        'alamat',
        'nomor_kontak',
        'nomor_kontak_orang_tua',
        'keringanan',
    ];
    public static function getListMurid($req, $perHalaman, $offset){
        $parameterpencarian = $req->parameter_pencarian;
        $detail = $req->detail;
        $query = DB::table('users_siswa')
            ->join('users', 'users_siswa.user_id', '=', 'users.id')
            ->join('tms_ajaran_kelas', 'users_siswa.id_kelas', '=', 'tms_ajaran_kelas.id')
            ->join('tms_ajaran_tahun', 'users_siswa.id_tahun_ajaran', '=', 'tms_ajaran_tahun.id')
            ->join('users_hakakses', 'users.id_hakakses', '=', 'users_hakakses.id');
        if ($detail) {
            $query->where('users.id', '=', $parameterpencarian)
            ->orWhere('users_siswa.nisn', '=', $parameterpencarian)
            ->orWhere('users_siswa.nis', '=', $parameterpencarian);
        }else{
            $query->where(function($query) use ($parameterpencarian) {
                $query->where('users_siswa.nama_lengkap', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('users_siswa.nama_panggilan', 'like', '%' . $parameterpencarian . '%')
                    ->orWhere('users_siswa.nomor_kontak', 'like', '%' . $parameterpencarian . '%');
            });
        }
        $query->select('users_siswa.*', 'tms_ajaran_kelas.*', 'tms_ajaran_tahun.*','users.id as idpengguna','users.*','users_hakakses.*','users_hakakses.id as idhakakses')
        ->take($perHalaman)
        ->skip($offset)
        ->orderBy('users_siswa.nis');
        return $query->get();

    }
}
