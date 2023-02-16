<?php

namespace TicketingTool\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Project extends Model
{
    protected $table = "projects";

    public function getProjectCategory()
    {
        return $this->hasOne('TicketingTool\Models\ProjectCategory', 'id', 'category')
                ->select(array('id', 'name'));
    }

    public function getProjectCreator()
    {
        return $this->hasOne('TicketingTool\Models\UserInfo', 'user_id', 'created_by')
                ->select(array('id', 'user_id', DB::raw('CONCAT_WS(" ",first_name, middle_name, last_name) AS name')));
    }

    public function getProjectManager()
    {
        return $this->hasOne('TicketingTool\Models\UserInfo', 'user_id', 'project_manager')
                ->select(array('id', 'user_id', DB::raw('CONCAT_WS(" ",first_name, middle_name, last_name) AS name')));
    }

    public function getProjectCreatorEmail()
    {
        return $this->hasOne('TicketingTool\Models\User', 'id', 'created_by')
                ->select(array('id', 'email'));
    }

    public function getProjectManagerEmail()
    {
        return $this->hasOne('TicketingTool\Models\User', 'id', 'project_manager')
                ->select(array('id', 'email'));
    }

    public function getProjectLabel()
    {
        return $this->hasOne('TicketingTool\Models\ProjectLabel', 'id', 'label')
                ->select(array('id', 'name'));
    }
}
