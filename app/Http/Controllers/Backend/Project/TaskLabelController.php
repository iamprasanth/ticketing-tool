<?php

namespace TicketingTool\Http\Controllers\Backend\Project;

use TicketingTool\Http\Controllers\Controller;
use TicketingTool\Services\Backend\Project\TaskLabelService;
use Validator;
use Illuminate\Http\Request;
use Response;
use Auth;

class TaskLabelController extends Controller
{
    /**
    * @param serivice-instance  $taskLabelService
    */
    public function __construct(TaskLabelService $taskLabelService)
    {
        $this->middleware('auth');
        $this->taskLabelService = $taskLabelService;
    }

    /**
    * Function for list task labels
    */
    public function listTaskLabels()
    {
        return view('Settings.task_labels');
    }

    /**
    * Function to add task label
    */
    public function addTaskLabel(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:250|unique:task_labels,name,NULL,id,is_deleted,0',
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
            $this->taskLabelService->addTaskLabel($request->all());

            return Response::json(['success' => '1']);
        }

        return Response::json(['errors' => $validator->errors()]);
    }

    /**
    * Function for get all task label
    */
    public function getTaskLabels()
    {
        return $this->taskLabelService->getTaskLabels();
    }

    /**
    * Function for edit  task  list
    */
    public function editTaskLabel($id)
    {
        return  $this->taskLabelService->editTaskLabel($id);
    }

    /**
    * Function for update task list
    */
    public function updateTaskLabel(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:250|unique:task_labels,name,'.$request['id'].',id,is_deleted,0'
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
            $this->taskLabelService->updateTaskLabel($request->all());

            return Response::json(['success' => '1']);
        }

        return Response::json(['errors' => $validator->errors()]);
    }

    /**
    * Function for delete task label
    */
    public function deleteTaskLabel($id)
    {
        return $this->taskLabelService->deleteTaskLabel($id);
    }
}
