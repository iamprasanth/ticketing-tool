<?php

namespace TicketingTool\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectMembers extends Model
{
    protected $table = "project_members";

    public function projectDetails()
    {
        return $this->hasOne('TicketingTool\Models\Project', 'id', 'project_id')
                    ->select(array(
                        'id',
                        'project_name',
                        'description',
                        'category',
                        'client_company',
                        'label'
                    ))->where('is_completed', 0)
                      ->where('is_deleted', 0)
                      ->where('is_active', 1);
    }
}
