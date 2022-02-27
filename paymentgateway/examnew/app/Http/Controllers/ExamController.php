<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Question;
use App\Models\Result;
use App\Models\User;
use App\Models\UserExamAttempted;
use App\Models\QuestionAttemptedUsers;
use App\Models\CorrectAnswer;
use App\Models\FinalResult;
use App\Setting;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Session;

class ExamController extends BaseController
{
    public function show(Request $request,$id)
    {
        // $answerChecked = \App\Models\Result::where(['user_id' => \Illuminate\Support\Facades\Auth::user()->id,'question_id' => 9])->first();
        // dd($answerChecked);
        // Find the question, assuming the model is Question

        Result::updateOrCreate([
            'user_id' => Auth::user()->id,
            'question_id' => $request->input('question'),
        ],[
            'user_id' => Auth::user()->id,
            'question_id' => $request->input('question'),
            'answer_id'  => $request->input('answer'),
        ]);

        QuestionAttemptedUsers::updateOrCreate([
            'user_id' => Auth::user()->id,
            'question_id' => $request->input('question'),
        ],[
            'user_id' => Auth::user()->id,
            'question_id' => $request->input('question'),
        ]);

        $questionDecode = base64_decode($id);
        $language = Session::get('language');
        $age = \Illuminate\Support\Facades\Auth::user()->age;

        if($age <= 12)
        {
          $agegroup = 'Under 12 years';
        }else if($age >= 13 && $age <= 17)
        {
          $agegroup = '13 to 17 years';
        }else if($age >= 18 && $age <= 30)
        {
          $agegroup = '18 to 30 years';
        }else if($age >= 30)
        {
          $agegroup = 'Above 30 years';
        }

        $setting = Setting::where('key','competition')->first();
        $exam = Exam::where(['agegroup' => $agegroup,'language' => $language,'competition_id' => $setting->value])->first();
        $question = Question::whereExam($exam->id)->whereSerial($questionDecode)->first();



        if(is_null($question))
        {
            return redirect()->route('examFinal');
        }else{



            $answer = $question->answers()->get();
            $question_list = Question::whereExam($exam->id)->get();

            return view('Front.question', [
                'question' => $question ,
                'answer'   =>  $answer,
                'question_list' => $question_list,
                'exam' => $exam,
                'questionForward' => $question
            ]);
        }

    }
    public function examPreview(Request $request)
    {
        $profile = Auth::user();
        return view('Front.exam-preview',compact('profile'));
    }
    public  function exam(Request $request,$id)
    {
        $language = Session::get('language');
        $age = \Illuminate\Support\Facades\Auth::user()->age;

        if($age <= 12)
        {
          $agegroup = 'Under 12 years';
        }else if($age >= 13 && $age <= 17)
        {
          $agegroup = '13 to 17 years';
        }else if($age >= 18 && $age <= 30)
        {
          $agegroup = '18 to 30 years';
        }else if($age >= 30)
        {
          $agegroup = 'Above 30 years';
        }

        $setting = Setting::where('key','competition')->first();
        $exam = Exam::where(['agegroup' => $agegroup,'language' => $language,'competition_id' => $setting->value])->first();
        $request->session()->put('examId', $exam->getKey());

        $question = Question::whereExam($exam->id)->first();
        // $questionForward = Question::whereExam($exam->id)->get();

        $answer = $question->answers()->get();
        $question_list = Question::whereExam($exam->id)->get();


        return view('Front.question', [
            'question' => $question ,
            'answer'   =>  $answer,
            'question_list' => $question_list,
            'exam' => $exam,
            'questionForward' => $question
        ]);
    }

    public function examNotAttempted(Request $request,$id)
    {

        $language = Session::get('language');
        $age = \Illuminate\Support\Facades\Auth::user()->age;

        if($age <= 12)
        {
          $agegroup = 'Under 12 years';
        }else if($age >= 13 && $age <= 17)
        {
          $agegroup = '13 to 17 years';
        }else if($age >= 18 && $age <= 30)
        {
          $agegroup = '18 to 30 years';
        }else if($age >= 30)
        {
          $agegroup = 'Above 30 years';
        }
        $setting = Setting::where('key','competition')->first();
        $exam = Exam::where(['agegroup' => $agegroup,'language' => $language,'competition_id' => $setting->value])->first();

        $question = Question::whereId($id)->first();
        $request->session()->put('serial_id', $question->serial);

        // $questionForward = Question::whereExam($exam->id)->get();
        return redirect()->route('startQuiz');
    }
    public function user()
    {
        $setting = Setting::where('key','competition')->first();
        $attempted = UserExamAttempted::where(['user_id' => Auth::user()->getKey(),'competition_id' => $setting->value])->first();
        $profile = Auth::user();

        return view('Front.user',compact('attempted','profile'));
    }

