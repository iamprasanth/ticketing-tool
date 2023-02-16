<?php
namespace TicketingTool\Services\Frontend;

use TicketingTool\Models\Ticket;
use TicketingTool\Models\TicketCategory;
use TicketingTool\Models\TicketStatus;
use TicketingTool\Models\TicketComment;
use Carbon\Carbon;

class TicketService
{

    /**
     * Function for get all Ticket Status
     */
    public function getTicketStatus()
    {
        return TicketStatus::select('name', 'id')
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->get();
    }

    /**
     * Function for get all Ticket categories
     */
    public function getTicketCategories()
    {
        return TicketCategory::select('name', 'id')
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->get();
    }

    /**
     * Function for add Tickets
     */
    public function addTicket($input)
    {
        $latestAddTicket = Ticket::latest()->first();
        $input['ticket_number'] = $this->generateTicketNumber($latestAddTicket);
        $data = [
            'name' => $input['name'],
            'email' => $input['email'],
            'telephone' => $input['mobile'],
            'project_name' => $input['project'],
            'subject' => $input['subject'],
            'category_id' => $input['categories'],
            'status_id' => 1,
            'message' => $input['message'],
            'attachments' => (!empty($input['attachments_array'])) ? json_encode($input['attachments_array']) : null,
            'ticket_number' => $input['ticket_number'],
            'created_at' => Carbon::now()
        ];
        $ticketId = Ticket::insertGetId($data);
        if ($ticketId) {
            insertIntoTicketScheduler(
                $ticketId,
                'ticket.add',
                'New Ticket',
                $comment = 'to_customer'
            );

            return  insertIntoTicketScheduler(
                $ticketId,
                'ticket.add',
                'New Ticket',
                $comment = 'to_admin'
            );
        }

        return $ticketId;
    }

    /**
     * Function to generate ticket number
     */
    public function generateTicketNumber($latestAddTicket)
    {
        if ($latestAddTicket['ticket_number'] == null) {
            $ticketNumber = '#0001';
        } else {
            $prevoiusTicketNumber = explode("#", $latestAddTicket['ticket_number']);
            $newTicketNumber = $prevoiusTicketNumber[1] + 1;
            $ticketNumber = str_pad($newTicketNumber, 4, '0', STR_PAD_LEFT);
            $ticketNumber = '#' . $ticketNumber;
        }

        return $ticketNumber;
    }

    /**
     * Function for get details of a Tickets
     */
    public function getTicket($ticketNumber)
    {
        return Ticket::where('ticket_number', $ticketNumber)
                    ->with(
                        'ticketStatus',
                        'ticketCategory',
                        'comments'
                    )->first();
    }

    /**
     * Function to add a new comment to a ticket
     */
    public function addTicketComment($request)
    {
        $ticketCommentId = TicketComment::insertGetId([
            'ticket_id' => $request['ticket_id'],
            'description' => $request['description'],
            'attachments' => (!empty($request['attachments_array'])) ? json_encode($request['attachments_array']) : null,
            'created_at' => Carbon::now()
        ]);
        if ($ticketCommentId) {
            return  insertIntoTicketScheduler(
                $request['ticket_id'],
                'ticket.comment',
                $ticketCommentId,
                $comment = 'to_admin'
            );
        }

        return $ticketCommentId;
    }

    /**
     * Function to update status oF a ticket
     */
    public function updateTicketStatus($request)
    {
        insertIntoTicketScheduler(
            $request['ticket_id'],
            'ticket.status',
            $request['previous_ticket_id'],
            $comment = 'to_admin'
        );

        return Ticket::where('id', $request['ticket_id'])
                    ->update([
                        'status_id' => $request['status_id']
                    ]);
    }
}
