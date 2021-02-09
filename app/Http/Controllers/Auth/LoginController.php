<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Adldap\Laravel\Facades\Adldap;

class LoginController extends Controller
{

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

    protected function email()
    {
        return 'email';
    }
    /*
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->email() => 'required|string|regex:/^[A-Za-z]+\.[A-Za-z]+$/',
            'password' => 'required|string',
        ]);
    }

    protected function attemptLogin(Request $request)
    {

        $credentials = $request->only($this->email(), 'password');
        $email = $credentials[$this->email()];
        $password = $credentials['password'];

        $user_format = env('LDAP_USER_FORMAT');
        $userdn = sprintf($user_format, $email);

        if (Adldap::auth()->attempt($userdn, $password, $bindAsUser = true)) {

            $user = User::where($this->username(), $email)->first();

            if (!$user) {

                $user = new User();
                $user->email = $email;
                $user->password = '';

                $sync_attrs = $this->retrieveSyncAttributes($email);
                foreach ($sync_attrs as $field => $value) {
                    $user->$field = $value !== null ? $value : '';
                }
            }

            $this->guard()->login($user, true);
            return true;
        }
        // the user doesn't exist in the LDAP server or the password is wrong
        // log error
        return false;
    }

    protected function retrieveSyncAttributes($email)
    {
        $ldapuser = Adldap::search()->where(env('LDAP_USER_ATTRIBUTE'), '=', $email)->first();

        if (!$ldapuser) {
            // log error
            return false;
        }

        $ldapuser_attrs = null;

        $attrs = [];

        foreach (config('ldap_auth.sync_attributes') as $local_attr => $ldap_attr) {
            if ($local_attr == 'email') {
                continue;
            }

            $method = 'get' . $ldap_attr;
            if (method_exists($ldapuser, $method)) {
                $attrs[$local_attr] = $ldapuser->$method();
                continue;
            }

            if ($ldapuser_attrs === null) {
                $ldapuser_attrs = self::accessProtected($ldapuser, 'attributes');
            }

            if (!isset($ldapuser_attrs[$ldap_attr])) {
                // an exception could be thrown
                $attrs[$local_attr] = null;
                continue;
            }

            if (!is_array($ldapuser_attrs[$ldap_attr])) {
                $attrs[$local_attr] = $ldapuser_attrs[$ldap_attr];
            }

            if (count($ldapuser_attrs[$ldap_attr]) == 0) {
                // an exception could be thrown
                $attrs[$local_attr] = null;
                continue;
            }

            $attrs[$local_attr] = $ldapuser_attrs[$ldap_attr][0];
            //$attrs[$local_attr] = implode(',', $ldapuser_attrs[$ldap_attr]);
        }

        return $attrs;
    }

    protected static function accessProtected($obj, $prop)
    {
        $reflection = new \ReflectionClass($obj);
        $property = $reflection->getProperty($prop);
        $property->setAccessible(true);
        return $property->getValue($obj);
    }*/

}
