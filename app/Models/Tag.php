<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id ','tag_id',
    ];

    public function article(){
        return $this->belongsTo('App\Models\Article');
    }

    public function tagname(){
      //  return $this->belongsTo('App\Models\Tagname');
        return $this->hasOne('App\Models\Tagname', 'id','tag_id');
    }
}
