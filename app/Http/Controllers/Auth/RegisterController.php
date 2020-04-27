<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

use App\User;
use App\Notifications\NewProfilePicture;
use Illuminate\Support\Facades\DB;

use App\Rules\NotBanned;

use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/user/profile';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $data['birth_year'] = intVal($data['birth_year']);
        return Validator::make($data, [
            'name'              => ['required', 'string', 'alpha_dash', 'unique:users,name', 'max:255'],
            'email'             => ['required', 'string', 'email', 'max:255', 'unique:users,email', new NotBanned],
            'password'          => ['required', 'string', 'min:8', 'confirmed'],
            'birth_year'        => ['required', 'integer', 'between:1950,'.intVal(date('Y')-18)],
            'profile-picture'   => ['required','file','image','max:10000', 'mimes:jpeg,png,jpg,gif,svg'],
            'termsOfService'    => ['accepted']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        if (isset($data['profile-picture'])) {
            $filename = hash_file('haval160,4',$data['profile-picture']->getPathname()).'.'.$data['profile-picture']->getClientOriginalExtension();
            $otherUser = User::where('picture',$filename)->orWhere('picture',$filename)->first();

            if (!$otherUser) {
            
                $data['profile-picture']->move(public_path('img/profile-pictures/'), $filename);
                $data['profile-picture'] = $filename;
    
                $admins = User::where('is_admin','=',1)->get();
    
                if($admins){
                    Notification::send($admins, new NewProfilePicture($data['name'],$data['profile-picture']));
                }
            
                return User::create([
                    'name'                  => $data['name'],
                    'email'                 => $data['email'],
                    'password'              => Hash::make($data['password']),
                    'api_token'             => Str::random(60),
                    'birth_year'            => $data['birth_year'],
                    'pending_picture'       => $data['profile-picture']
                ]);
            }
        }
    }
}
