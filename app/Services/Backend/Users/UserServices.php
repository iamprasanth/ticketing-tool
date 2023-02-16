<?php

namespace TicketingTool\Services\Backend\Users;

use TicketingTool\Models\User;
use TicketingTool\Models\UserInfo;
use Carbon\Carbon;
use Auth;

class UserServices
{
    /**
    * function for adding new permission
    * @param array incoming data
    *
    */
    public function addUsers($request)
    {
        if (!(empty($request))) {
            $data = [
                'role_id' => $request['role_id'],
                'email' => $request['email'],
                'password' => bcrypt('faktenhaus@123#'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            $insertedUser = User::insertGetId($data);
            $userInfo = [
                'user_id' => $insertedUser,
                'first_name' => $request['first_name'],
                'middle_name' => $request['middle_name'] ? $request['middle_name'] : ' ',
                'last_name' => $request['last_name'] ? $request['last_name'] : ' ',
                'gender' => $request['gender'],
                'date_of_birth' => ($request['dateofbirth'] ?
                                    date('Y-m-d', strtotime($request['dateofbirth'])) : null),
                'address' => $request['address'] ? $request['address'] : ' ',
                'secondary_address' => $request['temporary_address'] ?
                                       $request['temporary_address'] : ' ',
                'contact_no' => $request['telephone'],
                'secondary_contact_no' => $request['emergency_contact'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            UserInfo::insert($userInfo);

            return $insertedUser;
        }
    }

    /**
    * Function for get all users
    */
    public function getUsers()
    {
        return User::select('role_id', 'id')
                ->where('is_deleted', 0)->with('getUserName')->get();
    }

    /**
     * function for view a user
     */
    public function viewUsers($id)
    {
        $data = User::select(
            'role_id',
            'is_active',
            'email',
            'id'
        )->where('id', $id)->with('getUserInfo')->first()->toArray();
        $userData = [];
        $userData['name'] = $data['get_user_info']['first_name'].' '.
                            $data['get_user_info']['middle_name'].' '.
                            $data['get_user_info']['last_name'];
        $userData['address'] = $data['get_user_info']['address'];
        $userData['email'] = $data['email'];
        $userData['secondary_address'] = $data['get_user_info']['secondary_address'];
        $userData['date_of_birth'] = $data['get_user_info']['date_of_birth'];
        $userData['primary_phone'] = $data['get_user_info']['contact_no'];
        $userData['secondary_phone'] = $data['get_user_info']['secondary_contact_no'];
        if ($data['get_user_info']['gender'] == 0) {
            $userData['gender'] = 'Male';
        } elseif ($data['get_user_info']['gender'] == 1) {
            $userData['gender'] = 'Female';
        }
        if ($data['role_id'] == 1) {
            $userData['role'] = 'Admin';
        } elseif ($data['role_id'] == 2) {
            $userData['role'] = 'Employee';
        } else {
            $userData['role'] = 'Client';
        }

        return $userData;
    }

    /**
     * function for edit a user
     */
    public function editUser($id)
    {
        return User::select(
            'role_id',
            'is_active',
            'email',
            'id'
        )->where('id', $id)->with('getUserInfo')->first()->toArray();
    }

    /**
     * function for update a user
     */
    public function updateUser($request)
    {
        $updateUserData = [
            'email' => $request['email'],
            'role_id' => $request['role_id'],
            'updated_at' => Carbon::now(),
        ];
        User::where('id', $request['id'])->update($updateUserData);
        $userInfo = [
            'first_name' => $request['first_name'],
            'middle_name' => $request['middle_name'] ? $request['middle_name'] : ' ',
            'last_name' => $request['last_name'] ? $request['last_name'] : ' ',
            'gender' => $request['gender'],
            'date_of_birth' => ($request['dateofbirth'] ?
                                date('Y-m-d', strtotime($request['dateofbirth'])) : null),
            'address' => $request['address'] ? $request['address'] : ' ',
            'secondary_address' => $request['secondary_address'] ? $request['secondary_address'] : ' ',
            'contact_no' => $request['telephone'],
            'secondary_contact_no' => $request['emergency_contact'],
            'updated_at' => Carbon::now(),
        ];
        $update = UserInfo::where('user_id', $request['id'])->update($userInfo);
        if ($update) {
            return true;
        }
    }

    /**
     * Function for delete user
     */
    public function deleteUser($id)
    {
        $user = User::where('id', $id)->update([
            'is_deleted' => 1,
            'updated_at' => Carbon::now()
        ]);
        if ($user) {
            $delete = UserInfo::where('user_id', $id)->update([
                'is_deleted' => 1,
                'updated_at' => Carbon::now()
            ]);
            return $delete;
        }
    }

    /**
     * Function to get My profile
     */
    public function getMyProfile($id)
    {
        return User::select(
            'role_id',
            'is_active',
            'email',
            'id'
        )->where('id', $id)->with('getUserInfo')->first()->toArray();
    }

    /**
    * function for changing user password
    */
    public function changePassword($input)
    {
        $data= [
            'updated_at' => Carbon::now(),
            'password' => bcrypt($input['new_password'])
        ];

        return User::where('id', Auth::user()->id)->update($data);
    }
}
