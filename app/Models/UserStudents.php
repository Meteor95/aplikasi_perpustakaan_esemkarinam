<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserStudents extends Model
{
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
        'nomor_telepon',
        'keringanan',
    ];
    public static function detailOneUserWithPengguna($user_id){
        return DB::table('users')
        ->join('users_siswa', 'users.id', '=', 'users_siswa.user_id')
        ->join('users_hakakses', 'users.id_hakakses', '=', 'users_hakakses.id')
        ->select(
            'users.*',
            'users_siswa.*',
            'users_hakakses.nama_hak_akses',
        )->where('users.id', '=', $user_id)->first();
    }
}
