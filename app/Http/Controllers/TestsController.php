<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Test;
use App\Models\Result;
use App\Http\Requests\TestRequest;
use Illuminate\Support\Facades\Gate;

class TestsController extends Controller
{

    public function __construct()
    {
        // проверка на доступ только авторизованным 
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tests = Test::with('questions')->get();
        return view('tests.list', compact('tests'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $questions = Question::orderBy('content', 'asc')->get();    
        if (Gate::allows('teacher-only', auth()->user())) { // проверка роли авторизованного пользователя
            return view('tests.create', compact('questions'));
        }
        return view('access.403b');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TestRequest $request)
    {
        $newTest = new Test;
        $newTest->title = $request->title;
        $newTest->save();

        // тут сохранять в промежуточную таблицу test_id, question_id 
        foreach ($request->input('questions') as $key => $value) {
            $question = Question::find($value);
            $newTest->questions()->save($question);
        }
        
        return redirect(route('home'));
    }

    public function pass($id)
    {
        $test = Test::with('questions.answers')->find($id);
        return view('tests.pass', compact('test'));
    }

    public function result(Request $request, $id)
    {

        $resultPoints = 0;
        $rightAnswers = 0;
        $questions = 0;
        foreach ($request->input() as $questionID => $answerID) {
            if(is_numeric($questionID)){
                $questions++;
                $result = Question::with('answers')->find($questionID);
                // echo $answerID;
                if($answerID == $result->right_answer)
                {
                    $resultPoints += $result->mark;
                    $rightAnswers++;
                }
            }
        }

        $newResult = new Result;
        $newResult->user_id = auth()->user()->id;
        $newResult->test_id = $id;
        $newResult->total_mark = $resultPoints;
        $newResult->right_answers_count = $rightAnswers;
        $newResult->save();

        return view('tests.result')
                    ->withResult($resultPoints)
                    ->withAnswers($rightAnswers)
                    ->withQuestions($questions)
                    ->withResult($resultPoints);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $results = Result::with('test')->where('user_id', '=', auth()->user()->id)->get();
        if (Gate::allows('teacher-only', auth()->user())) { // проверка роли авторизованного пользователя
            $results = Result::with('test' ,'user')->get();
            return view('tests.total_results_teacher', compact('results'));
        }
        return view('tests.total_results', compact('results'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Gate::allows('teacher-only', auth()->user())) { // проверка роли авторизованного пользователя
            return view('tests.edit', compact('questions'));
        }
        return view('access.403b');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TestRequest $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
