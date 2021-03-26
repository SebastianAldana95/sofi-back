<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventResource extends Model
{
    use HasFactory;

    protected $table = 'event_resources';

    protected $primaryKey = 'id';

    protected $fillable = [
        'type',
        'url',
        'event_id'
    ];

    public function event() {
        return $this->belongsTo('App\Models\Event');
    }
}
