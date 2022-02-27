<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\UserExamAttempted;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index(Request $request)
    {
        $questions = Question::whereExam(Session::get('exam_id'))->with('answersArray')->get();

        $exam =  Exam::whereId(Session::get('exam_id'))->first();
        if(session('serial_id') == '')
        {
            $request->session()->put('serial_id', 1);
        }

        UserExamAttempted::updateOrCreate([
            'user_id' => Auth::user()->id,
            'exam_id' => $exam->id,
            'competition_id' => $exam->competition_id,
        ],[
            'user_id' => Auth::user()->id,
            'exam_id' => $exam->id,
            'competition_id' => $exam->competition_id,
            'status' => 'started'
        ]);

        return view('quiz.start',compact('questions','exam'));
    }
}
