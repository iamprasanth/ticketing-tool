<?php

namespace TicketingTool\Console\Commands;

use Illuminate\Console\Command;
use TicketingTool\Models\ProjectMailScheduler;
use TicketingTool\Models\Project;
use TicketingTool\Models\UserInfo;
use TicketingTool\Models\User;
use TicketingTool\Models\TaskComment;
use TicketingTool\Models\ProjectTask;
use TicketingTool\Models\TicketMailScheduler;
use TicketingTool\Models\Ticket;
use TicketingTool\Models\TicketComment;
use TicketingTool\Models\TicketStatus;
use Mail;
use DB;

class ProjectScheduler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:project_scheduler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for running project mails every minute';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Project Mail Scheduler
        $projectMail = ProjectMailScheduler::where('status', 0)->get()->toArray();
        if (!empty($projectMail)) {
            foreach ($projectMail as $value) {
                $projects = $this->getAllProjectMails($value);
            }
        }

        // Ticket Mail Scheduler
        $ticketMail = TicketMailScheduler::where('status', 0)->with('userInfo')->get()->toArray();
        if (!empty($ticketMail)) {
            foreach ($ticketMail as $value) {
                $tickets = $this->sendTicketMails($value);
            }
        }
    }

    public function getAllProjectMails($mailContent)
    {
        $projectDetails = Project::where('id', $mailContent['project_id'])
            ->select('project_name', 'created_by', 'project_manager')
            ->with('getProjectCreator')
            ->with('getProjectCreatorEmail')
            ->with('getProjectManager')
            ->with('getProjectManagerEmail')->first();
        $createdBy =    $projectDetails->getProjectCreator['name'];
        $prManagerMail = $projectDetails->getProjectManagerEmail->email;
        if ($mailContent['mail_type'] == 'project.add') {
            $projectManager =   $projectDetails->getProjectManager['name'];
            $subject = '[' . Config('constants.FAKTENHAUS') . ']-Created New Project ' . $projectDetails->project_name;
            $credentials = [
                'created_by' => $createdBy,
                'project_name' => $projectDetails->project_name,
                'project_manager' => $projectManager,
            ];
            $mailSender = $mailContent['sender_id'];
            $recepient = $prManagerMail;
            $sendMail = Mail::send(
                ['html' => 'Project.Mails.addProject'],
                $credentials,
                function ($message) use ($subject, $mailSender, $recepient) {
                    $message->to($recepient)
                        ->from($mailSender, Config('constants.FAKTENHAUS'))
                        ->subject($subject);
                }
            );
            return ProjectMailScheduler::where('id', $mailContent['id'])->where('status', 0)->update(['status' => 1]);
        } elseif ($mailContent['mail_type'] == 'project.members') {
            $receiver_id = json_decode($mailContent['receiver_id']);
            foreach ($receiver_id as $key => $value) {
                $receiverData = UserInfo::where('user_id', $value)->select('first_name', 'middle_name', 'last_name')->first();
                $receiverEmail = User::where('id', $value)->select('email')->first();
                $name = $receiverData->first_name . " " . $receiverData->middle_name . " " . $receiverData->last_name;
                $subject = '[' . Config('constants.FAKTENHAUS') . ']-Project Members : ' . $projectDetails->project_name;
                $credentials = [
                    'created_by' => $createdBy,
                    'project_name' => $projectDetails->project_name,
                    'reciever_name' =>  $name,
                ];
                $mailSender = $mailContent['sender_id'];
                $recepient = $receiverEmail->email;
                $sendMail = Mail::send(
                    ['html' => 'Project.Mails.projectMembers'],
                    $credentials,
                    function ($message) use ($subject, $mailSender, $recepient) {
                        $message->to($recepient)
                            ->from($mailSender, Config('constants.FAKTENHAUS'))
                            ->subject($subject);
                    }
                );
            }
            $itemSend = ProjectMailScheduler::where('id', $mailContent['id'])->where('status', 0)->update(['status' => 1]);
        } elseif ($mailContent['mail_type'] == 'project.task') {
            $recieverArray1 = json_decode($mailContent['cc_id']);
            $recieverArray2[] = $mailContent['receiver_id'];
            if ($recieverArray1) {
                $recieverArray = array_merge($recieverArray1, $recieverArray2);
            } else {
                $recieverArray = $recieverArray2;
            }
            $uniqueRecieverId = array_unique($recieverArray);
            foreach ($uniqueRecieverId as $key => $value) {
                $receiverEmail = User::where('id', $value)->select('email')->first();
                $assignee = UserInfo::where('user_id', $mailContent['receiver_id'])
                    ->select('first_name', 'middle_name', 'last_name')->first();
                $assigneeName = $assignee->first_name . " " . $assignee->middle_name . " " . $assignee->last_name;
                $subject = '[' . Config('constants.FAKTENHAUS') . ']-Task : ' . $mailContent['description'];
                $receiverNameData = UserInfo::where('user_id', $mailContent['creator_id'])
                    ->select('first_name', 'middle_name', 'last_name')->first();
                $receiverName = $receiverNameData->first_name . " " . $receiverNameData->middle_name . " " . $receiverNameData->last_name;
                $credentials = [
                    'created_by' => $receiverName,
                    'project_name' => $projectDetails->project_name,
                    'project_id' => $mailContent['project_id'],
                    'task_id' => $mailContent['task_id'],
                    'task_name' => $mailContent['description'],
                    'assigneeName' => $assigneeName
                ];
                $mailSender = $mailContent['sender_id'];
                $recepient = $receiverEmail->email;
                $sendMail = Mail::send(
                    ['html' => 'Project.Mails.task'],
                    $credentials,
                    function ($message) use ($subject, $mailSender, $recepient) {
                        $message->to($recepient)
                            ->from($mailSender, Config('constants.FAKTENHAUS'))
                            ->subject($subject);
                    }
                );
            }
            $itemSend = ProjectMailScheduler::where('id', $mailContent['id'])->where('status', 0)->update(['status' => 1]);
        } elseif ($mailContent['mail_type'] == 'project.comment') {
            $mailId = json_decode($mailContent['receiver_id']);
            $commentData = TaskComment::where('id', $mailContent['description'])
                ->select('commented_by', 'task_id', 'description')->with('getCommentedBy')->first();
            $commentedBy =  $commentData['getCommentedBy']->first_name . ' ' .
                $commentData['getCommentedBy']->middle_name . ' ' .
                $commentData['getCommentedBy']->last_name;
            $comment = $commentData->description;
            $taskName = ProjectTask::where('id', $commentData->task_id)->select('task_name')->first();
            foreach ($mailId as $key => $value) {
                $receiverData = User::where('id', $value)->select('email')->first();
                $subject = '[' . Config('constants.FAKTENHAUS') . ']-Comment : ' . $taskName->task_name;
                $credentials = [
                    'created_by' => $commentedBy,
                    'project_name' => $projectDetails->project_name,
                    'task_name' => $taskName->task_name,
                    'comment' => $comment,
                    'task_id' => $commentData->task_id,
                    'project_id' => $mailContent['project_id']
                ];
                $mailSender = $mailContent['sender_id'];
                $recepient = $receiverData->email;
                $sendMail = Mail::send(
                    ['html' => 'Project.Mails.taskComment'],
                    $credentials,
                    function ($message) use ($subject, $mailSender, $recepient) {
                        $message->to($recepient)
                            ->from($mailSender, Config('constants.FAKTENHAUS'))
                            ->subject($subject);
                    }
                );
            }
            $itemSend = ProjectMailScheduler::where('id', $mailContent['id'])->where('status', 0)->update(['status' => 1]);
        }
    }

    public function sendTicketMails($mailContent)
    {
        $ticketDetails = Ticket::where('id', $mailContent['ticket_id'])
            ->select('name', 'email', 'ticket_number', 'status_id')->with('ticketStatus')
            ->first();
        if ($mailContent['mail_type'] == 'ticket.add') {
            $subject = '[' . Config('constants.FAKTENHAUS') . ']-' . trans('ticketingtool.created_ticket');
            $credentials['ticket_number'] = $ticketDetails['ticket_number'];
            if ($mailContent['comment'] == 'to_customer') {
                $mailSender = Config('constants.SENDER_MAIL');
                $recepient = $ticketDetails['email'];
                $credentials['mail_to_admin'] = 0;
                $sendMail = Mail::send(
                    ['html' => 'Ticket.Mails.addTicket'],
                    $credentials,
                    function ($message) use ($subject, $mailSender, $recepient) {
                        $message->to($recepient)
                            ->from($mailSender, Config('constants.FAKTENHAUS'))
                            ->subject($subject);
                    }
                );
            } else {
                $credentials['mail_to_admin'] = 1;
                $mailSender = $mailContent['sender_id'];
                $recepient = Config('constants.ADMIN_MAIL');
                $sendMail = Mail::send(
                    ['html' => 'Ticket.Mails.addTicket'],
                    $credentials,
                    function ($message) use ($subject, $mailSender, $recepient) {
                        $message->to($recepient)
                            ->from($mailSender, Config('constants.FAKTENHAUS'))
                            ->subject($subject);
                    }
                );
            }
            $mailSend = TicketMailScheduler::where('id', $mailContent['id'])->where('status', 0)->update(['status' => 1]);
        } elseif ($mailContent['mail_type'] == 'ticket.comment' || $mailContent['mail_type'] == 'ticket.update_comment') {
            $commentData = TicketComment::where('id', $mailContent['description'])
                ->select('id', 'user_id', 'description')->with('userInfo')->first();
            $comment = $commentData['description'];
            $ticketNumber = $ticketDetails['ticket_number'];
            $subject = ($mailContent['mail_type'] == 'ticket.comment') ? '[' . Config('constants.FAKTENHAUS') . ']-' . trans('ticketingtool.comment') . '' : '[' . Config('constants.FAKTENHAUS') . ']-' . trans('ticketingtool.update_comment');
            if ($mailContent['comment'] == 'to_customer') { // Status change from backend (Mail to Customer)
                $commentedBy = $commentData->userInfo['name'];
                $credentials = [
                    'created_by' => $commentedBy,
                    'comment' => $comment,
                    'ticketNumber' => $ticketNumber
                ];
                $mailSender = $mailContent['sender_id'];
                $recepient = $ticketDetails['email'];
            } else { // Status change from frontend (Mail to Admin)
                $credentials = [
                    'created_by' => $ticketDetails['name'],
                    'comment' => $comment,
                    'ticketNumber' => $ticketNumber
                ];
                $mailSender = $ticketDetails['email'];
                $recepient = Config('constants.ADMIN_MAIL');
            }
            $sendMail = Mail::send(
                ['html' => 'Ticket.Mails.ticketComment'],
                $credentials,
                function ($message) use ($subject, $mailSender, $recepient) {
                    $message->to($recepient)
                        ->from($mailSender, Config('constants.FAKTENHAUS'))
                        ->subject($subject);
                }
            );
            $mailSend = TicketMailScheduler::where('id', $mailContent['id'])->where('status', 0)->update(['status' => 1]);
        } elseif ($mailContent['mail_type'] == 'ticket.status') {
            $oldStatusId = $mailContent['description'];
            $oldStatus = TicketStatus::select('id', 'name')->where('id', $oldStatusId)->get();
            $subject = '[' . Config('constants.FAKTENHAUS') . ']-Ticket ' . $ticketDetails['ticket_number'] . ' ' . trans('ticketingtool.has_updated');
            if ($mailContent['comment'] == 'to_customer') { // Status change from backend
                $credentials = [
                    'updated_by' => $mailContent['user_info']['name'],
                    'receiverName' => $ticketDetails['name'],
                    'oldStatus' => $oldStatus[0]['name'],
                    'newStatus' => $ticketDetails->ticketStatus[0]['name'],
                    'ticketNumber' => $ticketDetails['ticket_number'],
                    'mail_to_admin' => 0
                ];
                $mailSender = $mailContent['sender_id'];
                $recepient = $ticketDetails['email'];
            } else { // Status change from frontend
                $credentials = [
                    'updated_by' => $ticketDetails['name'],
                    'oldStatus' => $oldStatus[0]['name'],
                    'newStatus' => $ticketDetails->ticketStatus[0]['name'],
                    'ticketNumber' => $ticketDetails['ticket_number'],
                    'mail_to_admin' => 1
                ];
                $mailSender = $ticketDetails['email'];
                $recepient = Config('constants.ADMIN_MAIL');
            }
            $sendMail = Mail::send(
                ['html' => 'Ticket.Mails.ticketStatus'],
                $credentials,
                function ($message) use ($subject, $mailSender, $recepient) {
                    $message->to($recepient)
                        ->from($mailSender, Config('constants.FAKTENHAUS'))
                        ->subject($subject);
                }
            );
            $mailSend = TicketMailScheduler::where('id', $mailContent['id'])->where('status', 0)->update(['status' => 1]);
        }
    }
}
