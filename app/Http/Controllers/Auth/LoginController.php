<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Article;
use App\Models\Event;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Mail\LogInMailable;
use App\Mail\ForgotPasswordMailable;
use App\Mail\UserRegisteredMailable;
use Illuminate\Support\Facades\Mail;

use DB;


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
            //if (Hash::check($login['password'], $user->password)) {
            if(true){
	        $tokenResult = $user->createToken('sofiApp');
                $token = $tokenResult->token;
                $token->save();
            	$logInMail = new LogInMailable($user->id);
            	Mail::to($user->email)->send($logInMail);
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
	    'user'=>$user,
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

 
    public function sendUserRegisteredMail(Request $request): JsonResponse
    {
        $userId = $request->userid;
        $user = User::find($userId);
        if($user) {
            $userRegisteredMail = new UserRegisteredMailable($user->id);
            Mail::to($user->email)->send($userRegisteredMail);
            $response = [
                "message" => __('messages.user_registered_mail_sent'),
                "data" => [
                    "userid" => $userId,
                    "userinfo" => [
                        "identification" => $user->identificacion,
                        "username" => $user->username,
                        "name" => $user->name,
                        "last_name" => $user->last_name,
                        "email" => $user->email,
                        "title" => $user->title,
                        "institucion" => $user->institucion,
                        "phone1" => $user->phone1,
                        "phone2" => $user->phone2,
                        "address" => $user->address,
                        "alternatename" => $user->alternatename,
                        "url" => $user->url,
                        "lang" => $user->lang,
                        "firstnamephonetic" => $user->firstnamephonetic,
                        "lastnamephonetic" => $user->lastnamephonetic,
                        "middlename" => $user->middlename,
                        "photo" => $user->photo,
                        "state" => $user->state,
                        "user" => $user->user,
                        "city" => $user->city,
                        "country" => $user->country
                    ]
                ],
                "error" => ""
            ];
        } else {
            $response = [
                "message" => __('messages.user_does_not_exist'),
                "data" => [],
                "error" => __('messages.user_does_not_exist')
            ];
        }
        return response()->json($response);
    }

    public function sendLogInMail(Request $request): JsonResponse
    {
        $userId = $request->userid;
        $user = User::find($userId);
        if($user) {
            $logInMail = new LogInMailable($user->id);
            Mail::to($user->email)->send($logInMail);
            $response = [
                "message" => __('messages.log_in_mail_sent'),
                "data" => [
                    "userid" => $user->id,
                    "userinfo" => [
                        "identification" => $user->identificacion,
                        "username" => $user->username,
                        "name" => $user->name,
                        "last_name" => $user->last_name,
                        "email" => $user->email,
                        "title" => $user->title,
                        "institucion" => $user->institucion,
                        "phone1" => $user->phone1,
                        "phone2" => $user->phone2,
                        "address" => $user->address,
                        "alternatename" => $user->alternatename,
                        "url" => $user->url,
                        "lang" => $user->lang,
                        "firstnamephonetic" => $user->firstnamephonetic,
                        "lastnamephonetic" => $user->lastnamephonetic,
                        "middlename" => $user->middlename,
                        "photo" => $user->photo,
                        "state" => $user->state,
                        "user" => $user->user,
                        "city" => $user->city,
                        "country" => $user->country
                    ]
                ],
                "error" => ""
            ];
        } else {
            $response = [
                "message" => __('messages.user_does_not_exist'),
                "data" => [
                    'userid' => $userId
                ],
                "error" => __('messages.user_does_not_exist')
            ];
        }
        return response()->json($response);
    }

    public function generateDigitCode() {
        $digit = rand(0,9);
        return $digit;
    }

    public function sendForgotPasswordMail(Request $request): JsonResponse
    {
        $userMail = $request->usermail;
        $user = User::where('email', '=', $userMail)->first();
        if($user) {
            $confirmationCode = "";
            for($i=0; $i<env('CONFIRMATION_CODE_LENGHT'); $i++) {
                $confirmationCode .=  "".$this->generateDigitCode()."";
            }
            $forgotPasswordMail = new ForgotPasswordMailable($confirmationCode);
            Mail::to($user->email)->send($forgotPasswordMail);
            $expiresAt = Carbon::now()->addMinutes(10);
            $user->confirmation_code = $confirmationCode;
            $user->confirmation_code_expires_at = $expiresAt->format('Y-m-d H:i:s');
           // $user->time_expires_at=$expiresAt->format('H:i:s');
	    $user->save();
            $response = [
                "message" => __('messages.forgot_password_mail_sent'),
                "data" => [
                    "usermail" => $user->email,
                    "code" => $confirmationCode,
                    "expires_at" => $expiresAt->format('Y-m-d H:i:s')
                ],
                "error" => ""
            ];
        } else {
            $response = [
                "message" => __('messages.user_does_not_exist'),
                "data" => [
                    "usermail" => $userMail
                ],
                "error" => __('messages.user_does_not_exist')
                
            ];
        }
        return response()->json($response);
    }

    public function addFavoriteArticlesToUser(Request $request): JsonResponse
    {
        $userId = $request->userid;
        $userToken = $request->usertoken;
        $articlesIds = $request->articlesids;
        $user = User::find($userId);
        if($user) {
            $articlesSaved = 0;
            $articlesRejected = 0;
            foreach($articlesIds as $articleId) {
                $article = Article::find($articleId);
                if($article) {
                    $favorite = DB::table('favorites')->where('user_id', '=', $user->id)->where('article_id', '=', $article->id)->first();
                    if($favorite) {
                    } else {
                        DB::table('favorites')->insert([
                            'article_id' => $article->id,
                            'user_id' => $user->id
                        ]);                        
                    }
                    $articlesSaved++;
                } else {
                    $articlesRejected++;
                }
            }
            if($articlesRejected == 0) {
                $message = __('messages.articles_added_to_favorites');
            } else if($articlesSaved == 0) {
                $message = __('messages.articles_did_not_add_to_favorites');
            }
            else {
                $message = __('messages.some_articles_added_to_favorites');
            }
            $articlesFavorites = DB::table('favorites')
                ->select([
                    'favorites.user_id as userid',
                    'favorites.article_id as articleid',
                    'users.name',
                    'articles.title as titlearticle',
                    'articles.article_id as parentarticle'
                ])
                ->join('users', 'users.id', '=', 'favorites.user_id')
                ->join('articles', 'articles.id', '=', 'favorites.article_id')
                ->where('favorites.user_id', '=', $user->id)
                ->orderBy('favorites.article_id', 'ASC')
                ->get();
            $response = [
                "message" => $message,
                "data" => [
                    'userid' => $user->id,
                    'articlesfavorites' => $articlesFavorites,
                    'articlessaved' => $articlesSaved,
                    'articlesrejected' => $articlesRejected
                ],
                "error" => ""
            ];
        } else {
            $response = [
                "message" => __('messages.user_does_not_exist'),
                "data" => [],
                "error" => __('messages.user_does_not_exist')
            ];
        }
        return response()->json($response);
    }

    public function removeFavoriteArticlesToUser(Request $request): JsonResponse
    {
        $userId = $request->userid;
        $articleId = $request->articleid;
        $userToken = $request->usertoken;
        $user = User::find($userId);
        $articlesRemoved = 0;
        if($user) {
            $article = Article::find($articleId);
            if($article) {
                $favorite = DB::table('favorites')->where('user_id', '=', $user->id)->where('article_id', '=', $article->id)->first();
                if($favorite) {
                    $articlesRemoved++;
                    DB::table('favorites')->where('user_id', '=', $user->id)->where('article_id', '=', $article->id)->delete();
                    $articlesFavorites = DB::table('favorites')
                    ->select([
                        'favorites.user_id as userid',
                        'favorites.article_id as articleid',
                        'users.name',
                        'articles.title as titlearticle',
                        'articles.article_id as parentarticle'
                    ])
                    ->join('users', 'users.id', '=', 'favorites.user_id')
                    ->join('articles', 'articles.id', '=', 'favorites.article_id')
                    ->where('favorites.user_id', '=', $user->id)
                    ->orderBy('favorites.article_id', 'ASC')
                    ->get();
                    $response = [
                        "message" => __('messages.favorite_removed'),
                        "data" => [
                            'userid' => $userId,
                            'articleid' => $articleId,
                            'articlesfavorites' => $articlesFavorites,
                            'articlesRemoved' => $articlesRemoved
                        ],
                        "error" => ""
                    ];
                } else {
                    $articlesFavorites = DB::table('favorites')
                    ->select([
                        'favorites.user_id as userid',
                        'favorites.article_id as articleid',
                        'users.name',
                        'articles.title as titlearticle',
                        'articles.article_id as parentarticle'
                    ])
                    ->join('users', 'users.id', '=', 'favorites.user_id')
                    ->join('articles', 'articles.id', '=', 'favorites.article_id')
                    ->where('favorites.user_id', '=', $user->id)
                    ->orderBy('favorites.article_id', 'ASC')
                    ->get();
                    $response = [
                        "message" => __('messages.favorite_does_not_exist'),
                        "data" => [
                            'userid' => $userId,
                            'articleid' => $articleId,
                            'articlesfavorites' => $articlesFavorites,
                            'articlesRemoved' => $articlesRemoved
                        ],
                        "error" => __('messages.favorite_does_not_exist')
                    ];
                }
            } else {
                $articlesFavorites = DB::table('favorites')
                ->select([
                    'favorites.user_id as userid',
                    'favorites.article_id as articleid',
                    'users.name',
                    'articles.title as titlearticle',
                    'articles.article_id as parentarticle'
                ])
                ->join('users', 'users.id', '=', 'favorites.user_id')
                ->join('articles', 'articles.id', '=', 'favorites.article_id')
                ->where('favorites.user_id', '=', $user->id)
                ->orderBy('favorites.article_id', 'ASC')
                ->get();
                $response = [
                    "message" => __('messages.article_does_not_exist'),
                    "data" => [
                        'userid' => $userId,
                        'articleid' => $articleId,
                        'articlesfavorites' => $articlesFavorites,
                        'articlesRemoved' => $articlesRemoved
                    ],
                    "error" => __('messages.article_does_not_exist')
                ];
            }
        } else {
            $response = [
                "message" => __('messages.user_does_not_exist'),
                "data" => [
                    'userid' => $userId,
                    'articleid' => $articleId,
                    'articlesfavorites' => [],
                    'articlesRemoved' => $articlesRemoved
                ],
                "error" => __('messages.user_does_not_exist')
            ];
        }
        return response()->json($response);
    }

    public function existFavorite(Request $request): JsonResponse
    {
        $userId = $request->userid;
        $articleId = $request->articleid;
        $userToken = $request->usertoken;
        $user = User::find($userId);
        $favoriteFound = false;
        if($user) {
            $article = Article::find($articleId);
            if($article) {
                $favorite = DB::table('favorites')->where('user_id', '=', $user->id)->where('article_id', '=', $article->id)->first();
                if($favorite) {
                    $favoriteFound = true;
                    $articlesFavorites = DB::table('favorites')
                    ->select([
                        'favorites.user_id as userid',
                        'favorites.article_id as articleid',
                        'users.name',
                        'articles.title as titlearticle',
                        'articles.article_id as parentarticle'
                    ])
                    ->join('users', 'users.id', '=', 'favorites.user_id')
                    ->join('articles', 'articles.id', '=', 'favorites.article_id')
                    ->where('favorites.user_id', '=', $user->id)
                    ->orderBy('favorites.article_id', 'ASC')
                    ->get();
                    $response = [
                        "message" => __('messages.favorite_removed'),
                        "data" => [
                            'userid' => $userId,
                            'articleid' => $articleId,
                            'articlesfavorites' => $articlesFavorites,
                            'favoritefound' => $favoriteFound
                        ],
                        "error" => ""
                    ];
                } else {
                    $articlesFavorites = DB::table('favorites')
                    ->select([
                        'favorites.user_id as userid',
                        'favorites.article_id as articleid',
                        'users.name',
                        'articles.title as titlearticle',
                        'articles.article_id as parentarticle'
                    ])
                    ->join('users', 'users.id', '=', 'favorites.user_id')
                    ->join('articles', 'articles.id', '=', 'favorites.article_id')
                    ->where('favorites.user_id', '=', $user->id)
                    ->orderBy('favorites.article_id', 'ASC')
                    ->get();
                    $response = [
                        "message" => __('messages.favorite_does_not_exist'),
                        "data" => [
                            'userid' => $userId,
                            'articleid' => $articleId,
                            'articlesfavorites' => $articlesFavorites,
                            'favoritefound' => $favoriteFound
                        ],
                        "error" => __('messages.favorite_does_not_exist')
                    ];
                }
            } else {
                $articlesFavorites = DB::table('favorites')
                ->select([
                    'favorites.user_id as userid',
                    'favorites.article_id as articleid',
                    'users.name',
                    'articles.title as titlearticle',
                    'articles.article_id as parentarticle'
                ])
                ->join('users', 'users.id', '=', 'favorites.user_id')
                ->join('articles', 'articles.id', '=', 'favorites.article_id')
                ->where('favorites.user_id', '=', $user->id)
                ->orderBy('favorites.article_id', 'ASC')
                ->get();
                $response = [
                    "message" => __('messages.article_does_not_exist'),
                    "data" => [
                        'userid' => $userId,
                        'articleid' => $articleId,
                        'articlesfavorites' => $articlesFavorites,
                        'favoritefound' => $favoriteFound
                    ],
                    "error" => __('messages.article_does_not_exist')
                ];
            }
        } else {
            $response = [
                "message" => __('messages.user_does_not_exist'),
                "data" => [
                    'userid' => $userId,
                    'articleid' => $articleId,
                    'articlesfavorites' => [],
                    'favoritefound' => $favoriteFound
                ],
                "error" => __('messages.user_does_not_exist')
            ];
        }
        return response()->json($response);
    }

    public function getFavoriteArticlesByUser(Request $request): JsonResponse
    {
        $userId = $request->userid;
        $userToken = $request->usertoken;
        $user = User::find($userId);
        if($user) {
            $message = __('messages.favorites_list');
            $articlesFavorites = DB::table('favorites')
                ->select([
                    'favorites.user_id as userid',
                    'favorites.article_id as articleid',
                    'users.name',
                    'articles.title as titlearticle',
                    'articles.article_id as parentarticle'
                ])
                ->join('users', 'users.id', '=', 'favorites.user_id')
                ->join('articles', 'articles.id', '=', 'favorites.article_id')
                ->where('favorites.user_id', '=', $user->id)
                ->orderBy('favorites.article_id', 'ASC')
                ->get();
            $response = [
                "message" => $message,
                "data" => [
                    'userid' => $user->id,
                    'articlesfavorites' => $articlesFavorites
                ],
                "error" => ""
            ];
        } else {
            $response = [
                "message" => __('messages.user_does_not_exist'),
                "data" => [],
                "error" => __('messages.user_does_not_exist')
            ];
        }
        return response()->json($response);
    }

    public function getUsersByFavoriteArticle(Request $request): JsonResponse
    {
        $articleId = $request->articleid;
        $userToken = $request->usertoken;
        $article = Article::find($articleId);
        if($article) {
            $message = __('messages.favorites_list');
            $usersFavorites = DB::table('favorites')
                ->select([
                    'favorites.user_id as userid',
                    'favorites.article_id as articleid',
                    'users.name',
                    'articles.title as titlearticle',
                    'articles.article_id as parentarticle'
                ])
                ->join('users', 'users.id', '=', 'favorites.user_id')
                ->join('articles', 'articles.id', '=', 'favorites.article_id')
                ->where('favorites.article_id', '=', $article->id)
                ->orderBy('favorites.user_id', 'ASC')
                ->get();
            $response = [
                "message" => $message,
                "data" => [
                    'articleid' => $article->id,
                    'usersfavorites' => $usersFavorites
                ],
                "error" => ""
            ];
        } else {
            $response = [
                "message" => __('messages.article_does_not_exist'),
                "data" => [],
                "error" => __('messages.article_does_not_exist')
            ];
        }
        return response()->json($response);
    }

    public function addScoreToArticles(Request $request): JsonResponse
    {
        if(!isset($request->userid)){
            $response = [
                "message" => __('messages.field_required'),
                "data" => [],
                "error" => __('messages.field_required')
            ];
            return response()->json($response);
        }
        if(!isset($request->articleid)){
            $response = [
                "message" => __('messages.field_required'),
                "data" => [],
                "error" => __('messages.field_required')
            ];
            return response()->json($response);
        }
        if(!isset($request->qualification)){
            $response = [
                "message" => __('messages.field_required'),
                "data" => [],
                "error" => __('messages.field_required')
            ];
            return response()->json($response);
        }
        $userId = $request->userid;
        $userToken = $request->usertoken;
        $articleId = $request->articleid;
        $qualification = $request->qualification;
        $details = $request->details;
        $user = User::find($userId);
        if($user) {
            $articlesQualificated = 0;
            $articlesRejected = 0;
            $article = Article::find($articleId);
            if($article) {
                $score = DB::table('scores')->where('user_id', '=', $user->id)->where('article_id', '=', $article->id)->first();
                if($score) {
                    $score = DB::table('scores')->where('user_id', '=', $user->id)->where('article_id', '=', $article->id)->update([
                            'qualification' => $qualification,
                            'details' => $details
                        ]);
                } else {
                    DB::table('scores')->insert([
                        'article_id' => $article->id,
                        'user_id' => $user->id,
                        'qualification' => $qualification,
                        'details' => $details
                    ]);                        
                }
                $message = __('messages.articles_qulificated');
            } else {
                $message = __('messages.article_does_not_exist');
            }
            $scores = DB::table('scores')
                ->select([
                    'scores.user_id as userid',
                    'scores.article_id as articleid',
                    'scores.qualification as qualification',
                    'scores.details as details',
                    'users.name',
                    'articles.title as titlearticle',
                    'articles.article_id as parentarticle'
                ])
                ->join('users', 'users.id', '=', 'scores.user_id')
                ->join('articles', 'articles.id', '=', 'scores.article_id')
                ->where('scores.user_id', '=', $user->id)
                ->orderBy('scores.article_id', 'ASC')
                ->get();
            $response = [
                "message" => $message,
                "data" => [
                    'userid' => $user->id,
                    'scores' => $scores,
                    'articlesqualificated' => $articlesQualificated,
                    'articlesrejected' => $articlesRejected
                ],
                "error" => ""
            ];
        } else {
            $response = [
                "message" => __('messages.user_does_not_exist'),
                "data" => [],
                "error" => __('messages.user_does_not_exist')
            ];
        }
        
        return response()->json($response);
    }

    public function getScoresByUser(Request $request): JsonResponse
    {
        if(!isset($request->userid)){
            $response = [
                "message" => __('messages.field_required'),
                "data" => [],
                "error" => __('messages.field_required')
            ];
            return response()->json($response);
        }
        $userId = $request->userid;
        $userToken = $request->usertoken;
        $user = User::find($userId);
        if($user) {
            $scores = DB::table('scores')
                ->select([
                    'scores.user_id as userid',
                    'scores.article_id as articleid',
                    'scores.qualification as qualification',
                    'scores.details as details',
                    'users.name',
                    'articles.title as titlearticle'
                ])
                ->join('users', 'users.id', '=', 'scores.user_id')
                ->join('articles', 'articles.id', '=', 'scores.article_id')
                ->where('scores.user_id', '=', $user->id)
                ->orderBy('scores.article_id', 'ASC')
                ->get();
            $response = [
                "message" => __('messages.score_list'),
                "data" => [
                    'userid' => $user->id,
                    'scores' => $scores
                ],
                "error" => ""
            ];
        } else {
            $response = [
                "message" => __('messages.user_does_not_exist'),
                "data" => [],
                "error" => __('messages.user_does_not_exist')
            ];
        }
        return response()->json($response);
    }

    public function getScoresByArticle(Request $request): JsonResponse
    {
        if(!isset($request->articleid)) {
            $response = [
                "message" => __('messages.field_required'),
                "data" => [],
                "error" => __('messages.field_required')
            ];
            return response()->json($response);
        }
        $articleId = $request->articleid;
        $userToken = $request->usertoken;
        $article = Article::find($articleId);
        if($article) {
            $scores = DB::table('scores')
                ->select([
                    'scores.user_id as userid',
                    'scores.article_id as articleid',
                    'scores.qualification as qualification',
                    'scores.details as details',
                    'users.name',
                    'articles.title as titlearticle'
                ])
                ->join('users', 'users.id', '=', 'scores.user_id')
                ->join('articles', 'articles.id', '=', 'scores.article_id')
                ->where('scores.article_id', '=', $article->id)
                ->orderBy('scores.user_id', 'ASC')
                ->get();
            $response = [
                "message" => __('messages.score_list'),
                "data" => [
                    'articleid' => $article->id,
                    'scores' => $scores
                ],
                "error" => ""
            ];
        } else {
            $response = [
                "message" => __('messages.article_does_not_exist'),
                "data" => [],
                "error" => __('messages.article_does_not_exist')
            ];
        }
        return response()->json($response);
    }

    public function getFutureEvents(Request $request): JsonResponse
    {
        $message = __('messages.future_events_list');
        $now = Carbon::now();
        $today = Carbon::today();
        $futureEvents = Event::where('start_date', '>=', $today)->with(['resources', 'notifications'])->get();
        $response = [
            "message" => $message,
            "data" => [
                "today" => $today->format('Y-m-d H:i:s'),
                "futureevents" => $futureEvents
            ],
            "error" => ""
        ];
        return response()->json($response);
    }

    public function getFutureEventsByState(Request $request): JsonResponse
    {
        $eventState = $request->eventstate;
        $message = __('messages.future_events_list');
        $now = Carbon::now();
        $today = Carbon::today();
        $futureEvents = Event::where('start_date', '>=', $today)->where('state', '=', $eventState)->with(['resources', 'notifications'])->get();
        $response = [
            "message" => $message,
            "data" => [
                "today" => $today->format('Y-m-d H:i:s'),
                "futureevents" => $futureEvents
            ],
            "error" => ""
        ];
        return response()->json($response);
    }

    public function getFutureEventsByUser(Request $request): JsonResponse
    {
        $message = __('messages.future_events_list');
        $user = User::find($request->userid);
        $now = Carbon::now();
        $today = Carbon::today();
        $eventsIds = DB::table('event_user')
            ->select('event_id')
            ->where('user_id', '=', $user->id)
            ->orderBy('event_id', 'ASC')
            ->get();
        $eventsIdsArray = [];
        foreach ($eventsIds as $eventId) {
            array_push($eventsIdsArray, $eventId->event_id);
        }
        $events = Event::whereIn('id', $eventsIdsArray)->where('start_date', '>=', $today)->with(['resources', 'notifications'])->get();
        $response = [
            "message" => $message,
            "data" => [
                // "now" => $now->format('Y-m-d H:i:s'),
                "userid" => $user->id,
                "events" => $events
            ],
            "error" => ""
        ];
        return response()->json($response);
    }

    public function getFutureEventsByUserAndState(Request $request): JsonResponse
    {
        $message = __('messages.future_events_list');
        $user = User::find($request->userid);
        $eventState = $request->eventstate;
        $now = Carbon::now();
        $today = Carbon::today();
        $eventsIds = DB::table('event_user')
            ->select('event_id')
            ->where('user_id', '=', $user->id)
            ->orderBy('event_id', 'ASC')
            ->get();
        $eventsIdsArray = [];
        foreach ($eventsIds as $eventId) {
            array_push($eventsIdsArray, $eventId->event_id);
        }
        $events = Event::whereIn('id', $eventsIdsArray)->where('start_date', '>=', $today)->where('state', '=', $eventState)->with(['resources', 'notifications'])->get();
        $response = [
            "message" => $message,
            "data" => [
                // "now" => $now->format('Y-m-d H:i:s'),
                "userid" => $user->id,
                "eventsids" => $eventsIdsArray,
                "events" => $events,
            ],
            "error" => ""
        ];
        return response()->json($response);
    }

    public function confirmCodeRestorePassword(Request $request): JsonResponse
    {
        $message = __('messages.confirm_code_restore_password');
        $userId = $request->userid;
        $confirmationCode = $request->confirmationcode;
        $user = User::find($userId);
        if($user) {
            $now = Carbon::now();
            $expiresAt = Carbon::parse($user->confirmation_code_expires_at);
            if($now > $expiresAt) {
                $expired = "Yes";
                $response = [
                    "message" => __('messages.code_confirmation_expired'),
                    "data" => [
                        'userid' => $userId,
                        'confirmationcodeinput' => $confirmationCode,
                        'confirmationcodereal' => $user->confirmation_code,
                        'now' => $now->format('Y-m-d H:i:s'),
                        'expiresdate' => $user->confirmation_code_expires_at,
                        'codeexpired' => $expired,
                        'codeconfirmed' => false
                    ],
                    "error" => __('messages.code_confirmation_expired')
                ];
            } else {
                $expired = "No";
                if($confirmationCode == $user->confirmation_code) {
                    $response = [
                        "message" => __('messages.code_confirmation_accepted'),
                        "data" => [
                            'userid' => $user->id,
                            'confirmationcodeinput' => $confirmationCode,
                            'confirmationcodereal' => $user->confirmation_code,
                            'now' => $now->format('Y-m-d H:i:s'),
                            'expiresdate' => $user->confirmation_code_expires_at,
                            'codeexpired' => $expired,
                            'codeconfirmed' => true
                        ],
                        "error" => ""
                    ];
                } else {
                    $response = [
                        "message" => __('messages.code_confirmation_does_not_match'),
                        "data" => [
                            'userid' => $userId,
                            'confirmationcodeinput' => $confirmationCode,
                            'confirmationcodereal' => $user->confirmation_code,
                            'now' => $now->format('Y-m-d H:i:s'),
                            'expiresdate' => $user->confirmation_code_expires_at,
                            'codeexpired' => $expired,
                            'codeconfirmed' => false
                        ],
                        "error" => __('messages.code_confirmation_does_not_match')
                    ];
                }
            }
        } else {
            $response = [
                "message" => __('messages.user_does_not_exist'),
                "data" => [
                    'userid' => $userId,
                    'codeconfirmed' => false
                ],
                "error" => __('messages.user_does_not_exist')
            ];
        }
            
        return response()->json($response);
    }

    public function saveNewPassword(Request $request): JsonResponse
    {
        $userId = $request->userid;
        $userNewPassword = $request->usernewpassword;
        $userConfirmPassword = $request->userconfirmpassword;
        $user = User::find($userId);
        if($user) {
            if($userNewPassword === $userConfirmPassword) {
                $user->password = bcrypt($userNewPassword);
		$user->confirmation_code = null;
		$user->confirmation_code_expires_at = null;
                $user->save();
                $response = [
                    "message" => __('messages.password_restored'),
                    "data" => [
                        'userid' => $user->id,
                        'usernewpassword' => $userNewPassword,
                        'userconfirmpassword' => $userConfirmPassword,
                        'passwordrestored' => true
                    ],
                    "error" => ""
                ];
            } else {
                $user->confirmation_code = null;
                $user->confirmation_code_expires_at = null;
                $user->save();
		 $response = [
                    "message" => __('messages.passwords_do_not_match'),
                    "data" => [
                        'userid' => $user->id,
                        'usernewpassword' => $userNewPassword,
                        'userconfirmpassword' => $userConfirmPassword,
                        'passwordrestored' => false
                    ],
                    "error" => __('messages.passwords_do_not_match')
                ];
            }
                
        } else {
            $response = [
                "message" => __('messages.user_does_not_exist'),
                "data" => [
                    'userid' => $userId,
                    'passwordrestored' => false
                ],
                "error" => __('messages.user_does_not_exist')
            ];
        }

        return response()->json($response);
    }
}
