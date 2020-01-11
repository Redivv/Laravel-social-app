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
            'EmailSubject'  => ['required','string','max:255'],
            'EmailContent'  => ['required','string'],
        ]);

        $title      = $request->EmailSubject;
        $content    = $request->EmailContent;
        $user = Auth::user();

        Mail::send('mail.UserMessage', ['content' => $content, 'user' => $user->name], function ($message) use ($title,$user)
        {

            $message->subject($title);

            $message->from($user->email,$user->name);
            $message->replyTo($user->email);
            $message->sender($user->email,$user->name);

            $message->to('info@safo.com.pl');

        });
        

        $request->session()->flash('message', __('contact.mailSent'));
        return redirect(route('ContactPage'));


    }
}
