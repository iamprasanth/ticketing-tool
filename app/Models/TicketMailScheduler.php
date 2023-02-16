<?php

namespace TicketingTool\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class TicketMailScheduler extends Model
{
    protected $table = "ticket_mail_scheduler";

    public function userInfo()
    {
        return $this->hasOne('TicketingTool\Models\UserInfo', 'user_id', 'creator_id')
            ->select(array('id','user_id',DB::raw('CONCAT_WS(" ",first_name, middle_name, last_name) AS name')));
    }
}
