<?php

namespace TicketingTool\Http\Controllers\Frontend;

use TicketingTool\Http\Controllers\Frontend\FrontendBaseController;
use TicketingTool\Services\Frontend\TicketService;
use Validator;
use Response;
use Illuminate\Http\Request;

class TicketController extends FrontendBaseController
{
    /**
     * @param serivice-instance  $ticketService
     */
    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    /**
    * Function for get all Ticket Categories
    */
    public function getTicketCategories()
    {
        $ticketCategories = $this->ticketService->getTicketCategories();
        if ($ticketCategories) {
            return $this->successResponse($ticketCategories);
        }

        return $this->errorResponse([], trans('ticketingtool.no_result'));
    }

    /**
    * Function for get all Ticket Status
    */
    public function getTicketStatus()
    {
        $ticketStatus = $this->ticketService->getTicketStatus();
        if ($ticketStatus) {
            return $this->successResponse($ticketStatus);
        }

        return $this->errorResponse([], trans('ticketingtool.no_result'));
    }

    /**
     * Function for get details of a Tickets
     */
    public function getTicket(Request $request)
    {
        $ticket = $this->ticketService->getTicket($request['ticket_number']);
        if ($ticket) {
            return $this->successResponse($ticket);
        }

        return $this->errorResponse([], trans('ticketingtool.no_result'));
    }

    /**
     * Function to add ticket
     */
    public function addTicket(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string',
                'mobile' => 'required',
                'email' => 'required|email',
                'project' => 'required',
                'subject' => 'required',
                'message' => 'required',
                'categories' => 'required|not_in:0'
            ],
            [
                'mobile.numeric' => __('ticketingtool.valid_mobile_required'),
                'mobile.regex' => __('ticketingtool.valid_mobile_required'),
                'categories.not_in' => __('ticketingtool.choose_ticket_category'),
            ]
        );
        if ($validator->fails()) {
            return Response::make([
                'message' => trans('ticketingtool.validation_failed'),
                'errors' => $validator->errors()
            ]);
        }
        if ($request->file('fileupload')) {
            $files = $request->file('fileupload');
            $path = 'storage/tickets';
            $request['attachments_array'] = storeFilesToStorage($files, $path);
        }
        $ticketAdd = $this->ticketService->addTicket($request->all());
        if ($ticketAdd) {
            return $this->successResponse($ticketAdd);
        }

        return $this->errorResponse([], trans('ticketingtool.no_result'));
    }

    /**
     * Function to add new comment to a ticket
     */
    public function addTicketComment(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'description' => 'required|string'
            ]
        );
        if ($validator->fails()) {
            return Response::make([
                'message' => trans('ticketingtool.validation_failed'),
                'errors' => $validator->errors()
            ]);
        }
        if ($request->file('attachments')) {
            $files = $request->file('attachments');
            $path = 'storage/tickets';
            $request['attachments_array'] = storeFilesToStorage($files, $path);
        }
        $commentAdd = $this->ticketService->addTicketComment($request->all());
        if ($commentAdd) {
            return $this->successResponse($commentAdd);
        }

        return $this->errorResponse([], trans('ticketingtool.no_result'));
    }

    /**
     * Function to update status oF a ticket
     */
    public function updateTicketStatus(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'ticket_id' => 'required',
                'status_id' => 'required'
            ]
        );
        if ($validator->fails()) {
            return Response::make([
                'message' => trans('ticketingtool.validation_failed'),
                'errors' => $validator->errors()
            ]);
        }
        $statusUpdate = $this->ticketService->updateTicketStatus($request->all());
        if ($statusUpdate) {
            return $this->successResponse($statusUpdate);
        }

        return $this->errorResponse([], trans('ticketingtool.no_result'));
    }
}
