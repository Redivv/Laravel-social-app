<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\User;
use Nahid\Talk\Facades\Talk;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function chatHistory($id)
    {
        $threads = Talk::user(Auth::id())->getInbox();

        $user = null;
        $messages = [];
        if ($id != Auth::id()) {
            $conversations = Talk::getMessagesByUserId($id, 0, 100);
            if(!$conversations) {
                $user = User::find($id);
            } else {
                $user = $conversations->withUser;
                $messages = $conversations->messages;
            }

            if (count($messages) > 0) {
                $messages = $messages->sortBy('id');
            }
        }

        return view('messages.conversations', compact('messages', 'user', 'threads'));
    }

    public function ajaxSendMessage(Request $request)
    {
        if ($request->ajax()) {
            
            $rules = [
                'message-data'=>'required',
                '_id'=>'required'
            ];

            $this->validate($request, $rules);

            $body = $request->input('message-data');
            $userId = $request->input('_id');


            if($userId == Auth::id()){
                return response()->json(['status'=>'errors', 'msg'=>'something went wrong'], 401);
            }
            
            if ($message = Talk::user(Auth::id())->sendMessageByUserId($userId, $body)) {
                $lel = compact('message');
                $html = view('ajax.newMessageHtml', compact('message'))->render();
                $threads = Talk::user(Auth::id())->getInbox('desc',0,1);
                $html2 = view('ajax.newThreadHtml', compact('threads'))->render();
                return response()->json(['status'=>'success', 'html'=>$html, 'html2'=>$html2, 'receiver_id'=>$userId], 200);
            }
        }
    }

    public function ajaxDeleteMessage(Request $request, $id)
    {
        if ($request->ajax()) {
            if(Talk::user(Auth::id())->deleteMessage($id)) {
                return response()->json(['status'=>'success'], 200);
            }

            return response()->json(['status'=>'errors', 'msg'=>'something went wrong'], 401);
        }
    }
}
