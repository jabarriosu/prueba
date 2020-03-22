<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.verify');
    }

    public function show()
    {
        if (Auth::user()) {
            $current_token = (String)\JWTAuth::parseToken()->getToken();
            DB::table('tokens_users')->where('user_id', Auth::user()->id)->where('token', '!=', $current_token)->delete();
        }

        return view('users.users', ['token' => auth()->tokenById(Auth::user()->id)]);
    }

    public function get(Request $request)
    {
        if ($request->input('search')) {
            $result = User::where('name', 'LIKE', "%{$request->input('search')}%")->get();
        } else {
            $result = User::all();
        }

        return response()->json(['users' => $result, 'roles' => $this->getRolUsers($result)]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->input(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        
        $user = [];
        if (!$validator->fails()) {
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password'))]);

            if ($user && $request->input('rol')) {
                $user->assignRole($request->input('rol'));
            }
        }
            
        return response()->json(['user' => $user, 'errors' => $validator->errors()]);
    }

    public function update($id, Request $request)
    {
        $user = User::find($id);
        $result = [];
        if ($user) {
            $result = $user->update(['name' => $request->input('name'), 'email' => $request->input('email'), 'password' => Hash::make($request->input('password'))]);

            if ($result && $request->input('rol')) {
                $user->assignRole($request->input('rol'));
            }
        }
        
        return response()->json(['user' => $user, 'result' => $result]);
    }

    public function delete($id)
    {
        $user = User::find($id);
        $result = [];
        if ($user) {
            $result = $user->delete();
        }
        
        return response()->json(['user' => $user, 'result' => $result]);
    }

    private function getRolUsers(Collection $users)
    {
        $roles = [];
        foreach ($users as $user) {
            $roles[$user->id] = $user->getRoleNames()->implode(', ');
        }

        return $roles;
    }
}
