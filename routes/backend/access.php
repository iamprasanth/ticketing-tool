<?php

Route::get('/', function () {
    return redirect('/users');
});

// Route for managing users
Route::get('users', ['as' => 'users', 'uses' => 'Users\UserController@getUsersList']);
Route::post('addUser', ['as' => 'User.add', 'uses' => 'Users\UserController@addUsers']);
Route::get('getUsers', ['as' => 'User.get', 'uses' => 'Users\UserController@getUsers']);
Route::post('viewUser/{id}', ['as' => 'User.view', 'uses' => 'Users\UserController@viewUsers']);
Route::post('editUser/{id}', ['as' => 'User.edit', 'uses' => 'Users\UserController@editUser']);
Route::post('updateUser/{id}', ['as' => 'User.update', 'uses' => 'Users\UserController@updateUser']);
Route::get('deleteUser/{id}', ['as' => 'User.delete', 'uses' => 'Users\UserController@deleteUser']);
Route::post('password/change', ['as' => 'password.change', 'uses' => 'Users\UserController@changePassword']);

// Route for managing users
Route::get('myprofile', ['as' => 'users', 'uses' => 'Users\UserController@getMyProfile']);

// Route for managing project category settings
Route::get('project-category', ['as' => 'projectcategories.list', 'uses' => 'Project\ProjectCategoryController@listProjectCategory']);
Route::post('project-category/add', ['as' => 'projectcategory.add', 'uses' => 'Project\ProjectCategoryController@addProjectCategory']);
Route::get('project-category/get', ['as' => 'projectcategory.get', 'uses' => 'Project\ProjectCategoryController@getProjectCategories']);
Route::get('project-category/edit/{id}', ['as' => 'projectcategory.edit', 'uses' => 'Project\ProjectCategoryController@editProjectCategory']);
Route::post('project-category/update', ['as' => 'projectcategory.update', 'uses' => 'Project\ProjectCategoryController@updateProjectCategory']);
Route::get('project-category/delete/{id}', ['as' => 'projectcategory.delete', 'uses' => 'Project\ProjectCategoryController@deleteProjectCategory']);

// Route for managing project label settings
Route::get('project-label', ['as' => 'projectlabels.list', 'uses' => 'Project\ProjectLabelController@listProjectLabel']);
Route::post('project-label/add', ['as' => 'projectlabel.add', 'uses' => 'Project\ProjectLabelController@addProjectLabel']);
Route::get('project-label/get', ['as' => 'projectlabel.get', 'uses' => 'Project\ProjectLabelController@getProjectLabels']);
Route::get('project-label/edit/{id}', ['as' => 'projectlabel.edit', 'uses' => 'Project\ProjectLabelController@editProjectLabel']);
Route::post('project-label/update', ['as' => 'projectlabel.update', 'uses' => 'Project\ProjectLabelController@updateProjectLabel']);
Route::get('project-label/delete/{id}', ['as' => 'projectlabel.delete', 'uses' => 'Project\ProjectLabelController@deleteProjectLabel']);

// Route for managing task label settings
Route::get('task-label', ['as' => 'tasklabels.list', 'uses' => 'Project\TaskLabelController@listTaskLabels']);
Route::post('task-label/add', ['as' => 'tasklabel.add', 'uses' => 'Project\TaskLabelController@addTaskLabel']);
Route::get('task-label/get', ['as' => 'tasklabel.get', 'uses' => 'Project\TaskLabelController@getTaskLabels']);
Route::get('task-label/edit/{id}', ['as' => 'tasklabel.edit', 'uses' => 'Project\TaskLabelController@editTaskLabel']);
Route::post('task-label/update', ['as' => 'tasklabel.update', 'uses' => 'Project\TaskLabelController@updateTaskLabel']);
Route::get('task-label/delete/{id}', ['as' => 'tasklabel.delete', 'uses' => 'Project\TaskLabelController@deleteTaskLabel']);

// Route for managing Projects Listing Page
Route::get('projects', ['as' => 'projects.list', 'uses' => 'Project\ProjectController@listProjects']);
Route::get('getprojects', ['as' => 'projects.get', 'uses' => 'Project\ProjectController@getProjects']);
Route::post('project/add', ['as' => 'project.add', 'uses' => 'Project\ProjectController@addProject']);
Route::get('project/complete/{id}', ['as' => 'project.complete', 'uses' => 'Project\ProjectController@markProjectAsComplete']);
Route::get('project/reopen/{id}', ['as' => 'project.incomplete', 'uses' => 'Project\ProjectController@markProjectAsInomplete']);
Route::get('completedprojects/get', ['as' => 'completedprojects.get', 'uses' => 'Project\ProjectController@getCompletedProjects']);
Route::get('project/delete/{id}', ['as' => 'project.delete', 'uses' => 'Project\ProjectController@deleteProject']);

