<?php

namespace TicketingTool\Services\Backend\Project;

use TicketingTool\Models\User;
use TicketingTool\Models\ProjectLabel;
use TicketingTool\Models\TaskLabel;
use TicketingTool\Models\ProjectCategory;
use TicketingTool\Models\Project;
use TicketingTool\Models\ProjectMembers;
use TicketingTool\Models\UserInfo;
use TicketingTool\Models\TaskGroup;
use TicketingTool\Models\ProjectTask;
use TicketingTool\Models\TaskComment;
use TicketingTool\Models\TaskFiles;
use TicketingTool\Models\ProjectFiles;
use TicketingTool\Models\ProjectAccess;
use Carbon\Carbon;
use Auth;
use DB;

class ProjectService
{
    /**
    * Function for getting all active user_info
    */
    public function getUsers()
    {
        return User::select('id')
                    ->where('is_active', 1)
                    ->where('is_deleted', 0)
                    ->with('getUserName')
                    ->get()->toArray();
    }

    /**
    * Function for getting all project labels
    */
    public function getProjectLabels()
    {
        return ProjectLabel::select('name', 'is_active', 'id')
                            ->where('is_deleted', 0)->where('is_active', 1)->orderBy('name', 'ASC')
                            ->get()->toArray();
    }

    /**
    * Function for getting all task labels
    */
    public function getTaskLabels()
    {
        return TaskLabel::select('name', 'is_active', 'id')
                        ->where('is_deleted', 0)->where('is_active', 1)->orderBy('name', 'ASC')
                        ->get()->toArray();
    }

    /**
    * Function for getting all project category
    */
    public function getProjectCategories()
    {
        return ProjectCategory::select('name', 'is_active', 'id')
                               ->where('is_deleted', 0)->where('is_active', 1)->get()->toArray();
    }

    /**
    * Function to add project
    */
    public function addProject($request)
    {
        if (!empty($request)) {
            $data = [
                   'project_manager' => $request['project_manager'] != 0 ? $request['project_manager'] : null,
                   'project_name' => $request['project_name'],
                   'description' => $request['description'],
                   'label' => $request['project_label'] != 0 ? $request['project_label'] : null,
                   'category' => $request['project_category'] != 0 ? $request['project_category'] : null,
                   'client_company' => $request['client_company'],
                   'additional_info' => $request['additional_info'],
                   'created_by' => Auth::user()->id,
                   'is_active' => 1,
                   'created_at' => Carbon::now(),
                   'updated_at' => Carbon::now()
            ];
            $projectId = Project::insertGetId($data);
            if (($projectId) && ($request['project_manager'] != 0) && ($request['project_manager'] != Auth::user()->id)) {
                //for sending E-mail to Project Manager after project is created
                insertIntoProjectScheduler(
                    $projectId,
                    'project.add',
                    $request['project_manager'],
                    'New Project',
                    $cc = null,
                    $taskId = null
                );

                return $projectId;
            } else {
                return $projectId;
            }
        }
    }

    /**
    * Function for getting all user project
    */
    public function getUserProjects($userId)
    {
        return Project::leftjoin('project_members', 'projects.id', 'project_members.project_id')
                      ->select(
                          'projects.id',
                          'projects.project_name as name',
                          'projects.description',
                          'label',
                          'client_company'
                      )->where(function ($query) use ($userId) {
                            $query->where('project_manager', $userId)
                                  ->orWhere('created_by', $userId)
                                  ->orWhere('project_members.members', 'like', '%"' . $userId . '"%');
                      })->with('getProjectLabel')
                      ->where('projects.is_deleted', 0)
                      ->where('projects.is_completed', 0)
                      ->get()->toArray();
    }

