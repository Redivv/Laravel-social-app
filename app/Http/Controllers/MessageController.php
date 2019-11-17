<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

use App\User;
use Nahid\Talk\Facades\Talk;
use App\Events\MessagesWereSeen;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('verified');
    }

    public function index()
    {
        $offlineTimer = Carbon::now()->subMinutes(30)->toDateTimeString();
        User::where('status','online')->where('updated_at','<',$offlineTimer)->update(['status' => 'offline', 'updated_at' => Carbon::now()->toDateTimeString()]);
        
        $threads = Talk::user(Auth::id())->getInbox();
        $user = null;
        $messages = [];
        return view('messages.conversations', compact('messages', 'user', 'threads'))->withSender(null);
    }

    public function chatHistory(Request $request, $name)
    {
        $id =  User::where('name', $name)->first()->id;
        if($request->ajax()){
            $pagi = $request->input('pagi');
            if($conversations = Talk::user(Auth::id())->getMessagesByUserId($id, 10*$pagi,20*$pagi)){
               $messages = $conversations->messages;
               $html = view('ajax.newMessagesHtml', compact('messages'))->render();
               if(count($messages) < 10){
                    return response()->json(['status'=>'success','html'=>$html, 'stop' => true],200);     
               }else{
                return response()->json(['status'=>'success','html'=>$html, 'stop' => false],200); 
               }
            }else{
                return response()->json(['status'=>'errors', 'msg'=>'something went wrong'], 401);
            }

        }else{
            $threads = Talk::user(Auth::id())->getInbox('desc',0,10);

            $user = null;
            $messages = [];
            if ($id != Auth::id()) {
                $conversation_id = Talk::isConversationExists($id);
                $amount = DB::table('messages')
                    ->where('conversation_id', $conversation_id)
                    ->where('user_id',$id)
                    ->update(['is_seen'=>1]);
                if ($amount > 0) {
                    $threads = Talk::user(Auth::id())->getInbox();
                    event(new MessagesWereSeen(intVal($id), intVal($conversation_id)));
                }
                
                $conversations = Talk::user(Auth::id())->getMessagesByUserId($id, 0,10);
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

            return view('messages.conversations', compact('messages', 'user', 'threads'))->withSender($id);
        }
    }

    public function deleteConversation($id)
    {
        if ($conversation_id = Talk::user(Auth::id())->isConversationExists($id)) {
            Talk::deleteConversations($conversation_id);
            return redirect()->route('message.app');
        }
    }

    public function blockConversation($id)
    {
        if ($conversation_id = Talk::user(Auth::id())->isConversationExists($id)) {
            $conversation_status = DB::table('conversations')->select('status')
                    ->where('id', $conversation_id)
                    ->get();
            if ($conversation_status[0]->status == 1) {
                DB::table('conversations')
                    ->where('id', $conversation_id)
                    ->update(['status' => 0,'block_id'=>Auth::id()]);
                    return redirect()->route('message.app');
            }else{
                DB::table('conversations')
                    ->where('id', $conversation_id)
                    ->where('block_id', Auth::id())
                    ->update(['status' => 1,'block_id'=>null]);
                $name = User::find($id)->name;
                return redirect()->route('message.read',['name'=>$name]);
            }
            
        }
    }
}
