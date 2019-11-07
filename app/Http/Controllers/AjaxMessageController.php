<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\User;
use Nahid\Talk\Facades\Talk;
use App\Events\MessagesWereSeen;
use App\Notifications\NewMessage;

class AjaxMessageController extends Controller
{

    public function __construct()
    {
        $this->middleware('verified');
    }

    public function ajaxGetMessage(Request $request, $id)
    {
        if ($request->ajax()) {
            if ($message = Talk::user(Auth::id())->readMessage($id)) {
                $html = view('ajax.newMessageHtml', compact('message'))->render();
                return response()->json(['status'=>'success', 'html'=>$html], 200);
                
            }
        }
    }
    
    public function ajaxSendMessage(Request $request)
    {
        if ($request->ajax()) {
            $rules = [
                'message-data'=>['required_without:pictures','string','nullable'],
                'pictures.*'  =>['required_without:message-data','file','image','max:2000', 'mimes:jpeg,png,jpg,gif,svg'],
                '_id'=>'required'
            ];

            $this->validate($request, $rules);

            $body = (e($request->input('message-data')) == "") ? null : e($request->input('message-data')) ;
            $userId = $request->input('_id');
            $pictures = $request->file('pictures');
            $pictures_json = null;

            if($pictures){
                $pictures_json = array();
                foreach ($pictures as $picture) {
                    $imageName = hash_file('haval160,4',$picture->getPathname()).'.'.$picture->getClientOriginalExtension();
                    $picture->move(public_path('img/message-pictures'), $imageName);
                    $pictures_json[] = $imageName;
                }
                $pictures_json = json_encode($pictures_json);
            }

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

                if ($message = Talk::sendMessageByUserId($userId, $body, $pictures_json)) {
                    User::find($userId)->notify(new NewMessage(Auth::id(),$body,isset($pictures_json)));
                    $html = view('ajax.newMessageHtml', compact('message'))->render();
                    $threads = Talk::getInbox('desc',0,1);
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
                    event(new MessagesWereSeen(intVal($sender_id), intVal($conversation_id)));
                }
                $notifications = Auth::user()->notifications()->where('type', 'App\Notifications\NewMessage')->get();
                foreach ($notifications as $notification) {
                    if($notification->data['sender_id'] === intVal($sender_id)){
                        $notification->delete();
                    }
                }
                return response()->json(['status'=>'success', 'seen_messages' => $amount], 200);

            }else{
                if(Talk::user(Auth::id())->makeSeen($id)) {
                    $sender_id = $request->input('sender');
                    $conversation_id = Talk::user(Auth::id())->isConversationExists($sender_id);
                    
                    event(new MessagesWereSeen(intVal($sender_id), intVal($conversation_id)));
                    $notifications = Auth::user()->notifications()->where('type', 'App\Notifications\NewMessage')->get();
                    foreach ($notifications as $notification) {
                        if($notification->data['sender_id'] === intVal($sender_id)){
                            $notification->delete();
                        }
                    }

                    return response()->json(['status'=>'success'], 200);
                }
            }

            return response()->json(['status'=>'errors', 'msg'=>'something went wrong'], 401);
        }
    }

    public function pagiConversations(Request $request, $pagi)
    {
        if ($request->ajax()) {
            if($threads = Talk::user(Auth::id())->getInbox('desc',10*$pagi,20*$pagi)){
               $html = view('ajax.newThreadHtml', compact('threads'))->render();
               if (count($threads) < 10) {
                    return response()->json(['status'=>'success','html'=>$html, 'stop' => true],200);  
               }else{
                    return response()->json(['status'=>'success','html'=>$html, 'stop' => false],200); 
               }
            }else{
                return response()->json(['status'=>'errors', 'msg'=>'something went wrong'], 401);
            }
             
        }
        
    }
}