    /**
    * Function for getting all comlpeted projects for a user
    */
    public function getCompletedProjects($userId)
    {
        return Project::leftjoin('project_members', 'projects.id', 'project_members.project_id')
                      ->select(
                          'projects.id',
                          'projects.project_name as name',
                          'projects.description',
                          'label',
                          'client_company'
                      )->where(function ($query) use ($userId) {
                            $query->where('project_manager', $userId)
                                  ->orWhere('created_by', $userId)
                                  ->orWhere('project_members.members', 'like', '%"' . $userId . '"%');
                      })->with('getProjectLabel')
                      ->where('projects.is_deleted', 0)
                      ->where('projects.is_completed', 1)
                      ->get()->toArray();
    }

    /**
    * Function to view project
    */
    public function viewProject($projectId)
    {
        return Project::select(
            'id',
            'project_manager',
            'project_name',
            'description',
            'label',
            'category',
            'client_company'
        )->where('id', $projectId)->where('is_deleted', 0)
        ->with('getProjectLabel')
        ->with('getProjectCategory')->first();
    }

    /**
     * Function for getting details of project to edit
     */
    public function editProject($projectId)
    {
        return Project::select(
            'project_manager',
            'project_name',
            'description',
            'label',
            'category',
            'client_company',
            'additional_info'
        )->where('id', $projectId)->where('is_deleted', 0)
         ->first();
    }



    /**
    * Function to get project members
    */
    public function getProjectMembers($projectId)
    {
        $data = $members = $member = $datas = [];
        $members = ProjectMembers::select('members')->where('project_id', $projectId)->get();
        foreach ($members as $key => $val) {
            $member[] = json_decode($val['members']);
        }
        foreach ($member as $key => $values) {
            foreach ($values as $key => $value) {
                $datas [] = $value;
            }
        }
        $memmbersIds = array_values(array_unique($datas));
        $data['member_id'] = json_encode($memmbersIds);
        $data['members'] = json_encode($memmbersIds);
        if ($memmbersIds != 0) {
            $data['members'] = UserInfo::select(
                'user_id',
                DB::raw("CONCAT(if(first_name is null ,'',first_name),' ',if(middle_name is null ,'',middle_name), ' ', if(last_name is null ,'',last_name)) AS name")
            )->whereIn('user_id', $memmbersIds)->get();
        }
        return $data;
    }

