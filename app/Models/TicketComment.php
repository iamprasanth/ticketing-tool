<?php

namespace TicketingTool\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class TicketComment extends Model
{
    protected $table = "ticket_comments";

    protected $casts = [
        'attachments' => 'array'
    ];

    public function userInfo()
    {
        return $this->hasOne('TicketingTool\Models\UserInfo', 'user_id', 'user_id')
            ->select(array('id','user_id',DB::raw('CONCAT_WS(" ",first_name, middle_name, last_name) AS name')));
    }
}
