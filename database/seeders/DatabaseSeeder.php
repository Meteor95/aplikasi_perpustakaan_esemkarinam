<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory as FakerFactory;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'id' => 1,
                'uuid' => (string) Str::uuid(),
                'username' => "erayadigital",
                'email' => "hallo@erayadigital.co.id",
                'email_verified_at' => null,
                'password' => Hash::make("IniPassw0RdYaNkkuaTBeutHzz4083,.@"),
                'remember_token' => null,
                'id_hakakses' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'uuid' => (string) Str::uuid(),
                'username' => "siswa",
                'email' => "siswa@erayadigital.co.id",
                'email_verified_at' => null,
                'password' => Hash::make("IniPassw0RdYaNkkuaTBeutHzz4083,.@"),
                'remember_token' => null,
                'id_hakakses' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);  
        DB::table('users_hakakses')->insert([
            [
                'id' => 1,
                'nama_hak_akses' => "FULL ADMIN",
                'hakakses_json' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'nama_hak_akses' => "NON ACTIVE",
                'hakakses_json' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]); 
        DB::table('users_pegawai')->insert([
            [
                'id' => 1,
                'id_user' => 1,
                'nip' => "1990081720200410",
                'nama_lengkap' => "Indo Dream Studio Malang",
                'tempat_lahir' => "Malang",
                'tanggal_lahir' => "2024-01-01",
                'jenis_kelamin' => "Laki-Laki",
                'alamat' => "Malang",
                'nomor_telepon' => "082257808535",
                'catatan_lain' => "Mohon Lengkapi Informasi Pegawai Ini",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]); 
        DB::table('users_siswa')->insert([
            [
                'user_id' => 2,
                'nisn' => "154945-5049-666-6190",
                'nis' => "0598502-598026-502-200",
                'id_kelas' => 1,
                'id_tahun_ajaran' => 1,
                'nama_lengkap' => "Seira Setyawan",
                'nama_panggilan' => "Seira",
                'alamat' => "Malang",
                'nomor_kontak' => "082257808535",
                'nomor_kontak_orang_tua' => "082257808535",
                'keringanan' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
        DB::table('tms_ajaran_kelas')->insert([
            [
                'id' => 1,
                'kode_kelas' => "NOKLS",
                'nama_kelas' => "Tidak Memiliki Kelas",
                'total_biaya' => 0,
                'jumlah_bulan' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]); 
        DB::table('tms_ajaran_tahun')->insert([
            [
                'id' => 1,
                'kode_tahun_ajaran' => "NOTA",
                'keterangan_tahun_ajaran' => "Tidak Memiliki Tahun Ajaran",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]); 
    }
}
