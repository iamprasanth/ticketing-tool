<?php

namespace TicketingTool\Http\Controllers\Backend\Ticket;

use TicketingTool\Http\Controllers\Controller;
use TicketingTool\Services\Backend\Ticket\TicketService;
use Validator;
use Response;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * @param serivice-instance  $ticketService
     */
    public function __construct(TicketService $ticketService)
    {
        $this->middleware('auth');
        $this->ticketService = $ticketService;
    }

    /**
     * function for listing Tickets
     */
    public function listTickets(Request $request)
    {
        $ticketCategories = $this->ticketService->getTicketCategories();
        $ticketStatus = $this->ticketService->getTicketStatus();

        return view(
            'Ticket.list',
            [
                'ticketCategories' => $ticketCategories,
                'ticketStatus' => $ticketStatus
            ]
        );
    }

    /**
     * Function for get all Tickets
     */
    public function getTickets($ticketStatus)
    {
        return $this->ticketService->getTickets($ticketStatus);
    }

    /**
     * Function to add ticket
     */
    public function addTickets(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string',
                'mobile' => 'required|numeric',
                'email' => 'required|email',
                'project' => 'required',
                'subject' => 'required|max:200',
                'message' => 'required',
                'categories' => 'required|not_in:0',
                'ticket_status' => 'required|not_in:0',
            ],
            [
                'name.required' => __('ticketingtool.please_fill_this_field'),
                'mobile.required' => __('ticketingtool.please_fill_this_field'),
                'mobile.numeric' => __('ticketingtool.valid_mobile_required'),
                'project.required' => __('ticketingtool.please_fill_this_field'),
                'message.required' => __('ticketingtool.please_fill_this_field'),
                'email.required' => __('ticketingtool.please_fill_this_field'),
                'subject.required' => __('ticketingtool.please_fill_this_field'),
                'categories.required' => __('ticketingtool.please_fill_this_field'),
                'categories.not_in' => __('ticketingtool.choose_ticket_category'),
                'ticket_status.required' => __('ticketingtool.please_fill_this_field'),
                'ticket_status.not_in' => __('ticketingtool.choose_ticket_status'),
                'subject.max' => __('ticketingtool.character_limit_exceeds'),

            ]
        );
        if ($validator->fails()) {
            return Response::make([
                'message' => trans('ticketingtool.validation_failed'),
                'errors' => $validator->errors()
            ]);
        }
        if ($validator->passes()) {
            if ($request->file('fileupload')) {
                $files = $request->file('fileupload');
                $path = 'storage/tickets';
                $request['ticket_files'] = storeFilesToStorage($files, $path);
            }
            $this->ticketService->addTickets($request->all());

            return Response::json(['success' => '1']);
        }

        return Response::json(['errors' => $validator->errors()]);
    }

    /**
     * Function for edit  tickets
     */
    public function editTickets($id)
    {
        return  $this->ticketService->editTickets($id);
    }

    /**
     * Function for update ticket
     */
    public function updateTickets(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string',
                'mobile' => 'required|numeric',
                'email' => 'required|email',
                'project' => 'required',
                'message' => 'required',
                'subject' => 'required|max:200',
                'categories' => 'required|not_in:0',
                'ticket_status' => 'required|not_in:0',
            ],
            [
                'name.required' => __('ticketingtool.please_fill_this_field'),
                'mobile.required' => __('ticketingtool.please_fill_this_field'),
                'mobile.numeric' => __('ticketingtool.valid_mobile_required'),
                'email.required' => __('ticketingtool.please_fill_this_field'),
                'project.required' => __('ticketingtool.please_fill_this_field'),
                'message.required' => __('ticketingtool.please_fill_this_field'),
                'subject.required' => __('ticketingtool.please_fill_this_field'),
                'categories.required' => __('ticketingtool.please_fill_this_field'),
                'categories.not_in' => __('ticketingtool.choose_ticket_category'),
                'ticket_status.required' => __('ticketingtool.please_fill_this_field'),
                'ticket_status.not_in' => __('ticketingtool.choose_ticket_status'),
                'subject.max' => __('ticketingtool.character_limit_exceeds'),

            ]
        );
        if ($validator->fails()) {
            return Response::make([
                'message' => trans('ticketingtool.validation_failed'),
                'errors' => $validator->errors()
            ]);
        }
        if ($validator->passes()) {
            if ($request->file('fileupload')) {
                $files = $request->file('fileupload');
                $path = 'storage/tickets';
                $request['ticket_files'] = storeFilesToStorage($files, $path);
            }
            $this->ticketService->updateTickets($request->all());

            return Response::json(['success' => '1']);
        }

        return Response::json(['errors' => $validator->errors()]);
    }

    /**
     * Function for delete ticket
     */
    public function deleteTicket($id)
    {
        return $this->ticketService->deleteTicket($id);
    }

    /**
     * Function for view ticket
     */
    public function viewTickets($id)
    {
        $ticketInfo = $this->ticketService->viewTickets($id);
        $ticketCategories = $this->ticketService->getTicketCategories();
        $ticketStatus = $this->ticketService->getTicketStatus();
        return view('Ticket.view', [
            'ticketInfo' => $ticketInfo,
            'ticketCategories' => $ticketCategories,
            'ticketStatus' => $ticketStatus,
        ]);
    }

    /*
     * Function for deleting a ticket attachment file
     */
    public function deleteTicketFiles($fileId, $ticketId)
    {
        return $this->ticketService->deleteTicketFiles($fileId, $ticketId);
    }

    /**
     * Function to add ticket comments
     */
    public function addTicketComment(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'comment' => 'required|max:4000',
            ],
            [
                'comment.required' => __('ticketingtool.please_fill_this_field'),
                'comment.max' => __('ticketingtool.character_limit_exceeds'),
            ]
        );
        if ($validator->fails()) {
            return Response::make([
                'message' => trans('ticketingtool.validation_failed'),
                'errors' => $validator->errors()
            ]);
        }
        if ($validator->passes()) {
            if ($request->file('fileupload')) {
                $files = $request->file('fileupload');
                $path = 'storage/tickets';
                $request['ticket_files'] = storeFilesToStorage($files, $path);
            }
            $this->ticketService->addTicketComment($request->all());

            return Response::json(['success' => '1']);
        }

        return Response::json(['errors' => $validator->errors()]);
    }

    /*
     * Function for deleting a ticket comment
     */
    public function deleteTicketComment($id)
    {
        return $this->ticketService->deleteTicketComment($id);
    }

    /*
     * Function for updating  ticket comment
     */
    public function updateTicketComment(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'comment' => 'required|max:4000',
            ],
            [
                'comment.required' => __('ticketingtool.please_fill_this_field'),
                'comment.max' => __('ticketingtool.character_limit_exceeds'),
            ]
        );
        if ($validator->fails()) {
            return Response::make([
                'message' => trans('ticketingtool.validation_failed'),
                'errors' => $validator->errors(),
            ]);
        }
        if ($request->file('fileupload')) {
            $files = $request->file('fileupload');
            $path = 'storage/tickets';
            $request['ticket_files'] = storeFilesToStorage($files, $path);
        }
        $this->ticketService->updateTicketComment($request);

        return Response::json(['success' => '1']);
    }

    //Function to update ticket status
    public function updateTicketStatus($ticketId, $ticketStatus)
    {
        return $this->ticketService->updateTicketStatus($ticketId, $ticketStatus);
    }

    /**
     * Function for edit  ticket comment
     */
    public function editTicketComment($id)
    {
        return  $this->ticketService->editTicketComment($id);
    }

     /*
     * Function for deleting a ticket comment attachment file
     */
    public function deleteTicketCommentFiles($fileId, $ticketCommentId)
    {
        return $this->ticketService->deleteTicketCommentFiles($fileId, $ticketCommentId);
    }
}
