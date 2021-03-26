<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use LdapRecord\Laravel\Auth\LdapAuthenticatable;
use LdapRecord\Laravel\Auth\AuthenticatesWithLdap;
use LdapRecord\Laravel\Auth\HasLdapUser;
use Laravel\Passport\HasApiTokens;


class User extends Authenticatable implements LdapAuthenticatable
{
    use Notifiable, AuthenticatesWithLdap, HasLdapUser, HasApiTokens, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'identification',
        'username',
        'name',
        'lastname',
        'email',
        'title',
        'institution',
        'phone1',
        'phone2',
        'address',
        'alternatename',
        'url',
        'lang',
        'firstnamephonetic',
        'lastnamephonetic',
        'middlename',
        'photo',
        'state',
        'user',
        'city',
        'country',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function adminlte_image(){
        return 'https://picsum.photos/300/300';
    }

    public function adminlte_desc(){
        return auth()->user()->email;
    }

    public function adminlte_profile_url(){
        return 'profile/username';
    }

    public function events() {
        return $this->belongsToMany('\App\Models\Event', 'event_user')
            ->where('visibility', '=', '1')
            ->with('resources', 'notifications')
            ->latest('start_date');

    }

    public function favorites() {
        return $this->belongsToMany('\App\Models\Article', 'favorites')
            ->Where('visibility', '=', '1')
            ->with('resources', 'keywords', 'childrenArticles.resources')
            ->latest('date');
    }

    public function scores() {
        return $this->belongsToMany('\App\Models\Article', 'scores')
            ->withPivot('qualification', 'details');
    }

}
