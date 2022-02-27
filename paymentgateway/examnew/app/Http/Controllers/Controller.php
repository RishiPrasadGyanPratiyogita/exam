<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use DB;
use App\Models\Exam;
use App\Models\Category;
use App\Models\CorrectAnswer;
use App\Models\Question;
use App\Exports\UsersExport;
use App\Models\Questionwithanswer;
use App\Models\FinalResult;
use App\Models\Competition;
use App\Models\User;
use Illuminate\Support\Facades\DB as FacadesDB;
use Maatwebsite\Excel\Facades\Excel;

class Controller extends BaseController
{
    public function home()
    {
        return view('home');
    }

    public function importExportView()
    {
        return view('import');
    }

    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    /*************** Exam Controller ********************* */
    public function addexam()
    {
        $examCategoryData = Category::all();
        $competitions = Competition::orderBy('id', 'desc')->get();

        return view('addexam', ['examcategorydata' => $examCategoryData, 'competitions' => $competitions]);
    }

    public function createExam(Request $request)
    {
        $data = $request->validate([
            'competition_id' => 'required',
            'title' => 'required',
            'category' => 'required',
            'agegroup' => 'required',
            'language' => 'required',
            'duration' => 'required',
            'noquestion' => 'required',
            'passmarks' => 'required',
            'totalmarks' => 'required',
            'status' => 'required',
            'type' => 'required',
            'cost' => 'required',
            'fromdate' => 'required',
            'todate' => 'required',
            'instruction' => 'required',
        ]);

        Exam::create($data);

        return redirect()->route('examlist')->with('message', 'Exam Added Successfully');
    }

    public function editExam($id)
    {
        $editExam = Exam::findOrFail($id);
        $examCategoryData = Category::all();
        $competitions = Competition::orderBy('id', 'desc')->get();

        return view('editexam', ['editexam' => $editExam, 'examcategorydata' => $examCategoryData, 'competitions' => $competitions]);
    }

    public function updateExam(Request $request, $id)
    {
        $exam = Exam::findOrFail($id);
        $data = $request->validate([
            'competition_id' => 'required',
            'title' => 'required',
            'category' => 'required',
            'agegroup' => 'required',
            'language' => 'required',
            'duration' => 'required',
            'noquestion' => 'required',
            'passmarks' => 'required',
            'totalmarks' => 'required',
            'status' => 'required',
            'type' => 'required',
            'cost' => 'required',
            'fromdate' => 'required',
            'todate' => 'required',
            'instruction' => 'required',
        ]);
        $exam->update($data);

        return redirect()->route('examlist')->with('message', 'Exam Update Successfully');
    }

    public function destroyExam($id)
    {
        Exam::findOrFail($id)->delete();

        return redirect()->route('examlist')
            ->with([
                'message' => 'Exam Deleted successfully.',
            ]);
    }

    public function examlist()
    {
        $listExam = Exam::orderBy('id', 'desc')->paginate();

        return view('examlist', ['listexam' => $listExam]);
    }
    /*************** Exam Competition ********************* */
    public function competition()
    {
        return view('competition');
    }

    public function createCompetition(Request $request)
    {
        $data = $request->validate([
            'type' => 'required',
            'phase' => 'required',
            'institution_name' => 'required',
            'description' => 'required',
            'from_date' => 'required',
            'end_date' => 'required',
            'status' => 'required',
        ]);
        Competition::create($data);

        return redirect()->route('competitionlist')->with('message', 'Competition Added Successfully');
    }

    public function competitionlist()
    {
        $listCompetition = Competition::paginate();
        return view('competitionlist', ['listcompetition' => $listCompetition]);
    }

    public function editCompetition($id)
    {
        $editCompetition = Competition::findOrFail($id);
        return view('editcompetition', ['editcompetition' => $editCompetition]);
    }

    public function updateCompetition(Request $request, $id)
    {
        $competition = Competition::findOrFail($id);
        $data = $request->validate([
            'type' => 'required',
            'phase' => 'required',
            'institution_name' => 'required',
            'description' => 'required',
            'from_date' => 'required',
            'end_date' => 'required',
            'status' => 'required',
        ]);
        $competition->update($data);

        return redirect()->route('competitionlist')->with('message', 'Competition Update Successfully');
    }

    public function destroyCompetition($id)
    {
        Competition::findOrFail($id)->delete();

        return redirect()->route('competitionlist')
            ->with([
                'message' => 'Competition Deleted successfully.',
            ]);
    }

    /*************** Exam Category ********************* */
    public function examcategory()
    {
        return view('examcategory');
    }

    public function addExamcategory(Request $request)
    {
        $data = $request->validate([
            'category_name' => 'required',
            'description' => 'required',
        ]);
        Category::create($data);

        return redirect()->route('categorylist')->with('message', 'Exam Category Added Successfully');
    }

    public function editCategory($id)
    {
        $editCategory = Category::findOrFail($id);
        return view('editcategory', ['editcategory' => $editCategory]);
    }

    public function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $data = $request->validate([
            'category_name' => 'required',
            'description' => 'required',
        ]);
        $category->update($data);

