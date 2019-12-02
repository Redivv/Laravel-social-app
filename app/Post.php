<?php

namespace App;

use Auth;
use App\User;

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

    public function canBeSeen()
    {
        if ($this->is_public) {
            return true;
        }else{
            if ((Auth::user()->isFriendWith($this->user)) || (Auth::id() == $this->user_id)) {
                return true;
            }else{
                return false;
            }
        }
    }
}
