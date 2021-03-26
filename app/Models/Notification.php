<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'details',
        'event_id'
    ];

    public function event() {
        return $this->belongsTo('App\Models\Event');
    }
}
