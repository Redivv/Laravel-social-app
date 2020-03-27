<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class blogComment extends Model
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
        return $this->belongsTo('App\blogPost', 'post_id', 'id');
    }

    public function replies()
    {
        return $this->hasMany('App\blogComment', 'parent_id')->orderBy('created_at','desc');
    }
}
