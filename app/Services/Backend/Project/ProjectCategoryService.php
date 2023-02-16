<?php

namespace TicketingTool\Services\Backend\Project;

use Carbon\Carbon;
use TicketingTool\Models\ProjectCategory;

class ProjectCategoryService
{
    /**
    * function for listing Project Categories
    */
    public function listProjectCategories(Request $request)
    {
        return view('settings.openingslist');
    }

    /**
    * Function for add project Category
    */
    public function addProjectCategory($input)
    {
        if ($input) {
            $data = [
                'name' => $input['name'],
                'is_active' => $input['is_active'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];

            return ProjectCategory::insert($data);
        }
    }

    /**
    * Function for get all project Category
    */
    public function getProjectCategories()
    {
        return ProjectCategory::select('name', 'is_active', 'id')
                               ->where('is_deleted', 0)
                               ->get()->toArray();
    }

    /**
    * Function for edit  project Category
    */
    public function editProjectCategory($id)
    {
        return ProjectCategory::where('id', $id)->first();
    }

    /**
    * Function for update project Category
    */
    public function updateProjectCategory($input)
    {
        return ProjectCategory::where('id', $input['id'])->update([
            'name' => $input['name'],
            'is_active' => $input['is_active'],
            'updated_at' => Carbon::now()
        ]);
    }

    /**
    * Function for delete project Category
    */
    public function deleteProjectCategory($id)
    {
        return ProjectCategory::where('id', $id)->update([
            'is_deleted' => 1,
            'updated_at' => Carbon::now()
        ]);
    }
}