        return redirect()->route('categorylist')->with('message', 'Exam Category Update Successfully');
    }

    public function destroyCategory($id)
    {
        Category::findOrFail($id)->delete();

        return redirect()->route('categorylist')
            ->with([
                'message' => 'Exam Category Deleted successfully.',
            ]);
    }

    public function categorylist()
    {
        $categoryList = Category::paginate();
        return view('categorylist', ['categorylist' => $categoryList]);
    }

    public function userList()
    {
        $users = FacadesDB::table('users_online')
            ->leftjoin('district', 'district.DistCode', '=', 'users_online.district')
            ->leftjoin('state', 'state.StCode', '=', 'users_online.state')
            ->leftjoin('countries', 'countries.country_code', '=', 'users_online.country')
            ->select('users_online.*', 'district.*', 'state.*', 'countries.*')
            ->paginate();
        return view('userlist', ['users' => $users]);
    }

    public function searchUser(Request $request)
    {
        $search = $request->get('search');
        if ($search != '') {
            $users = User::where('name', 'like', '%' . $search . '%')->paginate();
            $users->appends(array('search' => $request->get('search'),));
            if (count($users) > 0) {
                return view('userlist', ['users' => $users]);
            }
            return back()->with('error', 'No results Found');
        }
    }

    public function addquestion()
    {
        $exams = Exam::get();
        return view('addquestion', compact('exams'));
    }

    public function createQuestion(Request $request)
    {

        $examQuestion = Question::whereExam($request->input('exam'))->latest('serial')->first();

        if (is_null($examQuestion)) {
            $serial = 1;
        } else {
            $serial = $examQuestion->serial + 1;
        }

        $data = $request->validate([
            'exam' => 'required',
            'question' => 'required',
            'marks' => 'required',
            'status' => 'required',
        ]);
        $data['serial'] = $serial;
        $question = Question::create($data);

        foreach ($request->input('answer') as $answer) {
            $info = ['questionid' => $question->id, 'answer' => $answer];
            Questionwithanswer::create($info);
        }

        return redirect()->route('questionlist')->with('message', 'Question Added Successfully');
    }

    public function questionlist()
    {
        $questionList = Question::paginate();
        return view('questionlist', ['questionlist' => $questionList]);
    }

    public function destroyQuestion($id)
    {
        Question::findOrFail($id)->delete();

        return redirect()->route('questionlist')
            ->with([
                'message' => 'Question Deleted successfully.',
            ]);
    }

    public function showQuestion($id)
    {
        $question = Question::findOrFail($id);
        $answer = Questionwithanswer::whereQuestionid($id)->get();
        $correctAnswer = CorrectAnswer::whereQuestionId($id)->first();

        return view('showquestion', ['question' => $question, 'answer' => $answer, 'correctAnswer' => $correctAnswer]);
    }

    public function setAnswer(Request $request)
    {
        $data = $request->validate([
            'answer' => 'required',
        ]);

        CorrectAnswer::updateOrCreate([
            'question_id' => $request->input('question'),
        ], [
            'question_id' => $request->input('question'),
            'answer_id'  => $request->input('answer'),
        ]);
        return redirect()->route('questionlist')->with('message', 'Answer Set Successfully');
    }


    public function profile()
    {
        return view('profile');
    }


    public function resultAll()
    {
        $results = FinalResult::distinct('final_results.user_id')->join('users_online', 'users_online.id', '=', 'final_results.user_id')->select('final_results.user_id', 'users_online.name', 'users_online.hallticket', 'users_online.mobile', 'users_online.agegroup', 'final_results.*')->orderBy('id', 'desc')->paginate();

        return view('exam-result-all', compact('results'));
    }

    public function GroupATopperResult()
    {
        $results = FinalResult::distinct('final_results.user_id')->join('users_online', 'users_online.id', '=', 'final_results.user_id')->select('final_results.user_id', 'users_online.name', 'users_online.hallticket', 'users_online.mobile', 'users_online.agegroup', 'final_results.*')->orderBy('final_results.total_mark', 'desc')->where('users_online.agegroup', 'Under 12 years')->take(10)->get();

        return view('topperlist', compact('results'));
    }

    public function GroupBTopperResult()
    {
        $results = FinalResult::distinct('final_results.user_id')->join('users_online', 'users_online.id', '=', 'final_results.user_id')->select('final_results.user_id', 'users_online.name', 'users_online.hallticket', 'users_online.mobile', 'users_online.agegroup', 'final_results.*')->orderBy('final_results.total_mark', 'desc')->where('users_online.agegroup', '13 to 17 years')->take(10)->get();

        return view('topperlist', compact('results'));
    }

    public function GroupCTopperResult()
    {
        $results = FinalResult::distinct('final_results.user_id')->join('users_online', 'users_online.id', '=', 'final_results.user_id')->select('final_results.user_id', 'users_online.name', 'users_online.hallticket', 'users_online.mobile', 'users_online.agegroup', 'final_results.*')->orderBy('final_results.total_mark', 'desc')->where('users_online.agegroup', '18 to 30 years')->take(10)->get();

        return view('topperlist', compact('results'));
    }

    public function GroupDTopperResult()
    {
        $results = FinalResult::distinct('final_results.user_id')->join('users_online', 'users_online.id', '=', 'final_results.user_id')->select('final_results.user_id', 'users_online.name', 'users_online.hallticket', 'users_online.mobile', 'users_online.agegroup', 'final_results.*')->orderBy('final_results.total_mark', 'desc')->where('users_online.agegroup', 'Above 30 years')->take(10)->get();

        return view('topperlist', compact('results'));
    }

    public function certificate()
    {
        $shareButtons3 = \Share::page('https://makitweb.com/how-to-upload-multiple-files-with-vue-js-and-php/')
            ->facebook()
            ->twitter()
            ->linkedin()
            ->telegram()
            ->whatsapp()
            ->reddit();

        return view('Front.certificate', compact('shareButtons3'));
    }
}
