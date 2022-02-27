<?php

namespace App\Http\Controllers;

use App\Imports\QuestionImport;
use Illuminate\Routing\Controller as BaseController;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends BaseController
{

    public function importQuestions()
    {
        Excel::import(new QuestionImport,request()->file('file'));

        return back();
    }

    public function importView()
    {
        return view('import-question');
    }


}
