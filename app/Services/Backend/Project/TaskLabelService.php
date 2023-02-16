<?php

namespace TicketingTool\Services\Backend\Project;

use Carbon\Carbon;
use TicketingTool\Models\TaskLabel;
use Auth;
use DB;

class TaskLabelService
{
    /**
    * Function for add task label
    */
    public function addTaskLabel($input)
    {
        if ($input) {
            $data = [
                'name' => $input['name'],
                'is_active' => $input['is_active'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];

            return TaskLabel::insert($data);
        }
    }

    /**
    * Function for get all task labels
    */
    public function getTaskLabels()
    {
        return TaskLabel::select('name', 'is_active', 'id')
                         ->where('is_deleted', 0)
                         ->get()->toArray();
    }

    /**
    * Function for edit  task label
    */
    public function editTaskLabel($id)
    {
        return TaskLabel::where('id', $id)->first();
    }

    /**
    * Function for update task label
    */
    public function updateTaskLabel($input)
    {
        return TaskLabel::where('id', $input['id'])->update([
            'name' => $input['name'],
            'is_active' => $input['is_active'],
            'updated_at' => Carbon::now()
        ]);
    }

    /**
    * Function for delete task label
    */
    public function deleteTaskLabel($id)
    {
        return TaskLabel::where('id', $id)->update([
            'is_deleted' => 1,
            'updated_at' => Carbon::now()
        ]);
    }
}
