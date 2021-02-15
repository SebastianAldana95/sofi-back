<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use LdapRecord\Auth\BindException;
use LdapRecord\Laravel\Auth\ListensForLdapBindFailure;
use Illuminate\Validation\ValidationException;
use LdapRecord\Container;
use LdapRecord\Models\ActiveDirectory\User;

class LoginController extends Controller
{

    use AuthenticatesUsers, ListensForLdapBindFailure;

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

    public function username()
    {
        return 'username';
    }

    protected function credentials(Request $request)
    {
        return [
            'cn' => $request->get('username'),
            'password' => $request->get('password'),
            'fallback' => [
              'username' => $request->get('username'),
              'password' => $request->get('password'),
            ],
        ];
    }

    protected function handleLdapBindError($message, $code = null)
    {
        if ($code == '773') {
            // The users password has expired. Redirect them.
            abort(redirect('/password-reset'));
        }

        throw ValidationException::withMessages([
            'username' => "Whoops! LDAP server cannot be reached.",
        ]);
    }

    /*public function attemptLogin(Request $request)
    {

        $connection = Container::getDefaultConnection();
        $user = User::findByOrFail('sAMAccountName', $request->get('username'));

        try {
            if ($connection->auth()->attempt($user->getDn(), $request->password, $stayBound = true)) {
                return view('home')->with([
                    'message' => 'Credenciales correctas',
                    'data' => $user,
                ]);
            }


        } catch (BindException $e) {
            $error = $e->getDetailedError();

            echo $error->getErrorCode();
            echo $error->getErrorMessage();
            echo $error->getDiagnosticMessage();
        }
    }*/
}
