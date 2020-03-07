<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class cultureCategory extends Model
{

    protected $guarded = [
        'id'
    ];

    public function items()
    {
        return $this->hasMany('App\cultureItem', 'category_id', 'id')->orderBy('created_at','desc');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
