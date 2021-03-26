<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'title',
        'extract',
        'content',
        'state',
        'type',
        'visibility',
        'total_score',
        'article_id',
    ];

    protected $hidden = [
        'pivot',
    ];

    public function parentArticle() {
        return $this->hasMany(Article::class)
            ->where('article_id', '=', null);
    }

    public function childrenArticles() {
        return $this->hasMany(Article::class);//->with('parentArticle');
    }

    public function keywords() {
        return $this->belongsToMany('\App\Models\Keyword', 'article_keyword');
    }

    public function resources() {
        return $this->hasMany('\App\Models\Resource');
    }

    public function favorites() {
        return $this->belongsToMany('\App\Models\User', 'favorites');
    }

    public function scores() {
        return $this->belongsToMany('\App\Models\User', 'scores')
            ->withPivot('qualification', 'details');
    }
}
