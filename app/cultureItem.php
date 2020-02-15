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

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany('App\cultureComment')->whereNull('parent_id')->orderBy('created_at','desc');
    }

    public function category()
    {
        return $this->belongsTo('App\cultureCategory', 'category_id', 'id');
    }

}
