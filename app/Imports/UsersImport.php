<?php

namespace App\Imports;

use App\Models\{User,UserStudents};
use Illuminate\Support\Str;
use Illuminate\Support\Facades\{DB,Hash};
use Maatwebsite\Excel\Concerns\{ToModel,WithHeadingRow,WithValidation};
use Illuminate\Support\Facades\Log;

class UsersImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * Heading row start from third row
     */
    public function headingRow(): int
    {
        return 1;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $uuid = (string) Str::uuid();
        $user = User::create([
            'uuid'          => $uuid,
            'username'      => $row['username'],
            'email'         => $row['email'],
            'password'      => Hash::make($row['password']),
            'id_hakakses'   => $row['id_hakakses'],
        ]);
        $user = User::where('uuid', $uuid)->first();
        UserStudents::create([
            'user_id'                   => $user->id,
            'nisn'                      => $row['nisn'],
            'nis'                       => $row['nis'],
            'id_kelas'                  => $row['id_kelas'],
            'id_tahun_ajaran'           => $row['id_tahun_ajaran'],
            'nama_lengkap'              => $row['nama_lengkap'],
            'nama_panggilan'            => $row['nama_panggilan'],
            'alamat'                    => $row['alamat'],
            'nomor_kontak'              => $row['nomor_kontak'],
            'nomor_kontak_orang_tua'    => $row['nomor_kontak_orang_tua'],
            'keringanan'                => $row['keringanan'],
        ]);
        return $user; 
    }

    /**
     * Define validation rules.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'username' => 'required|min:5',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5',
            'id_hakakses' => 'required',
            'nis' => 'required',
            'id_kelas' => 'required',
            'id_tahun_ajaran' => 'required',
            'nama_lengkap' => 'required',
            'alamat' => 'required',
        ];
    }
}
