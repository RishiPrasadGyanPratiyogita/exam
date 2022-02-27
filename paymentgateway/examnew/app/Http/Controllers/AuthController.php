<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use DB;
Use Auth;
use App\Models\Admin;

class AuthController extends BaseController
{

    public function createRegister()
    {
      return view('registerdata');
    }
    public function storeRegister(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'password' => 'required',
        ]);
        
        $admin = Admin::create($data);

        
        return redirect()->route('registerdata')->with('message','Registration Done Successfully');
    }

    public function createLogin()
    {
      return view('logindata');
    }
    public function createauthenticate(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->intended('/home');
        }
        return redirect('storelogin')->with('error', 'Oppes! You have entered invalid credentials');
    }
    public function createlogout() {
        Auth::logout();
  
        return redirect('logindata');
      }

    
}
