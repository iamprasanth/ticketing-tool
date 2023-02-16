<?php

namespace TicketingTool\Services\Backend\Ticket;

use Carbon\Carbon;
use TicketingTool\Models\TicketCategory;

class TicketCategoryService
{

    /**
     * Function for get all Ticket Categories
     */
    public function getTicketCategory()
    {
        return TicketCategory::select('name', 'is_active', 'id')
            ->where('is_deleted', 0)
            ->orderBy('id', 'desc')
            ->get();
    }

    /**
     * Function for add Ticket Category
     */
    public function addTicketCategory($input)
    {
        if ($input) {
            $data = [
                'name' => $input['name'],
                'is_active' => $input['is_active'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];

            return TicketCategory::insert($data);
        }
    }


    /**
     * Function for edit  Ticket Category
     */
    public function editTicketCategory($id)
    {
        return TicketCategory::where('id', $id)->first();
    }

    /**
     * Function for Ticket Category
     */
    public function updateTicketCategory($input)
    {
        return TicketCategory::where('id', $input['id'])->update([
            'name' => $input['name'],
            'is_active' => $input['is_active'],
            'updated_at' => Carbon::now()
        ]);
    }

    /**
     * Function for delete Ticket Category
     */
    public function deleteTicketCategory($id)
    {
        return TicketCategory::where('id', $id)->update([
            'is_deleted' => 1,
            'updated_at' => Carbon::now()
        ]);
    }
}
