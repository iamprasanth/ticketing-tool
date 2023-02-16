<?php

namespace TicketingTool\Models;

use Illuminate\Database\Eloquent\Model;

class TaskGroup extends Model
{
    protected $table = "task_group";

    public function getSubTask()
    {
        return $this->hasMany('TicketingTool\Models\ProjectTask', 'task_group_id', 'id')
                   ->where('project_tasks.is_completed', 0);
    }

    public function getCompletedSubTask()
    {
        return $this->hasMany('TicketingTool\Models\ProjectTask', 'task_group_id', 'id')
                   ->select(array('id', 'task_group_id', 'task_name'))
                   ->where('project_tasks.is_completed', 1);
    }
}
