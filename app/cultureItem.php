<?php

namespace App;

use Conner\Tagging\Taggable;
use Conner\Likeable\Likeable;

use Illuminate\Database\Eloquent\Model;

class cultureItem extends Model
{
    use Taggable, Likeable;
    
    protected $guarded = [
        'id'
    ];
    public function getRouteKeyName()
    {
        return 'name_slug';
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany('App\cultureComment', 'item_id', 'id')->whereNull('parent_id')->orderBy('created_at','desc')->take(5);
    }

    public function category()
    {
        return $this->belongsTo('App\cultureCategory', 'category_id', 'id');
    }

}
