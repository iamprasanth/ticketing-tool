<?php

namespace TicketingTool\Http\Controllers\Backend\Project;

use TicketingTool\Http\Controllers\Controller;
use TicketingTool\Services\Backend\Project\ProjectService;
use Validator;
use Illuminate\Http\Request;
use Response;
use Auth;

class ProjectController extends Controller
{
    /**
    * @param serivice-instance  $projectService
    */
    public function __construct(ProjectService $projectService)
    {
        $this->middleware('auth');
        $this->projectService = $projectService;
    }

    /**
     * Function for list project labels
     */
    public function listProjects()
    {
        $users = $this->projectService->getUsers();
        $projectLabels = $this->projectService->getProjectLabels();
        $projectCategories = $this->projectService->getProjectCategories();
        $userProjects = $this->projectService->getUserProjects(Auth::user()->id);

        return view(
            'Project.list',
            [
                    'users' => $users,
                    'projectLabels' => $projectLabels,
                    'projectCategories' => $projectCategories,
                    'userProjects' => $userProjects
                ]
        );
    }

    /**
     * Function for getting all projects
     */
    public function getProjects()
    {
        $projects =  $this->projectService->getUserProjects(Auth::user()->id);

        return Response::json(['projects' => $projects]);
    }

    /**
     * Function for getting completed projects
     */
    public function getCompletedProjects()
    {
        return $this->projectService->getCompletedProjects(Auth::user()->id);
    }

