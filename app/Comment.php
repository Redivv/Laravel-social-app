<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Conner\Likeable\Likeable;

class Comment extends Model
{
    use Likeable;
    
    protected $fillable = [
        'message','author_id','post_id','parent_id'
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
        return $this->hasMany('App\Comment', 'parent_id')->orderBy('created_at','desc');
    }
}
