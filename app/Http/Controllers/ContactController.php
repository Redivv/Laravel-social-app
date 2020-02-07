<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use Auth;

use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        $admins = User::where('is_admin',1)->get();
        return view('contact')->withAdmins($admins);
    }

    public function sendMail(Request $request)
    {
        $request->validate([
            'EmailSubject'        => ['required','string','max:255'],
            'EmailContent'        => ['required','string'],
            'EmailAttachments.*'  => ['file','image','max:5000','mimes:jpeg,png,jpg,gif,svg'],
        ]);

        $title      = $request->EmailSubject;
        $content    = $request->EmailContent;
        $pictures   = $request->file('EmailAttachments');
        $readyPictures = array();
        $user = Auth::user();

        if($pictures){
            foreach ($pictures as $key => $picture) {
                $imageName = hash_file('haval160,4',$picture->getPathname()).'.'.$picture->getClientOriginalExtension();
                $picture->move(public_path('img/email-pictures'), $imageName);

                $readyPictures[$key]['name'] = $imageName;
                $readyPictures[$key]['extension'] = $picture->getClientOriginalExtension();
            }
        }

        Mail::send('mail.UserMessage', ['content' => $content, 'user' => $user->name], function ($message) use ($title,$user,$readyPictures)
        {

            $message->subject($title);

            $message->from($user->email,$user->name);
            $message->replyTo($user->email);
            $message->sender($user->email);
            $message->to(env('MAIL_FROM_ADDRESS','administracja@safo.com.pl'));

            foreach ($readyPictures as $key => $picture) {
                $message->attach(public_path("img/email-pictures/".$picture['name']),[
                    'as'    => 'screen'.$key.".".$picture['extension']
                ]);
            }

        });
        

        $request->session()->flash('message', __('contact.mailSent'));
        return redirect(route('ContactPage'));


    }
}
