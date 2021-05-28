<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'title',
	'author',
        'extract',
        'content',
        'state',
        'type',
        'visibility',
        'total_score',
        'article_id',
	'primary_image'

    ];

    protected $hidden = [
        'pivot',
    ];

    public function getTotal_ScoreAttribute() {
        return $this->scores()->sum(DB::raw('qualification'));
    }

    /* Relationships */

    public function parentArticle() {
        return $this->hasMany(Article::class)
            ->where('article_id', '=', null);
    }

    public function childrenArticles() {
        return $this->hasMany(Article::class)->with('parentArticle');
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
