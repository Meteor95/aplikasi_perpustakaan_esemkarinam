<?php

namespace App\Services;

use Str;
use Carbon\Carbon;
use App\Models\{User,Pegawai};
use Illuminate\Support\Facades\{DB,Hash};
use Illuminate\Support\Facades\Log;

class RegisterService
{
    public function createUserAndEmployed($data)
    {
        return DB::transaction(function () use ($data) {
            $users = [
                'username' => $data['username'] ?? '',
                'email' => $data['email'] ?? '',
                'password' => Hash::make($data['password']),
                'id_hakakses' => $data['idhakakses'] ?? '',
                'uuid' => (string) Str::uuid(),
            ];
            User::create($users);
            $userdata = User::where('username', $data['username'])->first();
            $employed = [
                'id_user' => $userdata->id,
                'nip' => isset($data['nip']) ? $data['nip'] : '',
                'nama_lengkap' => isset($data['nama_lengkap']) ? $data['nama_lengkap'] : '',
                'tempat_lahir' => isset($data['tempat_lahir']) ? $data['tempat_lahir'] : '',
                'tanggal_lahir' => (isset($data['tanggal_lahir']) && $data['tanggal_lahir'] !== "" ? Carbon::createFromFormat('d-m-Y', $data['tanggal_lahir'])->format('Y-m-d') : null),
                'jenis_kelamin' => isset($data['jenis_kelamin']) ? $data['jenis_kelamin'] : 'Laki-Laki',
                'alamat' => isset($data['alamat']) ? $data['alamat'] : '',
                'nomor_telepon' => isset($data['nomor_telepon']) ? $data['nomor_telepon'] : '',
                'catatan_lain' => isset($data['catatan_lain']) ? $data['catatan_lain'] : '',
            ];              
            Pegawai::create($employed);
        });
    }
    public function deleteUserAndEmployed($data)
    {
        return DB::transaction(function () use ($data) {
            $userdata = User::where('username', $data['username'])->first();
            Pegawai::where('id_user', $userdata->id)->delete();
            User::where('id', $userdata->id)->delete();
        });
    }
}