    /**
     * Function to add projects
     */
    public function addProject(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'project_name' => 'required|max:250',
                'client_company' => 'nullable|max:250',
                'project_manager' => 'required|not_in:0',
            ],
            [
                'project_name.required' => __('ticketingtool.please_fill_this_field'),
                'client_company.max' => __('ticketingtool.character_limit_exceeds'),
                'project_manager.not_in' => __('ticketingtool.choose_a_project_manager'),
            ]
        );
        if ($validator->fails()) {
            return Response::make([
                'message' => trans('ticketingtool.validation_failed'),
                'errors' => $validator->errors()
            ]);
        }
        if ($validator->passes()) {
            $projectId = $this->projectService->addProject($request->all());
            if ($request->has('projectMembers')) {
                $request['project_id'] = $projectId;
                $this->projectService->addProjectMembers($request);
            }

            return Response::json(['success' => $projectId]);
        }

        return Response::json(['errors' => $validator->errors()]);
    }

    /**
     * Function to view project
     */
    public function viewProject($id)
    {
        $project = $this->projectService->viewProject($id);
        $projectMembers = $this->projectService->getProjectMembers($id);
        $taskLists = $this->projectService->getTaskLists($id);
        $users = $this->projectService->getUsers();
        $projectLabels = $this->projectService->getProjectLabels();
        $taskLabels = $this->projectService->getTaskLabels();
        $projectCategories = $this->projectService->getProjectCategories();

        return view(
            'Project.view',
            [
                'users' => $users,
                'projectLabels' => $projectLabels,
                'projectCategories' => $projectCategories,
                'projects' => $project,
                'projectMembers' => $projectMembers,
                'taskLists' => $taskLists,
                'taskLabels' => $taskLabels
            ]
        );
    }

    /**
     * Function for getting details of project to edit
     */
    public function editProject($id)
    {
        return $this->projectService->editProject($id);
    }


    /**
     * Function to add project members
     */
    public function addProjectMembers(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'projectMembers' => 'required',
            ],
            [
                    'projectMembers.required' => __('ticketingtool.please_fill_this_field')
            ]
        );
        if ($validator->fails()) {
            return Response::make([
                'message' => trans('ticketingtool.validation_failed'),
                'errors' => $validator->errors()
            ]);
        }
        if ($validator->passes()) {
            $data = $this->projectService->addProjectMembers($request);
            if ($data) {
                return Response::json(['success' => '1']);
            }
        }
    }

    /**
     * Function to add project task list
     */
    public function addProjectTaskList(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'taskList' => 'required|max:250',
            ],
            [
                'taskList.required' => __('ticketingtool.please_fill_this_field'),
            ]
        );
        if ($validator->fails()) {
            return Response::make([
                'message' => trans('ticketingtool.validation_failed'),
                'errors' => $validator->errors()
            ]);
        }
        if ($validator->passes()) {
            $data = $this->projectService->addProjectTaskList($request);

            return Response::json(['success' => $data]);
        }

        return Response::json(['errors' => $validator->errors()]);
    }

    /**
     * Function to edit project task list
     */
    public function editProjectTaskList(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'taskList' => 'required|max:250',
            ],
            [
                'taskList.required' => __('ticketingtool.please_fill_this_field'),
            ]
        );
        if ($validator->fails()) {
            return Response::make([
                'message' => trans('ticketingtool.validation_failed'),
                'errors' => $validator->errors()
            ]);
        }
        if ($validator->passes()) {
            $data = $this->projectService->editProjectTaskList($request);

            return Response::json(['success' => $data]);
        }

        return Response::json(['errors' => $validator->errors()]);
    }

    /**
     * Function to add project sub task
     */
    public function addSubTask(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'task_name' => 'required|max:80',
                'task_assignee' => 'required|not_in:0'
            ],
            [
                'task_name.max' => __('ticketingtool.character_limit_exceeds'),
                'task_assignee.not_in' => __('ticketingtool.please_fill_this_field'),
                'task_name.required' => __('ticketingtool.please_fill_this_field')
            ]
        );
        if ($validator->fails()) {
            return Response::make([
                'message' => trans('ticketingtool.validation_failed'),
                'errors' => $validator->errors()
            ]);
        }
        if ($validator->passes()) {
            $documents = $request->file('taskFile');
            $fileupload_filename = [];
            if ($documents) {
                foreach ($documents as $keys => $document) {
                    $mimeType = $document->getClientOriginalName();
                    $ext = explode('.', $mimeType);
                    $path =  public_path().'/storage/project-'.$request['task_group_id'];
                    if (!is_dir($path)) {
                        mkdir($path, 0777, true);
                    }
                    $filename = str_slug(pathinfo($document->getClientOriginalName(), PATHINFO_FILENAME)).
                    '-'. time().'.'.$document->getClientOriginalExtension();
                    $document->move($path, $filename);
                    $request['fileupload_filename'] = $filename;
                    $fileupload_filename[$keys]= $filename;
                }
                $request['fileupload_filename']= $fileupload_filename;
            } else {
                $request['fileupload_filename']= '';
            }
            $data = $this->projectService->addSubTask($request);

            return Response::json(['success' => $data]);
        }

        return Response::json(['errors' => $validator->errors()]);
    }

    /**
     * Function to update sub task label
     */
    public function updateSubTaskLabel($id, $subtaskId)
    {
        $data = $this->projectService->updateSubTaskLabel($id, $subtaskId);

        return Response::json(['success' => '1']);
    }

    /**
     * Function to update sub task assignee
     */
    public function updateSubTaskAssignee($id, $subtaskId)
    {
        $data = $this->projectService->updateSubTaskAssignee($id, $subtaskId);

        return Response::json(['success' => '1']);
    }

    /**
     * Function for getting the  sub task
     */
    public function viewSubTask($id)
    {
        return $this->projectService->viewSubTask($id);
    }

    /**
     * Function to update sub task due date
     */
    public function updateSubTaskDate($date, $subtaskId)
    {
        $data = $this->projectService->updateSubTaskDate($date, $subtaskId);

        return Response::json(['success' => '1']);
    }

    /**
     * Function to add project sub task comments
     */
    public function addTaskcomment(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'comment' => 'required|max:4000'
            ],
            [
                'comment.required' => __('ticketingtool.please_fill_this_field'),
                'comment.max' => __('ticketingtool.character_limit_exceeds')
            ]
        );
        if ($validator->fails()) {
            return Response::make([
                'message' => trans('ticketingtool.validation_failed'),
                'errors' => $validator->errors()
            ]);
        }
        if ($validator->passes()) {
            if ($request->file('commentFiles')) {
                $files = $request->file('commentFiles');
                foreach ($files as $key => $value) {
                    $mimeType = $value->getClientOriginalName();
                    $ext = explode('.', $mimeType);
                    $path =  public_path().'/storage/project-'.$request['sub_task_id'];
                    if (!is_dir($path)) {
                          mkdir($path, 0777, true);
                    }
                    $commentFilesname = $ext[0].'.'.$ext[1];
                    $value->move($path, $commentFilesname);
                    $comntFile[$key] = $commentFilesname;
                }
                $request['comment_file'] = $comntFile;
            }
            $comments = preg_replace('/&nbsp;/', '', $request['comment']);
            $stripComments = strip_tags(str_replace('</p><p>', '', $comments));
            $validComment = preg_replace('/\s+/', '', $stripComments);
            if ($validComment == '') {
                return Response::json(['commenterror' => '1']);
            }
            $data = $this->projectService->addTaskcomment($request->all());
            $taskComment = $this->projectService->getTaskComment($request['sub_task_id']);

            return Response::json(['success' => '1', 'comments' => $taskComment]);
        }

        return Response::json(['errors' => $validator->errors()]);
    }

    /**
     * Function to update subscribers in each task
     */
    public function updateSubscriberTask($taskId, Request $request)
    {
        $data = $this->projectService->updateSubscriberTask($taskId, $request->all());
        if ($data) {
            return Response::json(['success' => '1']);
        }
    }

    /**
     * Function to update projects
     */
    public function updateProject(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'project_name' => 'required|max:250',
                'project_manager' => 'required|not_in:0'
            ],
            [
                'project_name.required' => __('ticketingtool.please_fill_this_field'),
                'project_manager.not_in' => __('ticketingtool.choose_a_project_manager'),
            ]
        );
        if ($validator->fails()) {
            return Response::make([
                'message' => trans('ticketingtool.validation_failed'),
                'errors' => $validator->errors()
            ]);
        }
        if ($validator->passes()) {
            $this->projectService->updateProject($request->all());

            return Response::json(['success' => '1']);
        }

        return Response::json(['errors' => $validator->errors()]);
    }

    /**
     * Function to update projects
     */
    public function viewProjectTime($projectId, $subTaskId)
    {
        return $this->projectService->viewProjectTime($projectId, $subTaskId);
    }
    /**
     * Function to get project access
     */
    public function getProjectAccess($id)
    {
        return $this->projectService->getProjectAccess($id);
    }
    /**
     * Function to add project access
     */
    public function addProjectAccess(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'task_group' => 'required',
            ],
            [
                'task_group.required' => __('ticketingtool.please_fill_this_field'),
            ]
        );
        if ($validator->fails()) {
            return Response::make([
                'message' => trans('ticketingtool.validation_failed'),
                'errors' => $validator->errors()
            ]);
        }
        if ($validator->passes()) {
            $data = $this->projectService->addProjectAccess($request);
            if ($data) {
                return Response::json(['success' => '1']);
            }
        }
    }
    /**
     * Function for edit the  sub task
     */
    public function updateProjectAccess(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'task_group' => 'required',
            ],
            [
                'task_group.required' => __('ticketingtool.please_fill_this_field'),
            ]
        );
        if ($validator->fails()) {
            return Response::make([
                'message' => trans('ticketingtool.validation_failed'),
                'errors' => $validator->errors()
            ]);
        }

        if ($this->projectService->updateProjectAccess($request->all())) {
            return Response::json(['success' => '1']);
        }
    }

    /**
     * Function for delete lead
     */
    public function deleteProjectAccess($id)
    {
            return $this->projectService->deleteProjectAccess($id);
    }

    /**
     * Function for delete attachment
     */
    public function removeTaskAttachment(Request $request)
    {
        if (removeTaskAttachment($request['table'], $request['column'], $request['id'])) {
                return Response::json(['success' => 1]);
        }
    }

    /**
     * Function for edit the  sub task
     */
    public function editSubTask($id)
    {
        return $this->projectService->editSubTask($id);
    }

    /**
     * Function for completing a sub task
     */
    public function markSubTaskAsComplete($id)
    {
        return $this->projectService->markSubTaskAsComplete($id);
    }

    /**
     * Function for reverting a sub task to incomplete list
     */
    public function markSubTaskAsIncomplete($id)
    {
        return $this->projectService->markSubTaskAsIncomplete($id);
    }

    /**
     * Function for completing a Task Group
     */
    public function markTaskGroupAsComplete($id)
    {
        return $this->projectService->markTaskGroupAsComplete($id);
    }

    /**
     * Function for deleting a Task Group
     */
    public function deleteTaskGroup($id)
    {
        return $this->projectService->deleteTaskGroup($id);
    }

    /**
     * Function for completing a Project as complete
     */
    public function markProjectAsComplete($id)
    {
        return $this->projectService->markProjectAsComplete($id);
    }

    /**
     * Function for marking a Project as incomplete
     */
    public function markProjectAsInomplete($id)
    {
        return $this->projectService->markProjectAsInomplete($id);
    }

    /**
     * Function for deleting a Project
     */
    public function deleteProject($id)
    {
        return $this->projectService->deleteProject($id);
    }

    public function updateSubTask(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'task_name' => 'required|max:80'
            ],
            [
                'task_name.max' => __('ticketingtool.character_limit_exceeds'),
                'task_name.required' => __('ticketingtool.please_fill_this_field')
            ]
        );
        if ($validator->fails()) {
            return Response::make([
                'message' => trans('ticketingtool.validation_failed'),
                'errors' => $validator->errors()
            ]);
        }
        if ($validator->passes()) {
            if ($request->file('attachment')) {
                                $taskFile = $request->file('attachment');
                                $mimeType = $taskFile->getClientOriginalName();
                                $ext = explode('.', $mimeType);
                                $path =  public_path().'/storage/project-'.$request['task_group_id'];
                if (!is_dir($path)) {
                            mkdir($path, 0777, true);
                }
                                $taskFilename = 'taskgroup-'.time().'-'.$request['task_group_id'].'.'.$ext[1];
                                $taskFile->move($path, $taskFilename);
                                $request['project_files'] = $taskFilename;
            }
            $data = $this->projectService->updateSubTask($request);

            return Response::json(['success' => '1']);
        }
        return Response::json(['errors' => $validator->errors()]);
    }
    public function addProjectFile(Request $request)
    {
            $inputFile = $request->all();
        if (!isset($inputFile['attachments'])) {
            return Response::json(['no_file_error' => '1']);
        }
            $validator = Validator::make(
                $inputFile,
                [
                'attachments.*' => 'required|mimes:jpg,jpeg,png,bmp,pdf,zip,xlsx,svg,doc,docx,txt,xls,mp4,mov,mpeg,avi,mkv,ch'
                ],
                [
                'attachments.*.required' => 'Please upload an image',
                'attachments.*.mimes' => 'Upload a valid file'
                ]
            );
            if ($validator->fails()) {
                return Response::make([
                    'message' => trans('ticketingtool.validation_failed'),
                    'errors' => $validator->errors()
                ]);
            }
            if ($validator->passes()) {
                if ($request->file('attachments')) {
                    $files = $request->file('attachments');
                    foreach ($files as $key => $value) {
                        $mimeType = $value->getClientOriginalName();
                        $ext = explode('.', $mimeType);
                        $path =  public_path().'/storage/projectfile-'.$request['project_id'];
                        if (!is_dir($path)) {
                            mkdir($path, 0777, true);
                        }
                        $filename = explode('.', $mimeType)[0];
                        $projectFilename = $filename.time().'-'.$key.'.'.$ext[1];
                        $value->move($path, $projectFilename);
                        $projectFile[$key] = $projectFilename;
                    }
                    $request['attachments_file'] = $projectFile;
                }
                $this->projectService->addProjectFile($request);

                return Response::json(['success' => '1']);
            }
            return Response::json(['errors' => $validator->errors()]);
    }
    /**
     * Function to get project files
     */
    public function getProjectFiles($id)
    {
        return $this->projectService->getProjectFiles($id);
    }
    /**
     * Function for delete lead
     */
    public function deleteProjectFile($id)
    {
            return $this->projectService->deleteProjectFile($id);
    }

    /**
     * Function for list project labels
     */
    public function listBugsSettings()
    {
        return view('settings.Bugs.list');
    }

    /**
     * Function for list project labels
     */
    public function getBugPriority()
    {
        return $this->projectService->getBugPriority();
    }

    /**
     * Function for list project labels
     */
    public function getBugStatus()
    {
        return $this->projectService->getBugStatus();
    }

    /**
     * Function to add project label
     */
    public function addBugPriority(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'bug_priority' => 'required|max:250|unique:project_labels,name,NULL,id,is_deleted,0',
            ],
            [
                'bug_priority.required' => __('ticketingtool.please_fill_this_field'),
                'bug_priority.unique' => __('ticketingtool.project_label_unqiue'),
                'bug_priority.max' => __('ticketingtool.character_limit_exceeds'),
            ]
        );
        if ($validator->fails()) {
            return Response::make([
                'message' => trans('ticketingtool.validation_failed'),
                'errors' => $validator->errors()
            ]);
        }
        if ($validator->passes()) {
            $this->projectService->addBugPriority($request->all());

            return Response::json(['success' => '1']);
        }

        return Response::json(['errors' => $validator->errors()]);
    }

    /**
     * Function to add project label
     */
    public function addBugStatus(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'bug_status' => 'required|max:250|unique:project_labels,name,NULL,id,is_deleted,0',
            ],
            [
                'bug_status.required' => __('ticketingtool.please_fill_this_field'),
                'bug_status.unique' => __('ticketingtool.project_label_unqiue'),
                'bug_status.max' => __('ticketingtool.character_limit_exceeds'),
            ]
        );
        if ($validator->fails()) {
            return Response::make([
                'message' => trans('ticketingtool.validation_failed'),
                'errors' => $validator->errors()
            ]);
        }
        if ($validator->passes()) {
            $this->projectService->addBugStatus($request->all());

            return Response::json(['success' => '1']);
        }

        return Response::json(['errors' => $validator->errors()]);
    }

    /**
     * Function for edit  project  list
     */
    public function editBugPriority($id)
    {
        return  $this->projectService->editBugPriority($id);
    }

    /**
     * Function for update project list
     */
    public function updateBugPriority(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'bug_priority' => 'required|max:250|unique:project_labels,name,'.$request['id'].',id,is_deleted,0'
            ],
            [
                'bug_priority.required' => __('ticketingtool.please_fill_this_field'),
                'bug_priority.unique' => __('ticketingtool.project_label_unqiue'),
                'bug_priority.max' => __('ticketingtool.character_limit_exceeds'),
            ]
        );
        if ($validator->fails()) {
            return Response::make([
                'message' => trans('ticketingtool.validation_failed'),
                'errors' => $validator->errors()
            ]);
        }
        if ($validator->passes()) {
            $this->projectService->updateBugPriority($request->all());

            return Response::json(['success' => '1']);
        }
        return Response::json(['errors' => $validator->errors()]);
    }

    /**
     * Function for edit  project  list
     */
    public function editBugStatus($id)
    {
        return  $this->projectService->editBugStatus($id);
    }

    /**
     * Function for update project list
     */
    public function updateBugStatus(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'bug_status' => 'required|max:250|unique:project_labels,name,'.$request['id'].',id,is_deleted,0'
            ],
            [
                'bug_status.required' => __('ticketingtool.please_fill_this_field'),
                'bug_status.unique' => __('ticketingtool.project_label_unqiue'),
                'bug_status.max' => __('ticketingtool.character_limit_exceeds'),
            ]
        );
        if ($validator->fails()) {
            return Response::make([
                'message' => trans('ticketingtool.validation_failed'),
                'errors' => $validator->errors()
            ]);
        }
        if ($validator->passes()) {
            $this->projectService->updateBugStatus($request->all());

            return Response::json(['success' => '1']);
        }
        return Response::json(['errors' => $validator->errors()]);
    }

    /**
     * Function to add project bug
     */
    public function addBug(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'Bugtask_id' => 'required|not_in:0',
                'assignee' => 'required|not_in:0',
                'status' => 'required|not_in:0',
                'priority' => 'required|not_in:0',
                'summary' => 'required',
            ],
            [
                'Bugtask_id.required' => __('ticketingtool.please_fill_this_field'),
                'Bugtask_id.not_in' => __('ticketingtool.please_fill_this_field'),
                'summary.required' => __('ticketingtool.please_fill_this_field'),
                'assignee.not_in' => __('ticketingtool.please_fill_this_field'),
                'status.not_in' => __('ticketingtool.please_fill_this_field'),
                'priority.not_in' => __('ticketingtool.please_fill_this_field'),
            ]
        );
        if ($validator->fails()) {
            return Response::make([
                'message' => trans('ticketingtool.validation_failed'),
                'errors' => $validator->errors()
            ]);
        }
        $documents = $request->file('attachments');
        if ($documents) {
            foreach ($documents as $keys => $document) {
                $mimeType = $document->getClientOriginalName();
                $ext = explode('.', $mimeType);
                $path =  public_path().'/storage/bugs/';
                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }
                $filename = str_slug(pathinfo($document->getClientOriginalName(), PATHINFO_FILENAME)).
                '-'. time().'.'.$document->getClientOriginalExtension();
                $document->move($path, $filename);
                $request['fileupload_filename'] = $filename;
                $fileupload_filename[$keys]= $filename;
            }
            $request['fileupload_filename']= $fileupload_filename;
        } else {
            $request['fileupload_filename']= '';
        }
        if ($validator->passes()) {
            $this->projectService->addBug($request->all());

            return Response::json(['success' => '1']);
        }

        return Response::json(['errors' => $validator->errors()]);
    }

    /**
     * Function to list bugs
     */
    public function getBugs($id)
    {
        return $this->projectService->getBugs($id);
    }

    /**
     * Function to list bugs
     */
    public function editBugs($id)
    {
        return $this->projectService->editBugs($id);
    }

    /**
     * Function to update bugs
     */
    public function updateBug(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'Bugtask_id' => 'required|not_in:0',
                'assignee' => 'required|not_in:0',
                'status' => 'required|not_in:0',
                'priority' => 'required|not_in:0',
                'summary' => 'required',
            ],
            [
                'Bugtask_id.required' => __('ticketingtool.please_fill_this_field'),
                'assignee.not_in' => __('ticketingtool.please_fill_this_field'),
                'status.not_in' => __('ticketingtool.please_fill_this_field'),
                'priority.not_in' => __('ticketingtool.please_fill_this_field'),
                'summary.required' => __('ticketingtool.please_fill_this_field'),
            ]
        );
        if ($validator->fails()) {
            return Response::make([
                'message' => trans('ticketingtool.validation_failed'),
                'errors' => $validator->errors()
            ]);
        }
        if ($validator->passes()) {
            $this->projectService->updateBug($request->all(), $id);

            return Response::json(['success' => '1']);
        }

        return Response::json(['errors' => $validator->errors()]);
    }

    /**
     * Function for delete bug status
     */
    public function deleteBugStatus($id)
    {
        return $this->projectService->deleteBugStatus($id);
    }

    /**
     * Function for delete bug priority
     */
    public function deleteBugPriority($id)
    {
        return $this->projectService->deleteBugPriority($id);
    }
    /**
     * Function for getting all completed tasks of a project
     */
    public function getCompletedtasks($projectId)
    {
        return $this->projectService->getCompletedtasks($projectId);
    }

    /**
     * Function for filtering bugs
     */
    public function filterBugs($projectId, $assigneeId, $reporterId, $taskId, $statusId, $priorityId)
    {
        return $this->projectService->filterBugs($projectId, $assigneeId, $reporterId, $taskId, $statusId, $priorityId);
    }

    /**
     * Function for getting total time entered for a subtask
     */
    public function getSubtaskTime($projectId, $subTaskId)
    {
        $projectId = ($subTaskId == 0) ? $projectId : 0;

        return Response::json(['time' => getProjectTotalTime($projectId, $subTaskId)]);
    }

    /**
     * Function for making a project favourite
     */
    public function makeProjectFavourite($projectId, $isFavourite)
    {
        return $this->projectService->makeProjectFavourite($projectId, $isFavourite);
    }
}
