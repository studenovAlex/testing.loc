<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Answer;
use App\Http\Requests\QuestionRequest;
use Illuminate\Support\Facades\Gate;

class QuestionsController extends Controller
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
        // доступ к просмотру списка вопросов имеет только пользователь с ролью "учитель". иначе вьюшка с ошибкой доступа
        $questions = Question::with(['answers'])->get();
        if (Gate::allows('teacher-only', auth()->user())) { // проверка роли авторизованного пользователя
            return view('questions.list', compact('questions'));
        }
        return view('access.403b');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // доступ к созданию вопросов имеет только пользователь с ролью "учитель". иначе вьюшка с ошибкой доступа
        $questions = Question::with(['answers'])->get();
        if (Gate::allows('teacher-only', auth()->user())) { // проверка роли авторизованного пользователя
            return view('questions.create');
        }
        return view('access.403b');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuestionRequest $request)
    {
        // создаем объект класса вопросов. предварительно валидируем форму через QuestionRequest.
        // записываем значения в таблицу вопросов. присваем правильный ответ на вопрос как 1, далее перезапишем её
        $newQuestion = new Question;
        $newQuestion->content = $request->content;
        $newQuestion->mark = $request->mark;
        $newQuestion->right_answer = 1;
        $newQuestion->save();
        
        // проходим по массиву реквеста. берём только значения answer. достаем значения. создаем обьект класса ответов. записываем значения. каждый ответ будет содержать айди вопроса к которому он относится
        foreach ($request->input('answer') as $key => $value) {
            $newAnswer = new Answer;
            $newAnswer->question_id = $newQuestion->id;
            $newAnswer->answer = $value;
            $newAnswer->save();

            // если ключ массива реквеста совпадает со значением правильного ответа на вопрос, то перезаписываем айди ответа в таблицу вопросов в столбец правильных ответов.
            // значение правильного ответа получаем через jquery как индекс радио кнопки со свойством checked
            if($key == $request->right_answer){
                $newQuestion->right_answer = $newAnswer->id;
                $newQuestion->save();
            }
        }
        
        return redirect(route('home'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // ищем айди вопроса на который кликнули
        $question = Question::with('answers')->find($id);
        if (Gate::allows('teacher-only', auth()->user())) { // проверка роли авторизованного пользователя
            return view('questions.edit', compact('question'));
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
    public function update(QuestionRequest $request, $id)
    {
        // ищем айди вопроса, который собираемся обновить. перезаписываем значения, предварительно валидируем форму через QuestionRequest.
        $editQuestion = Question::find($id);
        $editQuestion->content = $request->content;
        $editQuestion->mark = $request->mark;
        $editQuestion->save();

        // так как кол-во ответов может меняться, то принято решение удалять старые варианты ответов вопроса и записывать новые во избежание конфликтов с присвоением правильных значений правильным айди ответов
        $editAnswer = Answer::where('question_id', '=', $editQuestion->id)->get();
        foreach ($editAnswer as $answerID => $answer) {
            $answer->delete();
        }

        // создаем новые варианты ответов по аналогии с методом store. так же получаем и значение правильного ответа.
        foreach ($request->input('answer') as $key => $value) {
            $newAnswer = new Answer;
            $newAnswer->question_id = $editQuestion->id;
            $newAnswer->answer = $value;
            $newAnswer->save();

            if($key == $request->right_answer){
                $editQuestion->right_answer = $newAnswer->id;
                $editQuestion->save();
            }
        }
        
        return redirect(route('questions.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // получаем айди вопроса. ищем в вопросах такую строку. в ответах ищем строки где столбец question_id равен айди искомого вопроса.
        $question = Question::find($id);
        $answers = Answer::where('question_id', '=', $question->id)->get();

        // сначала удаляем все значения из обьекта ответов для того что бы не хранить ответы на удаленный вопрос
        foreach ($answers as $answer) {
            $answer->delete();
        }

        // затем удаляем и сам вопрос
        $question->delete();

        return redirect(route('questions.index'));
    }
}
