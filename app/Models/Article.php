<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Article extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id', 'title', 'content', 'category', 'image_id', 
    //(ここにcreateを実行してもNULLになってしまっていたカラムを指定する)
    ];

    function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('Y年m月d日') : null;
    }

    public function comment()
    {
        return $this->hasMany('App\Models\Comment','article_id','id');
    }

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function category(){
       // return $this->hasMany('App\Models\Category', 'id','category');
        return $this->hasOne('App\Models\Category', 'id','category');
    }

    public function image(){
        return $this->hasOne('App\Models\Image');
    }

    public function like()
    {
        return $this->hasMany('App\Models\Like','article_id','id');
    }
}