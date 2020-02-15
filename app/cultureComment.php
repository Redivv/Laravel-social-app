<?php

namespace App;

use Conner\Likeable\Likeable;

use Illuminate\Database\Eloquent\Model;

class cultureComment extends Model
{
    use Likeable;
    
    protected $fillable = [
        'message','author_id','item_id','parent_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'author_id', 'id');
    }

    public function post()
    {
        return $this->belongsTo('App\cultureItem', 'item_id', 'id');
    }

    public function replies()
    {
        return $this->hasMany('App\cultureComment', 'parent_id')->orderBy('created_at','desc');
    }
}