    /**
    * Function to add project members
    */
    public function addProjectMembers($request)
    {
        $members = ProjectMembers::where('project_id', $request->project_id)->value('members');
        if ($members) {
            $data = [
                'project_id' => $request->project_id,
                'members' => (!empty($request->projectMembers)) ? json_encode($request->projectMembers) : 0,
                'updated_at' => Carbon::now()
            ];
            $existingMembers = json_decode($members);
            $toMails = array_diff($request->projectMembers, $existingMembers);
            $members = ProjectMembers::where('project_id', $request->project_id)->update($data);
        } else {
            $data = [
                'project_id' => $request->project_id,
                'members' =>  (!empty($request->projectMembers)) ? json_encode($request->projectMembers) : 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
            $toMails = (!is_null($request->projectMembers)) ? ($request->projectMembers) : [];
            if ($data['members']) {
                $members = ProjectMembers::insert($data);
            }
        }
        $toMails = array_diff($toMails, array(Auth::user()->id));
        if (count($toMails)) {
            $toMails = json_encode(array_values(array_unique($toMails)));
            insertIntoProjectScheduler(
                $request->project_id,
                'project.members',
                $toMails,
                'Project Members',
                $cc = null,
                $taskId = null
            );
        } else {
            return true;
        }
    }

    /**
    * Function to add project task list
    */
    public function addProjectTaskList($request)
    {
        $data = [
            'project_id' => $request->project_id,
            'task_group' => $request->taskList,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];

        return TaskGroup::insertGetId($data);
    }

    /**
    * Function to edit a project task list
    */
    public function editProjectTaskList($request)
    {
        $data = [
            'task_group' => $request->taskList,
            'updated_at' => Carbon::now()
        ];

        return TaskGroup::where('id', $request['task_group_id'])->update($data);
    }

    /**
    * Function to get project task list
    */
    public function getTaskLists($projectId)
    {
        return TaskGroup::select('id', 'task_group')->where('project_id', $projectId)->where('is_deleted', 0)->with('getSubTask')->get();
    }

    /**
    * Function to add project sub task
    */
    public function addSubTask($request)
    {
        $data = [
            'task_group_id' => $request->task_group_id,
            'task_name' => $request->task_name,
            'description' => $request->description,
            'due_date' => ($request->due_date ? date("Y-m-d", strtotime($request->due_date)) : null),
            'label' => $request->task_label,
            'assignee' => $request->task_assignee,
            'subscribers' => (!empty($request->projectSubscribers)) ? $request->projectSubscribers : 0,
            'project_file' => (!empty($request['fileupload_filename'])) ? json_encode($request['fileupload_filename']): null,
            'priority' => isset($request->priority) ? 1 : 0,
            'estimate' => $request->task_estimate,
            'created_by' => Auth::user()->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];
        $data['subscribers'] = json_encode($data['subscribers']);
        $subtask = ProjectTask::insertGetId($data);
        $projectId = TaskGroup::where('id', $request->task_group_id)->select('project_id')->first();
        $projectMngr = Project::select('project_manager')->where('id', $projectId->project_id)->where('is_deleted', 0)->where('is_active', 1)->first();
        $ccForMail = ($data['subscribers'] == 0) ? [] : json_decode($data['subscribers']);
        unset($ccForMail[array_search(Auth::user()->id, $ccForMail)]);
        if (($data['assignee'] != Auth::user()->id) || ($projectMngr['project_manager'] != Auth::user()->id) || (count($ccForMail) > 0 )) {
            if ($projectMngr['project_manager'] != Auth::user()->id) {
                array_push($ccForMail, json_encode($projectMngr['project_manager']));
            }
            $ccForMail = json_encode(array_values(array_unique($ccForMail)));
            insertIntoProjectScheduler(
                $projectId->project_id,
                'project.task',
                $data['assignee'],
                $request->task_name,
                $ccForMail,
                $subtask
            );
        }

        return $subtask;
    }

    /**
    * Function to update sub task label
    */
    public function updateSubTaskLabel($id, $subtaskId)
    {
        $data = [
           'label' => $id
        ];

        return ProjectTask::where('id', $subtaskId)->update($data);
    }

    /**
    * Function to update sub task label
    */
    public function updateSubTaskAssignee($id, $subtaskId)
    {
        $data = [
            'assignee' => $id
        ];

        return ProjectTask::where('id', $subtaskId)->update($data);
    }

    /**
    * Function for getting the sub task
    */
    public function getTaskComment($id)
    {
        return TaskComment::join('user_info', 'user_info.user_id', 'task_comments.commented_by')
                            ->select(
                                DB::raw('CONCAT_WS(" ",user_info.first_name, user_info.middle_name, user_info.last_name) AS employee'),
                                'task_comments.id',
                                'task_comments.description',
                                'task_comments.commented_by',
                                'task_comments.task_id',
                                'task_comments.created_at'
                            )->where('task_comments.task_id', $id)
                            ->with('getTaskFiles')->orderBy('task_comments.id', 'DESC')->get();
    }

    /**
    * Function for getting the sub task
    */
    public function viewSubTask($id)
    {
        $datas = ProjectTask::where('id', $id)->with('getTaskAssignee')->with('getTaskLabel')
                                ->with('getTaskGroup')->first();
        if (!is_null($datas['created_by'])) {
            $createdBy = UserInfo::select(DB::raw('CONCAT_WS(" ",first_name, middle_name, last_name) AS name'))
                                ->where('user_id', $datas['created_by'])
                                ->where('is_deleted', 0)->first()->toArray();
            $createdBy = $createdBy['name'];
        } else {
            $createdBy = null;
        }
        $comments = TaskComment::join('user_info', 'user_info.user_id', 'task_comments.commented_by')
                                    ->select(
                                        DB::raw('CONCAT_WS(" ",user_info.first_name, user_info.middle_name, user_info.last_name) AS employee'),
                                        'task_comments.id',
                                        'task_comments.description',
                                        'task_comments.commented_by',
                                        'task_comments.task_id',
                                        'task_comments.updated_at'
                                    )->where('task_comments.task_id', $id)
                                    ->with('getTaskFiles')->orderBy('task_comments.id', 'DESC')->get();
        $subTask = [
            'id' => $datas['id'],
            'task_group_id' => $datas['task_group_id'],
            'description' => ($datas['description']),
            'estimate' => ($datas['estimate']),
            'priority' => ($datas['priority']),
            'task_group_name' => $datas->getTaskGroup->name,
            'task_name' => $datas['task_name'],
            'due_date' => $datas['due_date'],
            'label_id' => $datas['label'],
            'label' => !empty($datas->getTaskLabel->name) ? $datas->getTaskLabel->name : '',
            'assignee_id' => $datas['assignee'],
            'project_file' => $datas['project_file'],
            'assignee' => !empty($datas->getTaskAssignee->name) ? $datas->getTaskAssignee->name : '',
            'subscribers' => $datas['subscribers'],
            'subscribers_ids'=> json_decode($datas['subscribers']),
            'created_by' => $createdBy,
            'created_at' => date_format($datas['created_at'], "D, M d, Y")
        ];
        $subTask['dispaly_date'] = !is_null($datas['due_date']) ?
        date('j-M-Y', strtotime($subTask['due_date'])) : '';
        $subTask['subscribers'] = json_decode($subTask['subscribers']);
        if ($subTask['subscribers'] != 0) {
            $subTask['subscribers'] = UserInfo::select(
                'user_id',
                DB::raw("CONCAT(if(first_name is null ,'',first_name),' ',if(middle_name is null ,'',middle_name), ' ', if(last_name is null ,'',last_name)) AS name")
            )->whereIn('user_id', $subTask['subscribers'])->get();
        }
        if (!($comments->isEmpty())) {
            $subTask['comments'] = $comments;
        } else {
            $subTask['comments'] = null;
        }

        return $subTask;
    }

    /**
    * Function to update sub task due date
    */
    public function updateSubTaskDate($date, $subtaskId)
    {
        $data = [
            'due_date' => ($date ? date("Y-m-d", strtotime($date)) : null)
        ];

        return ProjectTask::where('id', $subtaskId)->update($data);
    }

    /**
    * Function to add project sub task comments
    */
    public function addTaskcomment($request)
    {
        $taskGroup = getTaskGroup($request['sub_task_id']);
        if (!empty($request)) {
            $data = [
                   'commented_by' => Auth::user()->id,
                   'description' => $request['comment'],
                   'task_id' => $request['sub_task_id'],
                   'task_group_id' => $taskGroup->task_group_id,
                   'created_at' => Carbon::now(),
                   'updated_at' => Carbon::now()
            ];
            $comment = TaskComment::insertGetId($data);
            if (isset($request['comment_file'])) {
                $file = [
                        'file' => json_encode($request['comment_file']),
                        'task_id' => $request['sub_task_id'],
                        'task_group_id' => $taskGroup->task_group_id,
                        'comment_id' => $comment,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                ];
                $fileUpdate = TaskFiles::insert($file);
            }
            if ($comment) {
                $projectId = TaskGroup::where('id', $taskGroup->task_group_id)->select('project_id')->first();
                $subscribers = ProjectTask::where('id', $request['sub_task_id'])->select('assignee', 'subscribers')->first();
                $recieverArray1 = (json_decode($subscribers['subscribers']) != 0) ? json_decode($subscribers['subscribers']): [];
                $recieverArray2[] = $subscribers['assignee'];
                $recieverArray = array_merge($recieverArray1, $recieverArray2);
                $uniqueRecieverId = array_unique(array_filter($recieverArray));
                unset($uniqueRecieverId[array_search(Auth::user()->id, $uniqueRecieverId)]);
                $projectMngr = Project::select('project_manager')->where('id', $projectId->project_id)->where('is_deleted', 0)->where('is_active', 1)->first();
                if (($projectMngr['project_manager'] != null) && ($projectMngr['project_manager'] != Auth::user()->id)) {
                    array_push($uniqueRecieverId, json_encode($projectMngr['project_manager']));
                }
                $uniqueRecieverIds = array_unique(array_values($uniqueRecieverId));
                foreach ($uniqueRecieverIds as $key => $uniqueRecieverId) {
                    if ($uniqueRecieverId == 'null') {
                        unset($uniqueRecieverIds[$key]);
                    }
                }
                $uniqueRecieverId = json_encode(array_values($uniqueRecieverIds));
                return  insertIntoProjectScheduler(
                    $projectId->project_id,
                    'project.comment',
                    $uniqueRecieverId,
                    $comment,
                    $cc = null,
                    $taskId = null
                );
            } else {
                return true;
            }
        }
    }

    /**
    * Function to update subscribers in each task
    */
    public function updateSubscriberTask($taskId, $subscribers)
    {
        if (isset($subscribers['task-subscribers'])) {
            $data = ['subscribers' => $subscribers['task-subscribers']];
            $data['subscribers'] = json_encode($data['subscribers']);
        } else {
            $data = ['subscribers' => 0];
        }

        return ProjectTask::where('id', $taskId)->update($data);
    }

    /**
    * Function to update project
    */
    public function updateProject($request)
    {
        if (!empty($request)) {
            $data = [
                   'project_manager' => $request['project_manager'] != 0 ? $request['project_manager'] : null,
                   'project_name' => $request['project_name'],
                   'description' => $request['description'],
                   'label' => $request['project_label'] != 0 ? $request['project_label'] : null,
                   'category' => $request['project_category'] != 0 ? $request['project_category'] : null,
                   'client_company' => $request['client_company'],
                   'additional_info' => $request['additional_info'],
                   'is_active' => 1,
                   'updated_at' => Carbon::now()
            ];

            return Project::where('id', $request['project_id'])->update($data);
        }
    }

    /**
    * Function for get all project labels
    */
    public function viewProjectTime($projectId, $subTaskId)
    {
        $data = TimeTracking::where('is_deleted', 0)
                           ->where('project_id', $projectId)
                           ->with('getEmployeeName')
                           ->orderBy('created_at', 'DESC');
        if ($subTaskId) {
            $data = $data->select(
                'time',
                'id',
                'assignee_id',
                'subtask',
                DB::raw('DATE_FORMAT(date, "%d.%M.%Y") as date')
            )->where('subtask', $subTaskId)
                       ->with('getTaskName');
        } else {
            $data = $data->select(
                'time',
                'id',
                'assignee_id',
                'subtask',
                DB::raw('DATE_FORMAT(date, "%d.%M.%Y") as date')
            )->with('getTaskName');
        }

            return $data->get()->toArray();
    }

    /**
    * Function to add project access
    */
    public function addProjectAccess($request)
    {
        $data = [
            'project_id' => $request['project_id'],
            'task_group' => $request['task_group'],
            'git_url' => is_null($request['git_url']) ? '' : $request['git_url'],
            'server' => is_null($request['server']) ? '' : $request['server'],
            'backend' => is_null($request['backend']) ? '' : $request['backend'],
            'database' => is_null($request['database']) ? '' : $request['database'],
            'domains' => is_null($request['domains']) ? '' : $request['domains'],
            'additional_info' => is_null($request['additional_info']) ? '' : $request['additional_info'],
            'is_active' => 1,
            'is_deleted' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];

        return ProjectAccess::insert($data);
    }

    /**
    * Function to get project access
    */
    function getProjectAccess($id)
    {
        return ProjectAccess::where('project_id', $id)->where('is_deleted', 0)->get()->toArray();
    }

    /**
    * Function for updating a project access
    */
    public function updateProjectAccess($request)
    {
        $data = [
            'task_group' => $request['task_group'],
            'git_url' => is_null($request['git_url']) ? '' : $request['git_url'],
            'server' => is_null($request['server']) ? '' : $request['server'],
            'backend' => is_null($request['backend']) ? '' : $request['backend'],
            'database' => is_null($request['database']) ? '' : $request['database'],
            'domains' => is_null($request['domains']) ? '' : $request['domains'],
            'additional_info' => is_null($request['additional_info']) ? '' : $request['additional_info'],
            'updated_at' => Carbon::now()
        ];

        return ProjectAccess::where('id', $request['id'])->update($data);
    }

    /**
    * Function for delete project access
    */
    public function deleteProjectAccess($id)
    {
            return ProjectAccess::where('id', $id)->update([
                    'is_deleted' => 1,
                    'updated_at' => Carbon::now()
            ]);
    }

    /**
    * Function for update subtask
    */
    public function updateSubTask($input)
    {
        if ($input) {
            $data = [
                    'task_name' => $input['task_name'],
                    'description' =>$input['task-description'],
                    'estimate' =>$input['task_estimate'],
                    'priority' => isset($input['priority']) ? 1 : 0,
                    'updated_at' => Carbon::now()
            ];
            if (isset($input['project_file'])) {
                $data['project_file'] = $input['project_files'];
            }
                return ProjectTask::where('id', $input['id'])->update($data);
        }
    }

    /**
    * Function for getting the sub task
    */
    public function editSubTask($id)
    {
        return ProjectTask::select(
            'id',
            'task_group_id',
            'task_name',
            'description',
            'project_file',
            'priority',
            'estimate'
        )->where('id', $id)->where('is_deleted', 0)
        ->where('is_active', 1)->first();
    }

    /**
    * Function for completing a sub task
    */
    public function markSubTaskAsComplete($id)
    {
        return ProjectTask::where('id', $id)->update(['is_completed' => 1]);
    }

    /**
    * Function for reverting a sub task to incomplete list
    */
    public function markSubTaskAsIncomplete($id)
    {
        return ProjectTask::where('id', $id)->update(['is_completed' => 0]);
    }

    /**
    * Function for deleting a Task Group
    */
    public function deleteTaskGroup($id)
    {
        return TaskGroup::where('id', $id)->update(['is_deleted' => 1]);
    }

    /**
    * Function for completing a Project as complete
    */
    public function markProjectAsComplete($id)
    {
        return Project::where('id', $id)->update(['is_completed' => 1]);
    }

    /**
    * Function for marking a Project as incomplete
    */
    public function markProjectAsInomplete($id)
    {
        return Project::where('id', $id)->update(['is_completed' => 0]);
    }

    /**
    * Function for deleting a Project
    */
    public function deleteProject($id)
    {
        return Project::where('id', $id)->update(['is_deleted' => 1]);
    }

    /**
    * Function for reverting a sub task to incomplete list
    */
    public function markTaskGroupAsComplete($id)
    {
        $count = ProjectTask::where('task_group_id', $id)->where('is_completed', 0)->count();
        ProjectTask::where('task_group_id', $id)->update(['is_completed' => 1]);

        return $count;
    }

    /**
    * Function for add project file
    */
    public function addProjectFile($request)
    {
        $attachments = $request['attachments_file'];
        foreach ($attachments as $key => $value) {
            $data = ProjectFiles::insert([
                'project_id' => $request['project_id'],
                'attachments' => $value,
                'created_by' => Auth::user()->id,
                'created_at' => Carbon::now(),
              'updated_at' => Carbon::now()
            ]);
        }
    }

    /**
    * Function to get project files
    */
    function getProjectFiles($id)
    {
        $info = ProjectFiles::where('project_id', $id)->where('is_deleted', 0)->get();
        $fileInfo = [];
        foreach ($info as $key => $value) {
            $createdBy = UserInfo::select(DB::raw('CONCAT_WS(" ",first_name, middle_name, last_name) AS name'))
                                    ->where('user_id', $value['created_by'])
                                    ->where('is_deleted', 0)->first()->toArray();
            $fileInfo[$key] = [
            'id' => $value['id'],
            'project_id' => $value['project_id'],
            'attachments' => $value['attachments'],
            'created_by' => $createdBy['name'],
            'created_at' => date_format($value['created_at'], 'M d, Y')
            ];
        }

        return $fileInfo;
    }

    /**
    * Function for delete lead
    */
    public function deleteProjectFile($id)
    {
        return ProjectFiles::where('id', $id)->update([
            'is_deleted' => 1,
            'updated_at' => Carbon::now()
        ]);
    }

    /**
    * Function for bug priority
    */
    public function getBugPriority()
    {
        return BugPriority::select('id', 'bug_priority', 'is_active')->where('is_deleted', 0)->orderBy('bug_priority')->get()->toArray();
    }

    /**
    * Function for bug priority
    */
    public function getBugStatus()
    {
        return BugStatus::select('id', 'bug_status', 'is_active')->where('is_deleted', 0)->orderBy('bug_status')->get()->toArray();
    }

    /**
    * Function for add project label
    */
    public function addBugPriority($input)
    {
        if ($input) {
            $data = [
                'bug_priority' => $input['bug_priority'],
                'is_active' => $input['is_active'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];

            return BugPriority::insert($data);
        }
    }

    /**
    * Function for add project label
    */
    public function addBugStatus($input)
    {
        if ($input) {
            $data = [
                'bug_status' => $input['bug_status'],
                'is_active' => $input['is_active'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];

            return BugStatus::insert($data);
        }
    }

    /**
    * Function for edit  project label
    */
    public function editBugPriority($id)
    {
        return BugPriority::where('id', $id)->first();
    }

    /**
    * Function for update project label
    */
    public function updateBugPriority($input)
    {
        return BugPriority::where('id', $input['id'])->update([
            'bug_priority' => $input['bug_priority'],
            'is_active' => $input['is_active'],
            'updated_at' => Carbon::now()
        ]);
    }

    /**
    * Function for edit  project label
    */
    public function editBugStatus($id)
    {
        return BugStatus::where('id', $id)->first();
    }

    /**
    * Function for update project label
    */
    public function updateBugStatus($input)
    {
        return BugStatus::where('id', $input['id'])->update([
            'bug_status' => $input['bug_status'],
            'is_active' => $input['is_active'],
            'updated_at' => Carbon::now()
        ]);
    }

    /**
    * Function for add bugs
    */
    public function addBug($input)
    {
        if ($input) {
            $bugs = Bugs::where('task_id', $input['Bugtask_id'])->count();
            $bugs = $bugs + 1;
            $bugCount = sprintf("%02d", $bugs);
            $projectId = sprintf("%02d", $input['project_id']);
            $bugId = 'BUG-'.$projectId.$input['Bugtask_id'].$bugCount;
            $data = [
                'project_id' => $input['project_id'],
                'task_id' => $input['Bugtask_id'],
                'bug_id' => $bugId,
                'reporter_id' => Auth::user()->id,
                'assignee_id' => $input['assignee'],
                'priority_id' => $input['priority'],
                'status_id' => $input['status'],
                'summary' => $input['summary'],
                'description' => $input['bug_description'],
                'steps_to_reproduce' => $input['steps_to_reproduce'],
                'attachments' => (!empty($input['fileupload_filename'])) ? json_encode($input['fileupload_filename']): null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];

            return Bugs::insert($data);
        }
    }

    /**
    * Function for get all project labels
    */
    public function getBugs($id)
    {
        return Bugs::select('id', 'assignee_id', 'task_id', 'priority_id', 'status_id', 'reporter_id', 'assignee_id', 'summary')
                    ->where('is_deleted', 0)
                    ->where('project_id', $id)
                    ->with('getReporterName')
                    ->with('getAssigneeName')
                    ->with('getBugPriority')
                    ->with('getBugTask')
                    ->with('getBugStatus')->get()->toArray();
    }

    /**
    * Function for get all project labels
    */
    public function editBugs($id)
    {
        $data = Bugs::select('id', 'assignee_id', 'task_id', 'priority_id', 'status_id', 'reporter_id', 'assignee_id', 'summary', 'steps_to_reproduce', 'description', 'attachments', 'created_at')
                    ->where('is_deleted', 0)
                    ->where('id', $id)
                    ->with('getReporterName')
                    ->with('getAssigneeName')
                    ->with('getBugPriority')
                    ->with('getBugcomments')
                    ->with('getBugStatus')->get();
        $data = $data->loadMissing('getBugcomments.getbugCommentedBy');

        return $data;
    }

    /**
    * Function for update bugs
    */
    public function updateBug($input, $id)
    {
        if ($input) {
            $data = [
                'assignee_id' => $input['assignee'],
                'priority_id' => $input['priority'],
                'task_id' => $input['Bugtask_id'],
                'summary'=> $input['summary'],
                'description'=> $input['bug_description'],
                'status_id' => $input['status'],
                'steps_to_reproduce' => $input['steps_to_reproduce'],
                'updated_at' => Carbon::now()
            ];
            $updateBug = Bugs::where('id', $id)->update($data);
            if ($input['note']) {
                $commentData = [
                    'bug_id' => $id,
                    'commented_by' => Auth::user()->id,
                    'description' => $input['note'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
                BugsComments::insert($commentData);
            }

            return $updateBug;
        }
    }

    /**
    * Function for delete bug status
    */
    public function deleteBugStatus($id)
    {
        return BugStatus::where('id', $id)->update([
            'is_deleted' => 1,
            'updated_at' => Carbon::now()
        ]);
    }

    /**
    * Function for delete bug priority
    */
    public function deleteBugPriority($id)
    {
        return BugPriority::where('id', $id)->update([
            'is_deleted' => 1,
            'updated_at' => Carbon::now()
        ]);
    }

    /**
    * Function for getting all completed tasks of a project
    */
    public function getCompletedtasks($projectId)
    {
        return ProjectTask::select('id', 'task_name', 'task_group_id')
                                ->where('is_completed', 1)
                                ->with('taskGroup')
                                ->whereHas('taskGroup', function ($query) use ($projectId) {
                                    $query->where('project_id', $projectId)
                                            ->where('task_group.is_deleted', 0);
                                })->get()->toArray();
    }

    /**
    * Function for filtering bugs
    */
    public function filterBugs($projectId, $assigneeId, $reporterId, $taskId, $statusId, $priorityId)
    {
        $bugs = Bugs::select('id', 'assignee_id', 'task_id', 'priority_id', 'status_id', 'reporter_id', 'bug_id', 'assignee_id', 'summary')
                    ->where('is_deleted', 0)->orderBy('created_at', 'DESC')
                    ->where('project_id', $projectId)
                    ->with('getReporterName')
                    ->with('getAssigneeName')
                    ->with('getBugPriority')
                    ->with('getBugTask')
                    ->with('getBugStatus');
        if ($assigneeId != 0) {
            $bugs = $bugs->where('assignee_id', $assigneeId);
        }
        if ($reporterId != 0) {
            $bugs = $bugs->where('reporter_id', $reporterId);
        }
        if ($taskId != 0) {
            $bugs = $bugs->where('task_id', $taskId);
        }
        if ($statusId != 0) {
            $bugs = $bugs->where('status_id', $statusId);
        }
        if ($priorityId != 0) {
            $bugs = $bugs->where('priority_id', $priorityId);
        }
                $bugs = $bugs->get()->toArray();

        return $bugs;
    }

    /**
    * Function for making a project favourite
    */
    public function makeProjectFavourite($projectId, $isFavourite)
    {
        return Project::where('id', $projectId)->update(['is_favourite' => $isFavourite]);
    }
}
