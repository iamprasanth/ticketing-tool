<?php

namespace TicketingTool\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = "tickets";

    protected $casts = [
        'attachments' => 'array'
    ];

    public function ticketCategory()
    {
        return $this->hasOne('TicketingTool\Models\TicketCategory', 'id', 'category_id')
            ->select(array('id', 'name'));
    }

    public function ticketStatus()
    {
        return $this->hasMany('TicketingTool\Models\TicketStatus', 'id', 'status_id')
            ->select(array('id', 'name'));
    }

    public function comments()
    {
        return $this->hasMany('TicketingTool\Models\TicketComment', 'ticket_id', 'id')
            ->select('id', 'user_id', 'ticket_id', 'description', 'attachments', 'created_at')
            ->where('is_deleted', 0)->orderBy('id', 'desc')->with('userInfo');
    }
}
