<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'state',
        'information',
        'place',
        'visibility'
    ];

    protected $hidden = [
        'pivot',
    ];

    public function users() {
        return $this->belongsToMany('\App\Models\User', 'event_user');
    }

    public function notifications() {
        return $this->hasMany('\App\Models\Notification');
    }

    public function resources() {
        return $this->hasMany('\App\Models\EventResource');
    }
}
