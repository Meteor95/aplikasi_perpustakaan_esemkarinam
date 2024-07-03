<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TahunAjaran extends Model
{
    use HasFactory;
    protected $table = 'tms_ajaran_tahun';
    protected $fillable = [
        'kode_tahun_ajaran',
        'keterangan_tahun_ajaran',
    ];
    public static function getTahunAjaran($req, $perHalaman, $offset){
        $parameterpencarian = $req->parameter_pencarian;
        return DB::table('tms_ajaran_tahun')
            ->where('kode_tahun_ajaran', 'like', '%' . $parameterpencarian . '%')
            ->orWhere('keterangan_tahun_ajaran', 'like', '%' . $parameterpencarian . '%')
            ->take($perHalaman)
            ->skip($offset)
            ->orderBy('kode_tahun_ajaran')
            ->get();
    }
}
