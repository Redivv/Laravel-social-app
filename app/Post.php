<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Conner\Likeable\Likeable;

class Post extends Model
{
    use Likeable;
    
    protected $fillable = [
        'user_id', 'desc', 'pictures'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment')->whereNull('parent_id')->orderBy('created_at','desc');
    }
}