// Route for managing Projects View Page
Route::get('projects/view/{id}', ['as' => 'projects.view', 'uses' => 'Project\ProjectController@viewProject']);
Route::get('project/edit/{id}', ['as' => 'projects.edit', 'uses' => 'Project\ProjectController@editProject']);
Route::post('addProjectMembers', ['as' => 'projectmembers.add', 'uses' => 'Project\ProjectController@addProjectMembers']);
Route::post('addProjectTaskList', ['as' => 'projectTaskList.add', 'uses' => 'Project\ProjectController@addProjectTaskList']);
Route::post('addsubTask', ['as' => 'addsubTask.add', 'uses' => 'Project\ProjectController@addSubTask']);
Route::get('view/subtask/{id}', ['as' => 'viewsubtask.view', 'uses' => 'Project\ProjectController@viewSubTask']);
Route::post('addTaskcomment/{taskGrpId}', ['as' => 'addTaskcomment.add', 'uses' => 'Project\ProjectController@addTaskcomment']);
Route::post('update/subtaskAssignee/{id}/{taskId}', ['as' => 'updatesubTaskLabel.update', 'uses' => 'Project\ProjectController@updateSubTaskAssignee']);
Route::post('update/subtaskLabel/{id}/{taskId}', ['as' => 'updatesubTaskLabel.update', 'uses' => 'Project\ProjectController@updateSubTaskLabel']);
Route::post('update/subtaskDate/{date}/{taskId}', ['as' => 'updatesubTaskDate.update', 'uses' => 'Project\ProjectController@updateSubTaskDate']);
Route::post('/updateProjectDetails', ['as' => 'updateproject', 'uses' => 'Project\ProjectController@updateProject']);
Route::post('updateSubscribersTask/{taskId}', ['as' => 'updatesubscriber.update', 'uses' => 'Project\ProjectController@updateSubscriberTask']);
Route::get('completed-tasks/{projectId}', ['as' => 'completedtasks.get', 'uses' => 'Project\ProjectController@getCompletedtasks']);
Route::get('subtask/incomplete/{id}', ['as' => 'subtask.incomplete', 'uses' => 'Project\ProjectController@markSubTaskAsIncomplete']);
Route::get('subtask/complete/{id}', ['as' => 'subtask.complete', 'uses' => 'Project\ProjectController@markSubTaskAsComplete']);
Route::get('taskgroup/complete/{id}', ['as' => 'taskgroup.complete', 'uses' => 'Project\ProjectController@markTaskGroupAsComplete']);
Route::delete('taskgroup/delete/{id}', ['as' => 'taskgroup.delete', 'uses' => 'Project\ProjectController@deleteTaskGroup']);
Route::get('edit/subtask/{id}', ['as' => 'editsubtask.view', 'uses' => 'Project\ProjectController@editSubTask']);
Route::post('editprojectsubtask', ['as' => 'editsubtask', 'uses' => 'Project\ProjectController@updateSubTask']);
Route::post('/addprojectfile', ['as' => 'addprojectfile', 'uses' => 'Project\ProjectController@addProjectFile']);
Route::get('getProjectFiles/{id}', ['as' => 'project.files', 'uses' => 'Project\ProjectController@getProjectFiles']);
Route::get('getProjectFiles/{id}', ['as' => 'project.files', 'uses' => 'Project\ProjectController@getProjectFiles']);
Route::delete('deleteprojectfile/{id}', ['as' => 'deleteprojectfile', 'uses' => 'Project\ProjectController@deleteProjectFile']);
Route::post('/addProjectAccess', ['as' => 'addprojectaccess', 'uses' => 'Project\ProjectController@addProjectAccess']);
Route::post('/project-access/update', ['as' => 'projectaccess.update', 'uses' => 'Project\ProjectController@updateProjectAccess']);
Route::delete('/project-access/delete/{id}', ['as' => 'projectaccess.delete', 'uses' => 'Project\ProjectController@deleteProjectAccess']);
Route::get('getProjectAccess/{id}', ['as' => 'project.access', 'uses' => 'Project\ProjectController@getProjectAccess']);
Route::get('edit/subtask/{id}', ['as' => 'editsubtask.view', 'uses' => 'Project\ProjectController@editSubTask']);
Route::post('editProjectTaskList', ['as' => 'projectTaskList.edit', 'uses' => 'Project\ProjectController@editProjectTaskList']);

