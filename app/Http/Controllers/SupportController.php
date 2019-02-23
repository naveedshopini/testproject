<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Ticket;
use Auth;
use DateTime;
use Session;
use App\Comment;
use App\User;
use DB;
class SupportController extends Controller
{
    
    public function __construct()
    {
       $this->middleware('auth');
    }

    public function index()
    {	
    	$user_id = Auth::user()->id;
    	$tickets = Ticket::where('user_id',$user_id)->get();

    	$active = Ticket::where(['user_id'=>$user_id,'status'=>0])->count();
    	$complete = Ticket::where(['user_id'=>$user_id,'status'=>1])->count();

    	return view('front.pages.tickets',[
    		'tickets' => $tickets,
    		'total_active'=>$active,
    		'total_complete'=>$complete,
    		'calculator_heading'=>'no',
    		'currency_heading'=>'no'
    	]);
    }
    public function create_ticket()
    {	

    	return view('front.pages.create_ticket',['calculator_heading'=>'no',
    		'currency_heading'=>'no']);
    }

    public function ticket_number()
    {
    	$pin =  generatePIN(6);
    	$tickets = Ticket::where('id',$pin)->count();
    	
    	if ($tickets > 0) {
    		
    		$this->ticket_number();			
    	}
    	else
    	{
    		return $pin;
    	}	
    }

    public function save_ticket(Request $req)
    {	
    	$user_id = Auth::user()->id;
    	$ticketNumber =  $this->ticket_number();

    	$validator = Validator::make($req->all(), [
            'subject'     => 'required|min:10|max:150',
            'description'   => 'required',
            'priority'      => 'required',
            'category'      => 'required',
        ]); 

        if ($validator->fails()) {

            return redirect('support/ticket/create')->withErrors($validator)->withInput();
        }

        Ticket::create([

        	'ticket_id'		=> $ticketNumber,
        	'user_id'		=> $user_id,
            'subject' 		=> $req->subject,
            'description' 	=> $req->description,
            'priority'		=> $req->priority,
            'category'		=> $req->category,
            'owner'			=> Auth::user()->fname.' '.Auth::user()->lname,
            'status'		=> 0,  // 0 = pending & 1 = solve
            'agent'			=>'admin'

        ]);

        Session::flash('success' , __('alerts.frontend.support.ticket_created') );
        return  redirect('support/tickets');
    }

    public function ticket_comments($id)
    {	
    	$user_id = Auth::user()->id;
    	$tickets = Ticket::where('ticket_id',$id)->first();
    	$comments = Comment::where('ticket_id',$id)->get();
    	$active = Ticket::where(['user_id'=>$user_id,'status'=>0])->count();
    	$complete = Ticket::where(['user_id'=>$user_id,'status'=>1])->count();

    	return view('front.pages.ticket_comments',[
    		'tickets'=>$tickets,
    		'comments'=>$comments,
    		'total_active'=>$active,
    		'total_complete'=>$complete,
    		'calculator_heading'=>'no',
    		'currency_heading'=>'no'
    	]);
    }
    public function reply_comment(Request $request)
    {	
    	$reply = $request->reply;
    	$ticket_id = $request->ticket_id;
    	$user_id = Auth::user()->id;

    	$validator = Validator::make($request->all(), [
            'reply'     => 'required',
        ]); 

        if ($validator->fails()) {

            return redirect('support/ticket/comments/'.$ticket_id.'')->withErrors($validator)->withInput();
        }


        Comment::create([

        	'ticket_id'		=> $ticket_id,
        	'user_id'		=> $user_id,
            'description' 	=> $reply,
            'status'		=> 1,  // 0 = pending & 1 = solve
        
        ]);

        Ticket::where('ticket_id',$ticket_id)->update(['ticket_id'=>$ticket_id]);

        Session::flash('success_comment' , __('alerts.frontend.support.ticket_comment') );
        return  redirect('support/ticket/comments/'.$ticket_id.'');
    }
    public function mark_complete($id)
    {	
    	Ticket::where('ticket_id',$id)->update(['status'=>1]);
    	Session::flash('ticket_completed' , __('alerts.frontend.support.ticket_completed') );
    	return  redirect('support/tickets');
    }

    public function re_open($id)
    {	
    	Ticket::where('ticket_id',$id)->update(['status'=>0]);
    	Session::flash('ticket_completed' , __('alerts.frontend.support.ticket_reopen') );
    	return  redirect('support/tickets');
    }
}