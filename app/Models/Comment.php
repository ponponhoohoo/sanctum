<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id','user_id','title','content',
    ];

    // public function GetArticleComment(){
    //     return $this->belongsTo('App\Articles', 'id');
    // }

    function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('Y年m月d日') : null;
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