    public function finalResultList()
    {
        $resultList = FinalResult::whereUserId(Auth::user()->getKey())->get();
        $profile = Auth::user();
        return view('Front.result-list',compact('resultList','profile'));
    }

    public function profile()
    {
        $profile = Auth::user();
        return view('Front.profile',compact('profile'));
    }

    public function instruction(Request $request)
    {
        $request->validate([
            'language' => 'required'
        ]);
        $language =$request->input('language');
        $age = \Illuminate\Support\Facades\Auth::user()->age;

        if($age <= 12)
        {
          $agegroup = 'Under 12 years';
        }else if($age >= 13 && $age <= 17)
        {
          $agegroup = '13 to 17 years';
        }else if($age >= 18 && $age <= 30)
        {
          $agegroup = '18 to 30 years';
        }else if($age >= 30)
        {
          $agegroup = 'Above 30 years';
        }

        $setting = Setting::where('key','competition')->first();
        $exam = Exam::where(['agegroup' => $agegroup,'language' => $language,'competition_id' => $setting->value])->first();

        if(isset($exam))
        {
            $request->session()->put('exam_id', $exam->id);
            $request->session()->put('competition_id', $exam->competition_id);
        }


        $request->session()->put('language', $request->input('language'));
        return view('Front.instruction',compact('exam'));
    }

    public function examFinal()
    {
        $userResultExist = FacadesDB::table('results')->whereUserId(Auth::user()->id)->exists();
        if($userResultExist != true)
        {
            return redirect()->back();
        }
        $language = Session::get('language');
        $age = \Illuminate\Support\Facades\Auth::user()->age;

        if($age <= 12)
        {
          $agegroup = 'Under 12 years';
        }else if($age >= 13 && $age <= 17)
        {
          $agegroup = '13 to 17 years';
        }else if($age >= 18 && $age <= 30)
        {
          $agegroup = '18 to 30 years';
        }else if($age >= 30)
        {
          $agegroup = 'Above 30 years';
        }

        $setting = Setting::where('key','competition')->first();
        $exam = Exam::where(['agegroup' => $agegroup,'language' => $language,'competition_id' => $setting->value])->first();
        $userExamAttempted = UserExamAttempted::where(['user_id' => Auth::user()->id,'exam_id' => session('exam_id'),'competition_id' => session('competition_id')])->first();

        $results = FacadesDB::table('results')->join('questionwithanswer','questionwithanswer.id','=','results.answer_id')
        ->join('question','question.id','=','results.question_id')->where(['results.user_id' => Auth::user()->id,'user_exam_attempted_id' => $userExamAttempted->getKey()])->get();
        $questions = Question::whereExam($exam->id)->get();


        session(['direct_review' => 1]);


        UserExamAttempted::updateOrCreate([
            'user_id' => Auth::user()->id,
            'exam_id' => $exam->id,
            'competition_id' => session('competition_id'),
        ],[
            'user_id' => Auth::user()->id,
            'competition_id' => session('competition_id'),
            'exam_id' => $exam->id,
            'status' => 'attempted'
        ]);


        return view('Front.review', [
            'results' => $results ,
            'exam' => $exam,
            'questions' => $questions

        ]);
    }

    public function final()
    {
        $userExamAttempted = UserExamAttempted::where(['user_id' => Auth::user()->id,'exam_id' => session('exam_id'),'competition_id' => session('competition_id')])->first();
        $userResults = Result::where(['user_id' => Auth::user()->id,'exam_id' => session('exam_id'),'user_exam_attempted_id' =>  $userExamAttempted->getKey()])->get();
        $correctQuestion = 0;
        $marks = 0;

        foreach($userResults as $key => $userResult)
        {
            $finalkey = $key + 1;
            $question = Question::find($userResult->question_id);
            $correctAnswerFound = CorrectAnswer::whereQuestionId($userResult->question_id)->first();
            if(@$correctAnswerFound->answer_id == $userResult->answer_id)
            {
                $correctQuestion += 1;
                $marks += $question->marks;
            }
        }

        FinalResult::create([
                'user_id' => Auth::user()->id,
                'user_exam_attempted_id' => $userExamAttempted->getKey(),
                'exam_id' => session('exam_id'),
                'total_mark' => $marks,
                'correct_question' => $correctQuestion
            ]);
        session()->forget('serial_id');
        session()->forget('direct_review');
        session()->forget('question_array');

        Auth::logout();
        return view('Front.final');
    }


