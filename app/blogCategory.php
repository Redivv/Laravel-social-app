<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class blogCategory extends Model
{

    protected $guarded = [
        'id'
    ];

    public function posts()
    {
        return $this->hasMany('App\blogPost', 'category_id', 'id')->orderBy('created_at','desc');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
