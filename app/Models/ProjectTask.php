<?php

namespace TicketingTool\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectTask extends Model
{
    protected $table = "project_tasks";

    public function getTaskAssignee()
    {
        return $this->hasOne('TicketingTool\Models\User', 'id', 'assignee');
    }

    public function getTaskLabel()
    {
        return $this->hasOne('TicketingTool\Models\TaskLabel', 'id', 'label');
    }

    public function getTaskGroup()
    {
        return $this->hasOne('TicketingTool\Models\TaskGroup', 'id', 'task_group_id');
    }

    public function getTaskComment()
    {
        return $this->hasMany('TicketingTool\Models\TaskComments', 'id', 'task_group_id');
    }

    public function taskGroup()
    {
        return $this->hasOne('TicketingTool\Models\TaskGroup', 'id', 'task_group_id')
                ->select(array('id', 'project_id'));
    }
}