    public function TimeOutfinal()
    {
        $userExamAttempted = UserExamAttempted::where(['user_id' => Auth::user()->id,'exam_id' => session('exam_id'),'competition_id' => session('competition_id')])->first();
        $userResults = Result::where(['user_id' => Auth::user()->id,'exam_id' => session('exam_id'),'user_exam_attempted_id' =>  $userExamAttempted->getKey()])->get();
        $correctQuestion = 0;
        $marks = 0;
        foreach($userResults as $key => $userResult)
        {
            $finalkey = $key + 1;
            $question = Question::find($userResult->question_id);
            $correctAnswerFound = CorrectAnswer::whereQuestionId($userResult->question_id)->first();
            if($correctAnswerFound->answer_id == $userResult->answer_id)
            {
                $correctQuestion += 1;
                $marks += $question->marks;
            }
        }
        UserExamAttempted::updateOrCreate([
            'user_id' => Auth::user()->id,
            'exam_id' => session('exam_id'),
            'competition_id' => session('competition_id'),
        ],[
            'user_id' => Auth::user()->id,
            'competition_id' => session('competition_id'),
            'exam_id' => session('exam_id'),
            'status' => 'timeout'
        ]);

        FinalResult::create([
                'user_id' => Auth::user()->id,
                'user_exam_attempted_id' => $userExamAttempted->getKey(),
                'exam_id' => session('exam_id'),
                'total_mark' => $marks,
                'correct_question' => $correctQuestion
            ]);

        session()->forget('serial_id');
        session()->forget('direct_review');
        session()->forget('question_array');

        Auth::logout();

        return view('Front.final');
    }

    public function userLogout()
    {

        $userExamAttempted = UserExamAttempted::where(['user_id' => Auth::user()->id,'exam_id' => session('exam_id'),'competition_id' => session('competition_id')])->first();
        $userResults = Result::where(['user_id' => Auth::user()->id,'exam_id' => session('exam_id'),'user_exam_attempted_id' =>  $userExamAttempted->getKey()])->get();
        $correctQuestion = 0;
        $marks = 0;
        foreach($userResults as $key => $userResult)
        {
            $finalkey = $key + 1;
            $question = Question::find($userResult->question_id);
            $correctAnswerFound = CorrectAnswer::whereQuestionId($userResult->question_id)->first();
            if($correctAnswerFound->answer_id == $userResult->answer_id)
            {
                $correctQuestion += 1;
                $marks += $question->marks;
            }
        }

        FinalResult::create([
                'user_id' => Auth::user()->id,
                'user_exam_attempted_id' => $userExamAttempted->getKey(),
                'total_mark' => $marks,
                'exam_id' => session('exam_id'),
                'correct_question' => $correctQuestion
            ]);

        $language = Session::get('language');
        $agegroup = Auth::user()->agegroup;
        $exam = Exam::where(['agegroup' => $agegroup,'language' => $language])->first();

        UserExamAttempted::updateOrCreate([
            'user_id' => Auth::user()->id,
            'exam_id' => $exam->id,
            'competition_id' => session('competition_id'),
        ],[
            'user_id' => Auth::user()->id,
            'competition_id' => session('competition_id'),
            'exam_id' => $exam->id,
            'status' => 'attempted'
        ]);

        session()->forget('serial_id');
        session()->forget('direct_review');
        session()->forget('question_array');

        Auth::logout();
        return view('Front.final');
    }

    public function updateNumber()
    {
        $userOnline = User::get();
        foreach($userOnline as $key =>  $user)
        {
            if($key > 0)
            {
                $firstUser =  User::where('hallticket', '!=' ,'')->orderBy('id','desc')->first();

                $number = $firstUser->hallticket + 1;
                $update = User::whereId($user->id)->update(['hallticket' => $number]);
            }


        }

    }

}
