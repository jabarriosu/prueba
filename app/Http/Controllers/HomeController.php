<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.verify');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::user()) {
            $current_token = (String)\JWTAuth::parseToken()->getToken();
            DB::table('tokens_users')->where('user_id', Auth::user()->id)->where('token', '!=', $current_token)->delete();
        }

        return view('home')->with(['user' => auth()->user()]);
    }
}
