<?php

namespace TicketingTool\Services\Backend\Project;

use Carbon\Carbon;
use TicketingTool\Models\ProjectLabel;
use Auth;
use DB;

class ProjectLabelService
{
    /**
    * Function for add project label
    */
    public function addProjectLabel($input)
    {
        if ($input) {
            $data = [
                'name' => $input['name'],
                'is_active' => $input['is_active'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];

            return ProjectLabel::insert($data);
        }
    }

    /**
    * Function for get all project labels
    */
    public function getProjectLabels()
    {
        return ProjectLabel::select('name', 'is_active', 'id')
                             ->where('is_deleted', 0)
                             ->get()->toArray();
    }

    /**
    * Function for edit  project label
    */
    public function editProjectLabel($id)
    {
        return ProjectLabel::where('id', $id)->first();
    }

    /**
    * Function for update project label
    */
    public function updateProjectLabel($input)
    {
        return ProjectLabel::where('id', $input['id'])->update([
            'name' => $input['name'],
            'is_active' => $input['is_active'],
            'updated_at' => Carbon::now()
        ]);
    }

    /**
    * Function for delete project label
    */
    public function deleteProjectLabel($id)
    {
        return ProjectLabel::where('id', $id)->update([
            'is_deleted' => 1,
            'updated_at' => Carbon::now()
        ]);
    }
}
