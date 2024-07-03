<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'username',
        'email',
        'password',
        'id_hakakses',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function userStudent(){
        return $this->hasOne(UserStudents::class, 'user_id');
    }
    public static function getUserWithHakAkses($req) {
        $parameterpencarian = $req->input('username');
        return User::join('users_hakakses', 'users.id_hakakses', '=', 'users_hakakses.id')
            ->where(function ($query) use ($parameterpencarian) {
                $query->where('users.username', '=', $parameterpencarian)
                    ->orWhere('email', '=', $parameterpencarian);
            })
            ->first();
    }
}
