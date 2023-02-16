<?php

use Illuminate\Support\Facades\Request;
use TicketingTool\Models\ProjectMailScheduler;
use TicketingTool\Models\TicketMailScheduler;
use Carbon\Carbon;
use TicketingTool\Models\TaskGroup;
use TicketingTool\Models\ProjectTask;
use TicketingTool\Models\TaskLabel;
use TicketingTool\Models\UserInfo;

// Function to check if given array of routes are active
function areActiveRoutes(array $routes)
{
    $currentRoute = Request::route()->getName();
    if (isset($currentRoute)) {
        foreach ($routes as $route) {
            if ($currentRoute == $route) {
                return true;
            }
        }
    }

    return false;
}

// function for inserting mail content to the database
function insertIntoProjectScheduler($projectId, $mailType, $recievers, $description, $cc = null, $subTask)
{
    $insertData = [
        'project_id' => $projectId,
        'creator_id' => Auth::user()->id,
        'sender_id' => Config('constants.SENDER_MAIL'),
        'mail_type' => $mailType,
        'receiver_id' => $recievers,
        'description' => $description,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
    ];

    return ProjectMailScheduler::insert($insertData);
}

function getMembersTask($projectId, $id)
{
    $taskGroup = TaskGroup::where('project_id', $projectId)->select('id')->get();
    foreach ($taskGroup as $key => $value) {
        $taskGroups[] = $value->id;
    }
    if (!$taskGroup->isEmpty()) {
        return ProjectTask::where('assignee', $id)->whereIn('task_group_id', $taskGroups)->where('is_completed', 0)->count();
    }
}

function getCompletedtasksNo($projectId)
{
    return ProjectTask::select('id', 'task_name', 'task_group_id')
        ->where('is_completed', 1)
        ->with('taskGroup')
        ->whereHas('taskGroup', function ($q) use ($projectId) {
            $q->where('project_id', $projectId)
                ->where('task_group.is_deleted', 0);
        })->count();
}

function getSubTaskCount($id)
{
    return ProjectTask::where('task_group_id', $id)->where('is_completed', 0)->count();
}

function getAssigneeName($id)
{
    $assignee = ProjectTask::where('id', $id)->select('assignee')->first();
    if ($assignee) {
        return DB::table('user_info')
            ->select(DB::raw('CONCAT_WS(" ",first_name, middle_name, last_name) AS username'))->where('user_id', $assignee->assignee)
            ->where('is_deleted', 0)->first();
    } else {
        return null;
    }
}

function getSubtaskLabel($id)
{
    $label = ProjectTask::where('id', $id)->select('label')->first();
    if ($label->label != 0) {
        return TaskLabel::where('id', $label->label)->select('name')->first();
    } else {
        return null;
    }
}

function getTaskGroup($id)
{
    return ProjectTask::select(
        'task_group_id'
    )->where('id', $id)->first();
}

//Function for getting User's full name
function getUserName()
{
    return UserInfo::select(
        DB::raw('CONCAT_WS(" ",first_name, middle_name, last_name) AS name')
    )->where('user_id', Auth::user()->id)->value('name');
}

//Function to store files to storage
function storeFilesToStorage($files, $filePath)
{
    foreach ($files as $key => $file) {
        $mimeType = $file->getClientOriginalName();
        $ext = explode('.', $mimeType);
        $path = public_path() . '/' . $filePath;
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        $storedName = $ext[0] . '-' . time() . '.' . $ext[1];
        $file->move($path, $storedName);
        $attachments[$key] = [
            'file_name' => $file->getClientOriginalName(),
            'stored_name' => $storedName
        ];
    }

    return $attachments;
}

// function for inserting mail content to the database
function insertIntoTicketScheduler($ticketId, $mailType, $description, $comment = null)
{
    $insertData = [
        'ticket_id' => $ticketId,
        'creator_id' => Auth::check() ? Auth::user()->id : null,
        'sender_id' => Config('constants.SENDER_MAIL'),
        'mail_type' => $mailType,
        'description' => $description,
        'comment' => $comment,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
    ];

    return TicketMailScheduler::insert($insertData);
}