// Route for managing ticket category settings
Route::get('ticket-category', ['as' => 'ticketcategory.list', 'uses' => 'Ticket\TicketCategoryController@listTicketCategory']);
Route::get('ticket-category/get', ['as' => 'ticketcategory.get', 'uses' => 'Ticket\TicketCategoryController@getTicketCategory']);
Route::post('ticket-category/add/', ['as' => 'ticketcategory.add', 'uses' => 'Ticket\TicketCategoryController@addTicketCategory']);
Route::get('ticket-category/edit/{id}', ['as' => 'ticketcategory.edit', 'uses' => 'Ticket\TicketCategoryController@editTicketCategory']);
Route::post('ticket-category/update', ['as' => 'ticketcategory.update', 'uses' => 'Ticket\TicketCategoryController@updateTicketCategory']);
Route::get('ticket-category/delete/{id}', ['as' => 'ticketcategory.delete', 'uses' => 'Ticket\TicketCategoryController@deleteTicketCategory']);

// Route for managing ticket status settings
Route::get('ticket-status', ['as' => 'ticket-status', 'uses' => 'Ticket\TicketStatusController@listTicketStatus']);
Route::get('ticket-status/get', ['as' => 'ticketstatus.get', 'uses' => 'Ticket\TicketStatusController@getTicketStatus']);
Route::post('ticket-status/add/', ['as' => 'ticketstatus.add', 'uses' => 'Ticket\TicketStatusController@addTicketStatus']);
Route::get('ticket-status/edit/{id}', ['as' => 'ticketstatus.edit', 'uses' => 'Ticket\TicketStatusController@editTicketStatus']);
Route::post('ticket-status/update', ['as' => 'ticketstatus.update', 'uses' => 'Ticket\TicketStatusController@updateTicketStatus']);
Route::get('ticket-status/delete/{id}', ['as' => 'ticketstatus.delete', 'uses' => 'Ticket\TicketStatusController@deleteTicketStatus']);

// Route for managing ticket module
Route::get('tickets', ['as' => 'tickets.list', 'uses' => 'Ticket\TicketController@listTickets']);
Route::get('tickets/get/{ticketStatus}', ['as' => 'tickets.get', 'uses' => 'Ticket\TicketController@getTickets']);
Route::post('tickets/add/', ['as' => 'tickets.add', 'uses' => 'Ticket\TicketController@addTickets']);
Route::get('tickets/edit/{id}', ['as' => 'tickets.edit', 'uses' => 'Ticket\TicketController@editTickets']);
Route::post('tickets/update', ['as' => 'tickets.update', 'uses' => 'Ticket\TicketController@updateTickets']);
Route::get('tickets/delete/{id}', ['as' => 'tickets.delete', 'uses' => 'Ticket\TicketController@deleteTicket']);
Route::get('ticket-file/delete/{fileId}/{ticketId}', ['as' => 'removeticketsattachment', 'uses' => 'Ticket\TicketController@deleteTicketFiles']);
Route::get('tickets/view/{id}', ['as' => 'tickets.view', 'uses' => 'Ticket\TicketController@viewTickets']);
Route::post('tickets/add_comment/', ['as' => 'tickets.add_comment', 'uses' => 'Ticket\TicketController@addTicketComment']);
Route::get('tickets/comment/delete/{id}', ['as' => 'tickets.delete_comment', 'uses' => 'Ticket\TicketController@deleteTicketComment']);
Route::post('tickets/comment/update', ['as' => 'tickets.update_comment', 'uses' => 'Ticket\TicketController@updateTicketComment']);
Route::get('edit-ticketstatus/{ticketId}/{ticketStatus}', ['as' => 'ticketstatus.edit', 'uses' => 'Ticket\TicketController@updateTicketStatus']);
Route::get('tickets/comment/edit/{id}', ['as' => 'tickets.edit_comment', 'uses' => 'Ticket\TicketController@editTicketComment']);
Route::get('ticket-comment-file/delete/{fileId}/{ticketCommentId}', ['as' => 'removeattachment', 'uses' => 'Ticket\TicketController@deleteTicketCommentFiles']);
