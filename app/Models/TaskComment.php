<?php

namespace TicketingTool\Models;

use Illuminate\Database\Eloquent\Model;

class TaskComment extends Model
{
    protected $table = "task_comments";

    public function getTaskFiles()
    {
        return $this->hasOne('TicketingTool\Models\TaskFiles', 'comment_id', 'id')->where('is_deleted', 0);
    }

    public function getCommentedBy()
    {
        return $this->hasOne('TicketingTool\Models\UserInfo', 'user_id', 'commented_by')->select(array('id', 'user_id', 'first_name', 'middle_name', 'last_name'));
    }
}
