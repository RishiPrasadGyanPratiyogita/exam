<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;

class ApiUserController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->all();

        User::create($data);

        return true;
    }
}
