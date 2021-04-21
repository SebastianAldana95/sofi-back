<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use LdapRecord\Laravel\Auth\LdapAuthenticatable;
use LdapRecord\Laravel\Auth\AuthenticatesWithLdap;
use LdapRecord\Laravel\Auth\HasLdapUser;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable implements LdapAuthenticatable
{
    use Notifiable, AuthenticatesWithLdap, HasLdapUser, HasApiTokens, HasFactory, HasRoles;

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

    protected $guard_name = 'api';

    public function setStateAttribute($value)
    {
        if ($value == 1) {
            $this->attributes['state'] = 'activo';
        } elseif ($value == 2) {
            $this->attributes['state'] = 'inactivo';
        } elseif ($value == 0) {
            $this->attributes['state'] = 'pendiente';
        }
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function events() {
        return $this->belongsToMany('\App\Models\Event', 'event_user');
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
