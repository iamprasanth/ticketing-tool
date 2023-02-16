<?php

namespace TicketingTool\Http\Controllers\Backend\Project;

use TicketingTool\Http\Controllers\Controller;
use TicketingTool\Services\Backend\Project\ProjectLabelService;
use Validator;
use Illuminate\Http\Request;
use Response;

class ProjectLabelController extends Controller
{
    /**
    * @param serivice-instance  $projectLabelService
    */
    public function __construct(ProjectLabelService $projectLabelService)
    {
        $this->middleware('auth');
        $this->projectLabelService = $projectLabelService;
    }

    /**
    * Function for list project labels
    */
    public function listProjectLabel()
    {
        return view('Settings.project_labels');
    }

    /**
    * Function to add project label
    */
    public function addProjectLabel(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:250|unique:project_labels,name,NULL,id,is_deleted,0',
            ],
            [
                'name.required' => __('ticketingtool.please_fill_this_field'),
                'name.unique' => __('ticketingtool.project_label_unqiue'),
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
            $this->projectLabelService->addProjectLabel($request->all());

            return Response::json(['success' => '1']);
        }

        return Response::json(['errors' => $validator->errors()]);
    }

    /**
    * Function for get all project label
    */
    public function getProjectLabels()
    {
        return $this->projectLabelService->getProjectLabels();
    }

    /**
    * Function for edit  project  list
    */
    public function editProjectLabel($id)
    {
        return  $this->projectLabelService->editProjectLabel($id);
    }

    /**
    * Function for update project list
    */
    public function updateProjectLabel(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:250|unique:project_labels,name,'.$request['id'].',id,is_deleted,0'
            ],
            [
                'name.required' => __('ticketingtool.please_fill_this_field'),
                'name.unique' => __('ticketingtool.project_label_unqiue'),
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
            $this->projectLabelService->updateProjectLabel($request->all());

            return Response::json(['success' => '1']);
        }

        return Response::json(['errors' => $validator->errors()]);
    }

    /**
    * Function for delete employee code
    */
    public function deleteProjectLabel($id)
    {
        return $this->projectLabelService->deleteProjectLabel($id);
    }
}
