<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
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

        return view('customers.customers', ['token' => auth()->tokenById(Auth::user()->id)]);
    }

    public function get(Request $request)
    {
        if ($request->input('search')) {
            $result = Customer::where('name', 'LIKE', "%{$request->input('search')}%")
                                ->orWhere('document', $request->input('search'))->get();
        } else {
            $result = Customer::orderBy('id','DESC')->get();
        }

        return response()->json(['customers' => $result]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->input(), [
            'name' => ['required', 'string', 'max:255'],
            'document' => ['required', 'string', 'max:20', 'unique:customers'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:customers'],
            'address' => ['required', 'string', 'max:255']
        ]);

        $customer = [];
        if (!$validator->fails()) {
            $customer = Customer::create([
                'name' => $request->input('name'),
                'document' => $request->input('document'),
                'email' => $request->input('email'),
                'address' => $request->input('address')
            ]);
        }
            
        return response()->json(['customer' => $customer, 'errors' => $validator->errors()]);
    }

    public function update($id, Request $request)
    {
        $customer = Customer::find($id);
        $result = [];
        if ($customer) {
            $validator = Validator::make($request->input(), [
                'name' => ['required', 'string', 'max:255'],
                'document' => ['required', 'string', 'max:20', 'unique:customers,document,' . $customer->id],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:customers,email,' . $customer->id],
                'address' => ['required', 'string', 'max:255']
            ]);

            if (!$validator->fails()) {
                $result = $customer->update($request->input());
            }
        }
        
        return response()->json(['customer' => $customer, 'result' => $result, 'errors' => $validator->errors()]);
    }

    public function delete($id)
    {
        $customer = Customer::find($id);
        $result = [];
        if ($customer) {
            $result = $customer->delete();
        }
        
        return response()->json(['customer' => $customer, 'result' => $result]);
    }
}
