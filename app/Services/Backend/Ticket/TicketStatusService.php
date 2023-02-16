<?php

namespace TicketingTool\Services\Backend\Ticket;

use Carbon\Carbon;
use TicketingTool\Models\TicketStatus;

class TicketStatusService
{
    /**
     * Function for get all Ticket Status
     */
    public function getTicketStatus()
    {
        return TicketStatus::select('name', 'is_active', 'id')
            ->where('is_deleted', 0)
            ->orderBy('id', 'desc')
            ->get();
    }

    /**
     * Function for add Ticket Status
     */
    public function addTicketStatus($input)
    {
        if ($input) {
            $data = [
                'name' => $input['name'],
                'is_active' => $input['is_active'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];

            return TicketStatus::insert($data);
        }
    }

    /**
     * Function for edit  Ticket Status
     */
    public function editTicketStatus($id)
    {
        return TicketStatus::where('id', $id)->first();
    }

    /**
     * Function for Ticket Status
     */
    public function updateTicketStatus($input)
    {
        return TicketStatus::where('id', $input['id'])->update([
            'name' => $input['name'],
            'is_active' => $input['is_active'],
            'updated_at' => Carbon::now()
        ]);
    }

    /**
     * Function for delete Ticket Status
     */
    public function deleteTicketStatus($id)
    {
        return TicketStatus::where('id', $id)->update([
            'is_deleted' => 1,
            'updated_at' => Carbon::now()
        ]);
    }
}
