<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $conversations =  \App\Models\Message::where('from_id', auth()->id())
            ->orWhere('to_id', auth()->id())
            ->orderBy('created_at','desc')
            ->get();
        $users = $conversations->map(function($conversation){
            if($conversation->from_id == auth()->id()) {
                return \App\Models\User::find($conversation->to_id);//$conversation->uxarkox;
            }

            return \App\Models\User::find($conversation->from_id);
        })->unique();
//        $users = collect($users)->reverse();

//        $users = \App\Models\User::whereIn('id',array_reverse($users->toArray()))->get();

        return view(theme('new.message'), compact('users'));
    }
}
