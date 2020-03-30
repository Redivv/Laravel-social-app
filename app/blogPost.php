<?php

namespace App;

use Conner\Likeable\Likeable;
use Conner\Tagging\Taggable;
use Illuminate\Database\Eloquent\Model;

class blogPost extends Model
{
    use Likeable,Taggable;

    protected $guarded= [
        'id',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'name_slug';
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'author_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo('App\blogCategory', 'category_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany('App\blogComment', 'post_id','id')->whereNull('parent_id')->orderBy('created_at','desc')->take(5);
    }
}
