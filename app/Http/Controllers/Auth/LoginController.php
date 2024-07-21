<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
    protected $redirectTo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->redirectTo = route('admin.home.detail');
        $this->middleware('guest')->except('logout');
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => ['required', 'string', 
                function ($attribute, $value, $fail) use($request) {
                    $user = \App\User::where('username', $request->get('username'))->online()->first();
                    if(!$user){
                        $fail('These credentials do not match our records.');
                    }
                }],
            'password' => 'required|string',
        ]);
    }

    public function username()
    {
        return 'username';
    }
    
    protected function loggedOut(Request $request)
    {
        return redirect(route('login'));
    }
}
