<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'message','pictures','author_id','post_id','parent_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'author_id', 'id');
    }

    public function post()
    {
        return $this->belongsTo('App\Post', 'post_id', 'id');
    }

    public function replies()
    {
        return $this->hasMany('App\Comment', 'parent_id', 'id');
    }
}
