<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Hootlex\Friendships\Traits\Friendable;

use Conner\Tagging\Taggable;
use App\Notifications\UserDeleted;
use Illuminate\Support\Facades\DB;
use Conner\Likeable\Likeable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, Taggable, Friendable, Likeable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'api_token', 'birth_year', 'pending_picture','city_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'name';
    }

    public function city()
    {
        return $this->belongsTo('App\City', 'city_id', 'id');
    }
    
    public function partner()
    {
        return $this->hasOne('App\User', 'partner_id', 'id');
    }


    public function isAdmin()
    {
        return $this->is_admin;
    }

    public function deleteAll()
    {   
        // Delete Conversation & Messages
        $convoId = DB::table('conversations')->select('id')->where('user_one',$this->id)->orWhere('user_two',$this->id)->get()->toArray();
        foreach ($convoId as $convo) {
            DB::table('conversations')->where('id',$convo->id)->delete();
            DB::table('messages')->where('conversation_id',$convo->id)->delete();
        }

        // Delete notifications
        DB::table('notifications')->where('notifiable_id',$this->id)->delete();

        // Delete Likes
        DB::table('likeable_likes')->where('user_id',$this->id)->delete();

        $this->notify(new UserDeleted($this->name));
        $this->delete();
        return true;
    }

    public function receivesBroadcastNotificationsOn() {
        return 'users.'.$this->id;
    }
}
