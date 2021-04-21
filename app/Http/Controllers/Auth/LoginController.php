<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use Illuminate\Http\JsonResponse;
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

    public function password()
    {
        return 'password';
    }

    public function login(Request $login): JsonResponse
    {

        $validations = [
            'username' => 'required',
            'password' => 'required'
        ];

        $this->validate($login, $validations);

        $user = User::where('username', $login['username'])->first();
        if ($user) {
            if ($user->state != 'activo') {
                return response()->json(["error" => "Usuario pendiente o inactivo"], 403);
            }
            if (Hash::check($login['password'], $user->password)) {
                $tokenResult = $user->createToken('sofiApp');
                $token = $tokenResult->token;
                $token->save();
            } else {
                return response()->json(["error" => "Usuario o contraseña no coinciden! Intente nuevamente."], 401);
            }
        } else {
            // Attempt logging in with ldap auth provider
            if (!Auth::attempt(['sAMAccountName' => $login['username'], 'password' => $login['password']])) {
                return response()->json(["error" => "Las credenciales proporcionadas no fueron encontradas"], 401);
            }
            $user = Auth::user();
            $user->syncRoles('User');
            $tokenResult = $user->createToken('sofiApp');
            $token = $tokenResult->token;
            $token->save();
        }

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString(),
            'token_type' => 'Bearer',
        ]);


    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->token()->revoke();
        return response()->json([
                'message' => 'Successfully logged out'
        ]);

    }

    public function user(Request $request): JsonResponse
    {
        return response()->json($request->user());
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


}
