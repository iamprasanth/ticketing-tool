<?php

namespace TicketingTool\Services\Backend\Ticket;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use TicketingTool\Models\Ticket;
use TicketingTool\Models\TicketCategory;
use TicketingTool\Models\TicketStatus;
use TicketingTool\Models\TicketComment;
use DB;

class TicketService
{

    /**
     * Function for get all Ticket Categories
     */
    public function getTicketCategories()
    {
        return TicketCategory::select('name', 'id')
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->orderBy('id', 'desc')
            ->get();
    }

    /**
     * Function for get all Ticket Status
     */
    public function getTicketStatus()
    {
        return TicketStatus::select('name', 'id')
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->orderBy('id', 'desc')
            ->get();
    }

    /**
     * Function for get all Tickets
     */
    public function getTickets($ticketStatus)
    {
        $tickets =  Ticket::where('tickets.is_deleted', 0)
            ->with('ticketStatus')
            ->withCount('comments')
            ->orderBy('id', 'desc');
        if ($ticketStatus) {
            $tickets->where('status_id', $ticketStatus);
        }

        return $tickets->get();
    }

    /**
     * Function for add Tickets
     */
    public function addTickets($input)
    {
        $latestAddTicket = Ticket::latest()->first();
        $input['ticket_number'] = $this->generateTicketNumber($latestAddTicket);
        if ($input) {
            $data = [
                'name' => $input['name'],
                'email' => $input['email'],
                'telephone' => $input['mobile'],
                'project_name' => $input['project'],
                'subject' => $input['subject'],
                'category_id' => $input['categories'],
                'status_id' => $input['ticket_status'],
                'message' => $input['message'],
                'attachments' => (!empty($input['ticket_files'])) ? json_encode($input['ticket_files']) : null,
                'ticket_number' => $input['ticket_number'],
                'is_active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
            $ticketId = Ticket::insertGetId($data);
            if ($ticketId) {
                return  insertIntoTicketScheduler(
                    $ticketId,
                    'ticket.add',
                    'New Ticket',
                    'to_customer'
                );
            } else {
                return true;
            }
        }
    }

    /**
     * Function for edit  Tickets
     */
    public function editTickets($id)
    {
        return Ticket::where('id', $id)->first();
    }

    /*
     * Function for update Tickets
     */
    public function updateTickets($input)
    {
        $currentFiles = json_decode(json_encode(Ticket::where('id', $input['id'])->value('attachments')), true);
        if (is_null($currentFiles)) {
            $currentFiles = [];
        }
        if ($input) {
            $data = [
                'name' => $input['name'],
                'email' => $input['email'],
                'telephone' => $input['mobile'],
                'project_name' => $input['project'],
                'subject' => $input['subject'],
                'category_id' => $input['categories'],
                'status_id' => $input['ticket_status'],
                'message' => $input['message'],
                'updated_at' => Carbon::now(),
            ];
            if (isset($input['ticket_files'])) {
                $data['attachments'] = json_encode(array_merge($input['ticket_files'], $currentFiles));
            }

            $previousStatusId = Ticket::where('id', $input['id'])->value('status_id');
            if ($previousStatusId != (int) $input['ticket_status']) {
                insertIntoTicketScheduler(
                    $input['id'],
                    'ticket.status',
                    $previousStatusId,
                    'to_customer'
                );
            }

            return Ticket::where('id', $input['id'])->update($data);
        }
    }

    /**
     * Function for delete Ticket
     */
    public function deleteTicket($id)
    {
        return Ticket::where('id', $id)->update([
            'is_deleted' => 1,
            'updated_at' => Carbon::now()
        ]);
    }

    /**
     * Function for view Tickets
     */
    public function viewTickets($id)
    {
        return Ticket::select(array('id', 'name', 'email', 'attachments', 'telephone', 'project_name', 'category_id', 'status_id', 'message', 'subject', 'created_at'))
            ->where('id', $id)->with('ticketCategory', 'ticketStatus', 'comments')->first();
    }

    /*
     * Function for deleting a ticket attachment file
     */
    public function deleteTicketFiles($fileId, $ticketId)
    {
        $files = Ticket::where('id', $ticketId)->value('attachments');
        unset($files[$fileId]);

        return Ticket::where('id', $ticketId)
            ->update(['attachments' => json_encode($files)]);
    }

    //Function to generate ticket number
    public function generateTicketNumber($latestAddTicket)
    {
        $ticketNumber = mt_rand(1000, 9999);
        $ticketNumber = '#' . $ticketNumber;

        return $ticketNumber;
    }

    /**
     * Function for add Ticket Comments
     */
    public function addTicketComment($input)
    {
        if ($input) {
            $data = [
                'ticket_id' => $input['ticket_id'],
                'user_id' => Auth::user()->id,
                'description' => $input['comment'],
                'attachments' => (!empty($input['ticket_files'])) ? json_encode($input['ticket_files']) : null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
            $ticketCommentId = TicketComment::insertGetId($data);
            if ($ticketCommentId) {
                return  insertIntoTicketScheduler(
                    $input['ticket_id'],
                    'ticket.comment',
                    $ticketCommentId,
                    'to_customer'
                );
            } else {
                return true;
            }
        }
    }

    /**
     * Function for delete Ticket Comment
     */
    public function deleteTicketComment($id)
    {
        return TicketComment::where('id', $id)->update([
            'is_deleted' => 1,
            'updated_at' => Carbon::now()
        ]);
    }

    /**
     * Function for update Ticket Comment
     */
    public function updateTicketComment($input)
    {
        $currentFiles = json_decode(json_encode(TicketComment::where('id', $input['id'])->value('attachments')), true);
        if (is_null($currentFiles)) {
            $currentFiles = [];
        }
        if ($input) {
            $data = [
                'description' => $input['comment'],
                'updated_at' => Carbon::now()
            ];

            if (isset($input['ticket_files'])) {
                $data['attachments'] = json_encode(array_merge($input['ticket_files'], $currentFiles));
            }
            $mailData =  insertIntoTicketScheduler(
                $input['ticketId'],
                'ticket.update_comment',
                $input['id'],
                'to_customer'
            );
            if ($mailData) {
                return TicketComment::where('id', $input['id'])->update($data);
            }
        }
    }

    /**
     * Function for update  Ticket Status
     */
    public function updateTicketStatus($ticketId, $ticketStatus)
    {
        $previousStatusId = Ticket::where('id', $ticketId)->value('status_id');
        $statusId =  Ticket::where('id', $ticketId)->update([
            'status_id' => $ticketStatus,
            'updated_at' => Carbon::now()
        ]);
        if ($statusId) {
            insertIntoTicketScheduler(
                $ticketId,
                'ticket.status',
                $previousStatusId,
                'to_customer'
            );
        }
    }

    /**
     * Function for edit  Ticket Comments
     */
    public function editTicketComment($id)
    {
        return TicketComment::where('id', $id)->first();
    }

    /*
     * Function for deleting a ticket attachment file
     */
    public function deleteTicketCommentFiles($fileId, $ticketCommentId)
    {
        $files = TicketComment::where('id', $ticketCommentId)->value('attachments');
        unset($files[$fileId]);

        return TicketComment::where('id', $ticketCommentId)
            ->update(['attachments' => json_encode($files)]);
    }
}
