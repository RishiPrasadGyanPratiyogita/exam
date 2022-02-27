<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\QuestionAttemptedUsers;
use App\Models\UserExamAttempted;
use App\Models\Result;
use App\Setting;

class SettingController extends BaseController
{
    public function testingUserExam(Request $request)
    {
        $user_id = $request->testing_user;

        QuestionAttemptedUsers::whereUserId($user_id)->delete();
        UserExamAttempted::whereUserId($user_id)->delete();
        Result::whereUserId($user_id)->delete();

        return redirect()->route('testing-setting')->with('message','User Exam Set Successfully');
    }

    public function testingUserExamView()
    {
        return view('testing-setting');
    }

    /*************** Front setting controller ********************* */
    public function websiteSetting()
    {
      return view('website-setting');
    }
    public function pageSetting()
    {
      return view('page-setting');
    }
    public function socialSetting()
    {
      return view('social-setting');
    }

    public function competitionSetting ()
    {
        $competitions = Competition::get();
        $setting = Setting::where('key','competition')->first();

        return view('competition-setting',compact('competitions','setting'));
    }

    public function competitionSettingStore(Request $request)
    {
        $data = $request->all();

        Setting::updateOrCreate(['key' => 'competition'],['key' => 'competition','value' => $request->competition]);

        return redirect()->back()->with('message','Competition Set Successfully');
    }

}
