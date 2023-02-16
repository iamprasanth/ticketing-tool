<?php

namespace TicketingTool\Http\Controllers\Auth;

use TicketingTool\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Lang;
use Auth;
use Response;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function login(Request $request)
    {
        $validator = $request->validate(
            [
            'email' => 'required|exists:users,email',
            'password' => 'required',
            ],
            ['email.required' => __('ticketingtool.email_required'),
            'password.required' => __('ticketingtool.password_required'),
            ]
        );
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'is_deleted' => 0])) {
            $user = Auth::user();
            if ($user->is_deleted == 1) {
                    Auth::logout();

                    return Response::json(["error" => __('ticketingtool.login_account_invalid')], 401);
            }
            return Response::json(["success" => true], 200);
        }
        return Response::json(["error" => __('ticketingtool.login_password_invalid')], 401);
    }
}
