<?php

namespace TicketingTool\Http\Controllers\Frontend;

use TicketingTool\Http\Controllers\Controller;
use Response;

class FrontendBaseController extends Controller
{
    /**
     * Return the success json response
     */
    public function successResponse($output)
    {
        return Response::json([
                'success' => true,
                'message' =>  'ok',
                'output' => $output
        ], 200)
        ->withHeaders([
            'Access-Control-Allow-Origin' => '*'
        ]);
    }

    /**
     * Return the error json response
     */
    public function errorResponse($output, $message = '')
    {
        return Response::json([
                'success' => false,
                'message' =>  $message,
                'output' => $output
        ], 200)
        ->withHeaders([
            'Access-Control-Allow-Origin' => '*'
        ]);
    }
}
