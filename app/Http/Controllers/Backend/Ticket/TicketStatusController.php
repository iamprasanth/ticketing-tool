<?php

namespace TicketingTool\Http\Controllers\Backend\Ticket;

use TicketingTool\Http\Controllers\Controller;
use TicketingTool\Services\Backend\Ticket\TicketStatusService;
use Validator;
use Response;
use Illuminate\Http\Request;

class TicketStatusController extends Controller
{
     /**
    * @param serivice-instance  $ticketStatusService
    */
    public function __construct(TicketStatusService $ticketStatusService)
    {
        $this->middleware('auth');
        $this->ticketStatusService = $ticketStatusService;
    }

     /**
    * function for listing Ticket Status
    */
    public function listTicketStatus(Request $request)
    {
        return view('Settings.ticket_status');
    }

    /**
    * Function for get all Ticket Status
    */
    public function getTicketStatus()
    {
        return $this->ticketStatusService->getTicketStatus();
    }


    /**
    * Function to add Ticket Status
    */
    public function addTicketStatus(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:250|unique:ticket_status,name,NULL,id,is_deleted,0',
            ],
            [
                'name.required' => __('ticketingtool.please_fill_this_field'),
                'name.unique' => __('ticketingtool.ticket_status_unqiue'),
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
            $this->ticketStatusService->addTicketStatus($request->all());

            return Response::json(['success' => '1']);
        }

        return Response::json(['errors' => $validator->errors()]);
    }

     /**
    * Function for edit  Ticket Status
    */
    public function editTicketStatus($id)
    {
        return  $this->ticketStatusService->editTicketStatus($id);
    }

     /**
    * Function for update Ticket Status
    */
    public function updateTicketStatus(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:250|unique:ticket_status,name,'.$request['id'].',id,is_deleted,0'
            ],
            [
                'name.required' => __('ticketingtool.please_fill_this_field'),
                'name.unique' => __('ticketingtool.ticket_status_unqiue'),
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
            $this->ticketStatusService->updateTicketStatus($request->all());

            return Response::json(['success' => '1']);
        }

        return Response::json(['errors' => $validator->errors()]);
    }

    /**
    * Function for delete Ticket Status
    */
    public function deleteTicketStatus($id)
    {
        return $this->ticketStatusService->deleteTicketStatus($id);
    }
}
