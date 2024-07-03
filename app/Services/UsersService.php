<?php

namespace App\Services;

use Str;
use Carbon\Carbon;
use App\Models\{User,Murid};
use Illuminate\Support\Facades\{DB,Hash};
use Illuminate\Support\Facades\Log;

class UsersService
{
    public function deleteUserAndStudents($data)
    {
        return DB::transaction(function () use ($data) {
            $userdata = User::where('id', $data['id'])->first();
            Murid::where('user_id', $userdata->id)->delete();
            User::where('id', $userdata->id)->delete();
        });
    }
}
