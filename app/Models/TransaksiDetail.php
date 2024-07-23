<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TransaksiDetail extends Model
{
    use HasFactory;
    protected $table = 'tms_transaksi_buku_detail';
    public $timestamps = false;
    protected $fillable = [
        'id_transaksi_detail',
        'id_transaksi',
        'id_buku',
        'denda',
        'keterangan',
        'dikembalikan',
        'status',
        'approval_status',
    ];
}
