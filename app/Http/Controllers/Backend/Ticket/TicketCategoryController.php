<?php

namespace TicketingTool\Http\Controllers\Backend\Ticket;

use TicketingTool\Http\Controllers\Controller;
use TicketingTool\Services\Backend\Ticket\TicketCategoryService;
use Validator;
use Response;
use Illuminate\Http\Request;

class TicketCategoryController extends Controller
{

    /**
    * @param serivice-instance  $ticketCategoryService
    */
    public function __construct(TicketCategoryService $ticketCategoryService)
    {
        $this->middleware('auth');
        $this->ticketCategoryService = $ticketCategoryService;
    }

     /**
    * function for listing Ticket Categories
    */
    public function listTicketCategory(Request $request)
    {
        return view('Settings.ticket_categories');
    }

    /**
    * Function for get all Ticket Categories
    */
    public function getTicketCategory()
    {
        return $this->ticketCategoryService->getTicketCategory();
    }

     /**
    * Function to add ticket Category
    */
    public function addTicketCategory(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:250|unique:ticket_categories,name,NULL,id,is_deleted,0',
            ],
            [
                'name.required' => __('ticketingtool.please_fill_this_field'),
                'name.unique' => __('ticketingtool.ticket_category_unqiue'),
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
            $this->ticketCategoryService->addTicketCategory($request->all());

            return Response::json(['success' => '1']);
        }

        return Response::json(['errors' => $validator->errors()]);
    }

    /**
    * Function for edit  ticket  Category
    */
    public function editTicketCategory($id)
    {
        return  $this->ticketCategoryService->editTicketCategory($id);
    }

     /**
    * Function for update ticket Category
    */
    public function updateTicketCategory(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:250|unique:ticket_categories,name,'.$request['id'].',id,is_deleted,0'
            ],
            [
                'name.required' => __('ticketingtool.please_fill_this_field'),
                'name.unique' => __('ticketingtool.ticket_category_unqiue'),
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
            $this->ticketCategoryService->updateTicketCategory($request->all());

            return Response::json(['success' => '1']);
        }

        return Response::json(['errors' => $validator->errors()]);
    }

    /**
    * Function for delete ticket Category
    */
    public function deleteTicketCategory($id)
    {
        return $this->ticketCategoryService->deleteTicketCategory($id);
    }

}
