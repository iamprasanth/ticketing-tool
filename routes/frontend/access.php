<?php

// Get all ticket catgories
Route::get('ticket-categories/get', 'TicketController@getTicketCategories');

// Get all types of ticket status
Route::get('ticket-status/get', 'TicketController@getTicketStatus');

// Add a new ticket
Route::post('ticket/add', 'TicketController@addTicket');

// Add a new ticket comment
Route::post('ticket-comment/add', 'TicketController@addTicketComment');

// Update status of a ticket
Route::post('ticket-status/update', 'TicketController@updateTicketStatus');

// Get details of ticket
Route::post('ticket/get', 'TicketController@getTicket');
