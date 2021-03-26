<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

use App\Models\Passport\Authenticator as PassportAuthenticator;
use App\Models\Passport\PassportClient;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Laravel\Passport\Passport;
use Psr\Http\Message\ServerRequestInterface;
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

    public function login(ServerRequestInterface $request, Request $login): JsonResponse
    {

        // Attempt logging in with ldap auth provider
        if (!Auth::attempt(['sAMAccountName' => $login['username'], 'password' => $login['password']])){
            return response()->json(['error' => 'Las credenciales proporcionadas no coinciden con nuestros registros'], 401);
        }

        // get the passport client using the API key passed in the request header
        if (!request()->header('apiKey')){
            return response()->json(["error" => "Su cliente no puede acceder a esta aplicación"], 401);
        }

        $user = Auth::user();
        /*if ($login->remember_token) {
            Passport::personalAccessTokensExpireIn(now()->addMinutes(3));
        }*/
        $tokenResult = $user->createToken('sofiApp');
        $token = $tokenResult->token;
        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString(),
            'token_type' => 'Bearer',
        ], 200);

        /*
        // generate passport tokens
        $client = PassportClient::findClientBySecret(request()->header('apikey'));

        $passport = (new PassportAuthenticator($request))
            ->authenticate($client, request('username'), request('password'));

        return response()->json([
            "access_token" => $passport->accessToken,
            "expires_in" => $passport->expires_in,
            "refresh_token" => $passport->refresh_token,
        ], 200);*/

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
