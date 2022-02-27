<?php

namespace App\Http\Livewire;

use App\Models\Result;
use App\Models\QuestionAttemptedUsers;
use App\Models\UserExamAttempted;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session as FacadesSession;
use Livewire\Component;

class ExamQuiz extends Component
{
    public $questions;
    public $question_id;
    public $answer = NULL;
    public $questionCount;
    public $finalAnswers = [];
    public $insertFinalAnswers = [];

    protected $listeners = [
        'SubmitQuestion' ,'SetAnswer','TimeOut','DirectQuestion','DirectPreview'
    ];
    public function mount($questions)
    {
        $this->questions = $questions;
        $this->questionCount = count($questions);
        $this->answer = NULL;
        if(!is_null(session()->get('question_array')))
        {
            $this->finalAnswers = array_unique(session()->get('question_array'),SORT_REGULAR);

        }

    }

    public function SetAnswer($key,$answerFinal)
    {
        $this->answer = $answerFinal;
    }

    public function DirectQuestion($questionSerial)
    {
         session(['serial_id' => $questionSerial]);
    }

    public function TimeOut()
    {
        $userExamAttempted = UserExamAttempted::where(['user_id' => Auth::user()->id,'exam_id' => session('exam_id'),'competition_id' => session('competition_id')])->first();

        foreach($this->finalAnswers as $quiz)
        {

            Result::updateOrCreate([
                'user_id' => $quiz['user_id'],
                'question_id' => $quiz['question_id'],
            ],[
                'user_id' => $quiz['user_id'],
                'exam_id' => session('exam_id'),
                'user_exam_attempted_id' => $userExamAttempted->getKey(),
                'question_id' => $quiz['question_id'],
                'answer_id'  => $quiz['answer_id'],
            ]);
        }

        foreach($this->insertFinalAnswers as $insertQuiz)
        {
            QuestionAttemptedUsers::updateOrCreate([
                'user_id' => $insertQuiz['user_id'],
                'question_id' => $insertQuiz['question_id'],
            ],[
                'user_id' => $insertQuiz['user_id'],
                'question_id' => $insertQuiz['question_id'],

            ]);
        }

        return redirect()->route('TimeOutfinal');
    }

    public function DirectPreview($key,$question_id,$serial)
    {

        $questionAttemptedWithAnswer = \App\Models\Result::where(['user_id' => \Illuminate\Support\Facades\Auth::user()->id,
        'question_id' => $question_id])->first();
        if(isset($questionAttemptedWithAnswer->answer_id))
        {
            $this->answer = $questionAttemptedWithAnswer->answer_id;
        }

        $userExamAttempted = UserExamAttempted::where(['user_id' => Auth::user()->id,'exam_id' => session('exam_id'),'competition_id' => session('competition_id')])->first();

        Result::updateOrCreate([
            'user_id' => Auth::user()->getKey(),
            'question_id' => $question_id,
        ],[
            'user_id' => Auth::user()->getKey(),
            'exam_id' => session('exam_id'),
            'user_exam_attempted_id' => $userExamAttempted->getKey(),
            'question_id' => $question_id,
            'answer_id'  => $this->answer,
        ]);

        QuestionAttemptedUsers::updateOrCreate([
            'user_id' => Auth::user()->getKey(),
            'question_id' => $question_id,
        ],[
            'user_id' => Auth::user()->getKey(),
            'question_id' => $question_id,

        ]);


        return redirect()->route('examFinal');
    }
    public function SubmitQuestion($key,$question_id,$serial)
    {

        $questionAttemptedWithAnswer = \App\Models\Result::where(['user_id' => \Illuminate\Support\Facades\Auth::user()->id,
        'question_id' => $question_id])->first();
        if(isset($questionAttemptedWithAnswer->answer_id))
        {
            $this->answer = $questionAttemptedWithAnswer->answer_id;
        }

         $data = [
            'question_id' => $question_id,
            'answer_id' => $this->answer,
            'user_id' => Auth::user()->getKey(),
        ];

        if($data['answer_id'] != '')
        {
            array_push($this->finalAnswers,$data);
            session(['question_array' => $this->finalAnswers]);
        }

        $info = [
            'question_id' => $question_id,
            'user_id' => Auth::user()->getKey(),
        ];
        array_push($this->insertFinalAnswers,$info);



        if($serial < $this->questionCount){
            $nextQuestion = $serial + 1;
            session(['serial_id' => $nextQuestion]);
            $this->answer = NULL;
        }else{
            $userExamAttempted = UserExamAttempted::where(['user_id' => Auth::user()->id,'exam_id' => session('exam_id'),'competition_id' => session('competition_id')])->first();
            foreach($this->finalAnswers as $quiz)
            {
                Result::updateOrCreate([
                    'user_id' => $quiz['user_id'],
                    'question_id' => $quiz['question_id'],
                ],[
                    'user_id' => $quiz['user_id'],
                    'exam_id' => session('exam_id'),
                    'user_exam_attempted_id' => $userExamAttempted->getKey(),
                    'question_id' => $quiz['question_id'],
                    'answer_id'  => $quiz['answer_id'],
                ]);
            }

            foreach($this->insertFinalAnswers as $insertQuiz)
            {
                QuestionAttemptedUsers::updateOrCreate([
                    'user_id' => $insertQuiz['user_id'],
                    'question_id' => $insertQuiz['question_id'],
                ],[
                    'user_id' => $insertQuiz['user_id'],
                    'question_id' => $insertQuiz['question_id'],

                ]);
            }

            return redirect()->route('examFinal');

        }



    }

    public function render()
    {
        return view('livewire.exam-quiz');
    }
}
