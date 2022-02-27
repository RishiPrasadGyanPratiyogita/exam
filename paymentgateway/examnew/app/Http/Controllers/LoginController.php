<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\AuditLog;

class LoginController extends BaseController
{

    public function login()
    {
        Auth::logout();
        if(Auth::user())
        {
            return redirect()->route('user');
        }

        return view('Front.login');
    }
    public function handleLogin(Request $request)
    {
        $request->validate([
            'mobile' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('mobile', 'password');
        $user = User::where(['mobile' => $request->input('mobile'),'password' => $request->input('password')])->first();

        if(!is_null($user))
        {
        	if (Auth::loginUsingId($user->getKey())) {
	            if (Auth::user()->id) {
                    $mac = shell_exec('getmac');
                    $macAddress = strtok($mac, ' ');

                    AuditLog::updateOrCreate([
                        'user_id' => Auth::user()->id,

                    ],[
                        'user_id' => Auth::user()->id,
                        'ip_address' => $request->ip(),
                        'mac_address' => $macAddress,
                    ]);

	                return redirect()->route('user');
	            }else
	            {

	                return redirect()->route('user.login');
	            }
        	}
        	return redirect()->route('user.login')->with('error', 'Woops! You have entered invalid credentials');
        }else
        {
        	return redirect()->route('user.login')->with('error', 'Woops! You have entered invalid credentials');
        }


    }

    public function loginDOB(Request $request)
    {
        $type = $request->type;
        return view('Front.login',compact('type'));
    }

    public function handleLoginDOB(Request $request)
    {
        $request->validate([
            'mobile' => 'required',
            'dob' => 'required',
        ]);

        $credentials = User::where(['mobile' => $request->input('mobile'),'dob' => date('Y-m-d',strtotime($request->input('dob')))])->first();

        if(is_null($credentials))
        {
            return redirect()->route('user.loginDOB','dob')->with('error', 'Woops! You have entered invalid credentials');
        }

        $user = User::where(['mobile' => $request->input('mobile'),'password' => $credentials->password])->first();

        if(!is_null($user))
        {
            if (Auth::loginUsingId($user->getKey())) {
                if (Auth::user()->id) {
                    $mac = shell_exec('getmac');
                    $macAddress = strtok($mac, ' ');


                    AuditLog::updateOrCreate([
                        'user_id' => Auth::user()->id,

                    ],[
                        'user_id' => Auth::user()->id,
                        'ip_address' => $request->ip(),
                        'mac_address' => $macAddress,
                    ]);

                    return redirect()->route('user');
                }else
                {
                    return redirect()->route('user.loginDOB','dob');
                }
            }
            return redirect()->route('user.loginDOB','dob')->with('error', 'Woops! You have entered invalid credentials');
        }else
        {
            return redirect()->route('user.loginDOB','dob')->with('error', 'Woops! You have entered invalid credentials');
        }


    }

    public function logout()
    {
        session()->forget('serial_id');
        Auth::logout();
        return redirect()->route('user.login');
    }

    public function forgetpassword()
    {

        return view('Front.forgetpassword');
    }

    public function forgetPasswordLink(Request $request)
    {
        $user = User::where(['mobile' => $request->input('mobile')])->first();

        if(!is_null($user))
        {
            $messge = "Hariom!
".$user->name."
Participate in RPGP Exam
Your Mob. No. ".$user->mobile." and Password: ".$user->password."
https://exam.rishiprasad.org/
By Rishi Prasad";

                $crl = curl_init();
                $url = 'http://login.aquasms.com/sendSMS?username=9106897629&message='.urlencode($messge).'&sendername=RPGPEX&smstype=TRANS&numbers='.$request->input('mobile').'&apikey=08d7f126-758a-4e61-b481-b403b202d03a';
                curl_setopt($crl, CURLOPT_URL, $url);
                curl_setopt($crl, CURLOPT_FRESH_CONNECT, true);
                curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($crl);

                if(!$response){
                  die('Error: "' . curl_error($crl) . '" - Code: ' . curl_errno($crl));
                }else
                {
                    // echo 1;
                }
                curl_close($crl);

            return redirect()->route('user.forgetpassword')->with('message','Login credentials sent to your registered mobile number');
        }else
        {
            return redirect()->route('user.forgetpassword')->with('error', 'Woops! You have entered mobile not register with our portal');
        }

    }

}
