<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function field(Request $request)
    {
        $email = $this->username();
        return filter_var($request->get($email), FILTER_VALIDATE_EMAIL) ? $email : 'phone';
    }

    protected function validateLogin(Request $request)
    {
        $field       = $this->field($request);
        $messages    = ["{$this->username()}.exists" => 'The account you are trying to login is not registered or it has been disabled.'];
        $user        = User::where($field, $request->email)->where('status',1)->first();
        $currentRole =  $user->hasAnyRole(['vendor','admin']);
        if ($currentRole)
        {
            $this->validate($request,[
                $this->username() => "required|exists:users,{$field}",
                'password' => 'required'
            ], $messages);
            return true;
        } else {
            return false;
        }
    }

    public function login(Request $request)
    {
        $resp = $this->validateLogin($request);
        if (!$resp) { abort(401);}
        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }


        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

}
