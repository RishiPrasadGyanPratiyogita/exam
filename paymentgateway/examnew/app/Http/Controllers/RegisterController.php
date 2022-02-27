<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use DB;
use App\Models\User;

class RegisterController extends BaseController
{

    public function createRegister()
    {
      return view('Front.register');
    }
    public function storeRegister(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'password' => 'required',
        ]);
        $data['password'] = bcrypt($request->input('password'));
        
        $user = User::create($data);

        
        return redirect()->route('register')->with('message','Registration Done Successfully');
    }

    
}
