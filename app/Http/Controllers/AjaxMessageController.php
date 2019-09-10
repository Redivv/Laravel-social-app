<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\User;
use Nahid\Talk\Facades\Talk;
use App\Events\MessagesWereSeen;

class AjaxMessageController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
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
            Talk::setAuthUserId(Auth::id());
            
            if($conversation_id = Talk::isConversationExists($userId)){
                $conversation_status = DB::table('conversations')->select('status')
                    ->where('id', $conversation_id)
                    ->get();
                if ($conversation_status[0]->status == 0) {
                    return response()->json(['status'=>'blocked-user', 'msg'=>__('chat.convoBlocked')], 400);
                }elseif($userId == Auth::id()){
                    return response()->json(['status'=>'errors', 'msg'=>'something went wrong'], 400);
                }
            }
                
                if ($message = Talk::sendMessageByUserId($userId, $body)) {
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

    public function ajaxSeenMessage(Request $request, $id)
    {
        if ($request->ajax()) {
            if($id == 0){
                $sender_id = $request->input('sender');
                $conversation_id = Talk::user(Auth::id())->isConversationExists($sender_id);
                $amount = DB::table('messages')
                ->where('conversation_id', $conversation_id)
                ->where('user_id',$sender_id)
                ->update(['is_seen'=>1]);
                if ($amount > 0) {
                    event(new MessagesWereSeen(intVal($sender_id)));
                }
                return response()->json(['status'=>'success', 'seen_messages' => $amount], 200);

            }else{
                if(Talk::user(Auth::id())->makeSeen($id)) {
                    $sender_id = $request->input('sender');
                    event(new MessagesWereSeen(intVal($sender_id)));

                    return response()->json(['status'=>'success'], 200);
                }
            }

            return response()->json(['status'=>'errors', 'msg'=>'something went wrong'], 401);
        }
    }

    public function ajaxGetMore(Request $request)
    {
        if($request->ajax()){
            return response()->json(['status'=>'success'],200);
        }
    }
}
