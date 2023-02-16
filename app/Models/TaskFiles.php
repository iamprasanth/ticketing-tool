<?php

namespace TicketingTool\Models;

use Illuminate\Database\Eloquent\Model;

class TaskFiles extends Model
{
    protected $table = "task_files";

    protected $casts = [
        'file' => 'array'
    ];
}
