<?php

namespace TicketingTool\Http\Controllers\Backend\Project;

use TicketingTool\Http\Controllers\Controller;
use TicketingTool\Services\Backend\Project\ProjectCategoryService;
use Illuminate\Http\Request;
use Validator;
use Response;

class ProjectCategoryController extends Controller
{
    /**
    * @param serivice-instance  $settingService
    */
    public function __construct(ProjectCategoryService $projectCategoryService)
    {
        $this->middleware('auth');
        $this->projectCategoryService = $projectCategoryService;
    }

    /**
    * function for listing Project Categories
    */
    public function listProjectCategory(Request $request)
    {
        return view('Settings.project_categories');
    }

    /**
    * Function to add project Category
    */
    public function addProjectCategory(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:250|unique:project_categories,name,NULL,id,is_deleted,0',
            ],
            [
                'name.required' => __('ticketingtool.please_fill_this_field'),
                'name.unique' => __('ticketingtool.project_category_unqiue'),
                'name.max' => __('ticketingtool.character_limit_exceeds'),
            ]
        );
        if ($validator->fails()) {
            return Response::make([
                'message' => trans('ticketingtool.validation_failed'),
                'errors' => $validator->errors()
            ]);
        }
        if ($validator->passes()) {
            $this->projectCategoryService->addProjectCategory($request->all());

            return Response::json(['success' => '1']);
        }

        return Response::json(['errors' => $validator->errors()]);
    }

    /**
    * Function for get all project Categories
    */
    public function getProjectCategories()
    {
        return $this->projectCategoryService->getProjectCategories();
    }

    /**
    * Function for edit  project  Category
    */
    public function editProjectCategory($id)
    {
        return  $this->projectCategoryService->editProjectCategory($id);
    }

    /**
    * Function for update project Category
    */
    public function updateProjectCategory(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:250|unique:project_categories,name,'.$request['id'].',id,is_deleted,0'
            ],
            [
                'name.required' => __('ticketingtool.please_fill_this_field'),
                'name.unique' => __('ticketingtool.project_category_unqiue'),
                'name.max' => __('ticketingtool.character_limit_exceeds'),
            ]
        );
        if ($validator->fails()) {
            return Response::make([
                'message' => trans('ticketingtool.validation_failed'),
                'errors' => $validator->errors()
            ]);
        }
        if ($validator->passes()) {
            $this->projectCategoryService->updateProjectCategory($request->all());

            return Response::json(['success' => '1']);
        }

        return Response::json(['errors' => $validator->errors()]);
    }

    /**
    * Function for delete project Category
    */
    public function deleteProjectCategory($id)
    {
        return $this->projectCategoryService->deleteProjectCategory($id);
    }
}
