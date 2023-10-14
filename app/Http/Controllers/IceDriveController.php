<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IceDriveController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function api($type,$data){
        if($type == 'POST'){
            return \Illuminate\Support\Facades\Http::asForm()->post('https://icedrive.net/API/Internal/V1/',$data)->json();
        }else{
            return \Illuminate\Support\Facades\Http::asForm()->get('https://icedrive.net/API/Internal/V1/',$data)->json();
        }
    }

    public function user_data(){
        return $this->api('get',[
            'request' => 'user-data'
        ]);
    }

    public function login(){
        return $this->api('POST',[
            'request' => 'login',
            'email' => 'artoorsdesign@gmail.com',
            'password' => 'ByvaNBFx4UV8in3',
        ]);


    }


}
