<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function login()
    {
        return view('login');
    }

    public function handleLogin(Request $req)
    {
        if(Auth::guard('webadmin')
               ->attempt($req->only(['email', 'password'])))
        {

            return redirect()
                ->route('home');
        }

        return redirect()
            ->back()
            ->with('error', 'Invalid Credentials');
    }

    public function logout()
    {
        Auth::guard('webadmin')
            ->logout();

        return redirect()
            ->route('admin.login');
    }
}
?>
