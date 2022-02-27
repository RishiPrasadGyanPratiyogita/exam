<?php

namespace App\Imports;

use App\Models\CorrectAnswer;
use App\Models\Question;
use App\Models\Questionwithanswer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuestionImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        $examQuestion = Question::whereExam($row['exam'])->latest('serial')->first(); 
        
        if(is_null($examQuestion))
        {
            $serial = 1;
        }else
        {
            $serial = $examQuestion->serial + 1;
        }

        $question = Question::create([
            'exam'     => $row['exam'],
            'serial'  => $serial,
            'question'    => str_replace('"', '', $row['question']),
            'marks' => $row['marks'],
            'status' => $row['status']
        ]);


        if(!is_null($row['answer_1']))
        {
            Questionwithanswer::create([
                'questionid' => $question->getKey(),
                'answer' => $row['answer_1']
            ]);
        }

        if(!is_null($row['answer_2']))
        {
            Questionwithanswer::create([
                'questionid' => $question->getKey(),
                'answer' => $row['answer_2']
            ]);
        }


        if(!is_null($row['answer_3']))
        {
            Questionwithanswer::create([
                'questionid' => $question->getKey(),
                'answer' => $row['answer_3']
            ]);
        }


        if(!is_null($row['answer_4']))
        {
            Questionwithanswer::create([
                'questionid' => $question->getKey(),
                'answer' => $row['answer_4']
            ]);
        }

        $answer = Questionwithanswer::where(['answer' => $row['correct_answer'] ,'questionid' => $question->getKey()])->first();
       
        CorrectAnswer::create([
            'question_id' => $question->getKey(),
            'answer_id' => $answer->id
        ]);

       
    }
}
