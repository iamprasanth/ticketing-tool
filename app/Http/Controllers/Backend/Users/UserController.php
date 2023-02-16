<?php

namespace TicketingTool\Http\Controllers\Backend\Users;

use TicketingTool\Http\Controllers\Controller;
use Illuminate\Http\Request;
use TicketingTool\Services\Backend\Users\UserServices;
use Illuminate\Validation\Rule;
use Validator;
use Response;
use Hash;
use Illuminate\Support\Facades\Input;
use Auth;

class UserController extends Controller
{
    /**
    * @param serivice-instance      $usesrservice
    */
    public function __construct(UserServices $userService)
    {
        $this->middleware('auth');
        $this->userService = $userService;
    }

    /**
    * Function for list users
    */
    public function getUsersList()
    {
        return view('Users.list');
    }

    /**
    * function for adding new user
    *
    * @param Request instance
    */
    public function addUsers(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'first_name' => 'required|max:250',
                'middle_name' => 'nullable|max:250',
                'last_name' => 'nullable|max:250',
                'email' => 'required|email|unique:users,email,NULL,id,is_deleted,0',
                'role_id' => 'required|not_in:0',
                'telephone' => 'nullable|numeric',
                'emergency_contact' => 'nullable|numeric',
            ],
            [
                'first_name.required' => __('ticketingtool.please_fill_this_field'),
                'first_name.max' => __('ticketingtool.character_limit_exceeds'),
                'middle_name.max' => __('ticketingtool.character_limit_exceeds'),
                'last_name.max' => __('ticketingtool.character_limit_exceeds'),
                'email.required' => __('ticketingtool.please_fill_this_field'),
                'email.email' => __('ticketingtool.enter_valid_email'),
                'email.unique' => __('ticketingtool.unique_email'),
                'emergency_contact.numeric' => __('ticketingtool.enter_valid_contact_no'),
                'telephone.numeric' => __('ticketingtool.enter_valid_contact_no')
            ]
        );
        if ($validator->fails()) {
            return Response::make([
                'message' => 'Validation Failed',
                'errors' => $validator->errors()
            ]);
        }
        if ($validator->passes()) {
            $this->userService->addUsers($request->all());

            return Response::json(['success' => '1']);
        }

        return Response::json(['errors' => $validator->errors()]);
    }

    /**
    * Function for list users
    */
    public function getUsers()
    {
        return $this->userService->getUsers();
    }

    /**
    * Function for view users
    */
    public function viewUsers($id)
    {
        return $this->userService->viewUsers($id);
    }

    /**
    * Function for edit users
    */
    public function editUser($id)
    {
        return $this->userService->editUser($id);
    }

    /**
    * function for update user
    *
    * @param Request instance
    */
    public function updateUser(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'first_name' => 'required|max:250',
                'middle_name' => 'nullable|max:250',
                'last_name' => 'nullable|max:250',
                'email' => 'required|email|unique:users,email,'.$request['id'].',id,is_deleted,0|max:250',
                'role_id' => 'required|not_in:0',
            ],
            [
                'first_name.required' => __('ticketingtool.required_firstname'),
                'first_name.max' => __('ticketingtool.character_limit_exceeds'),
                'middle_name.max' => __('ticketingtool.character_limit_exceeds'),
                'last_name.max' => __('ticketingtool.character_limit_exceeds'),
                'email.required' => __('ticketingtool.required_email'),
                'email.email' => __('ticketingtool.enter_valid_email'),
                'email.unique' => __('ticketingtool.unique_email'),
            ]
        );
        if ($validator->fails()) {
            return Response::make([
                'message' => 'Validation Failed',
                'errors' => $validator->errors()
            ]);
        }
        if ($validator->passes()) {
            $this->userService->updateUser($request->all());

            return Response::json(['success' => '1']);
        }
        return Response::json(['errors' => $validator->errors()]);
    }

    /**
    * Function for delete user
    */
    public function deleteUser($id)
    {
        return $this->userService->deleteUser($id);
    }

    /**
    * Function to get My profile
    */
    public function getMyProfile()
    {
        $id = Auth::user()->id;
        $data = $this->userService->getMyProfile($id);

        return view('MyProfile.view', ['data' => $data]);
    }

    /**
    * Function for changing Password
    */
    public function changePassword(Request $request)
    {
        $isValidPassword = 1;
        if (!(Hash::check($request->get('current_password'), Auth::user()->password))) {
            $isValidPassword = 0;
        }
        $rule = [
            'new_password' => 'required|min:6|same:confirm_password',
            'confirm_password' => 'required'
        ];
        $message = [
            'new_password.required' => __('ticketingtool.please_fill_this_field'),
            'new_password.min' => __('ticketingtool.password_min'),
            'confirm_password.required' => __('ticketingtool.please_fill_this_field'),
            'new_password.same' => __('ticketingtool.password_missmatch')
        ];
        $validator = Validator::make(Input::all(), $rule, $message);
        if ($validator->passes()) {
            if ($this->userService->changePassword($request)) {
                return Response::json(['success' => 1]);
            }
        }

        return Response::json(['errors' => $validator->errors(), 'password_validity' => $isValidPassword]);
    }
}
