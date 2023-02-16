<?php

namespace TicketingTool\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getUserInfo()
    {
        return $this->hasOne('TicketingTool\Models\UserInfo', 'user_id', 'id');
    }

    public function getUserName()
    {
        return $this->hasOne('TicketingTool\Models\UserInfo', 'user_id', 'id')
                    ->select(array('user_id', DB::raw('CONCAT_WS(" ",first_name, middle_name, last_name) AS user')));
    }
}
