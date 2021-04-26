<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    protected $hidden = [
        'pivot',
    ];

    public function articles() {
        return $this->belongsToMany('\App\Models\Article', 'article_keyword')
            ->Where('visibility', '=', '1')
            ->with('resources', 'keywords')
            ->latest('date');

    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }

}
