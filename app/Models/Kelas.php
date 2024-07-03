<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Kelas extends Model
{
    use HasFactory;
    protected $table = 'tms_ajaran_kelas';
    protected $fillable = [
        'kode_kelas',
        'nama_kelas',
        'total_biaya',
        'jumlah_bulan',
    ];
    public static function getKelasAjaran($req, $perHalaman, $offset){
        $parameterpencarian = $req->parameter_pencarian;
        return DB::table('tms_ajaran_kelas')
            ->where('kode_kelas', 'like', '%' . $parameterpencarian . '%')
            ->orWhere('nama_kelas', 'like', '%' . $parameterpencarian . '%')
            ->take($perHalaman)
            ->skip($offset)
            ->orderBy('kode_kelas')
            ->get();
    }
}
