@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">

        <form style="width: 100%" action="{{ route('questions.update', [
                            'id' => $question->id
                        ]) }}" method="post" id="form">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="PUT">
            <div class="form-group row">
                <label for="content" class="col-md-2 col-form-label">Текст вопроса</label>
                <div class="col-md-10">
                    <textarea
                            name="content"
                            id="content"
                            class="form-control"
                            cols="30"
                            rows="10">{{ old('content', $question->content) }}</textarea>
                    <div>
                        @if($errors->has('content'))
                            <span style="color: red">{{ $errors->first('content') }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label for="mark" class="col-md-2 col-form-label">Количество баллов за вопрос</label>
                <div class="col-md-10">
                    <input
                            type="number"
                            class="form-control"
                            id="mark"
                            name="mark"
                            value="{{ old('mark', $question->mark) }}"
                    >
                    <div>
                        @if($errors->has('mark'))
                            <span style="color: red">{{ $errors->first('mark') }}</span>
                        @endif
                    </div>
                </div>
            </div>

            @php
                $i = 0;
                $rAnswerID = $question->right_answer;
            @endphp

            @foreach ($question->answers as $answerID => $answer)

            @php
                $i++;
                $answerID = $answer->id;
            @endphp

            <div class="form-group row answerBlock">
                <label for="answer{{$i}}" class="col-md-2 col-form-label" id="forText">Вариант ответа {{$i}}</label>
                <div class="col-md-8">
                    <input
                            type="text"
                            class="form-control"
                            id="answer{{$i}}"
                            name="answer[]"
                            value="{{ old('answer.*', $answer->answer) }}"
                    >
                    <button type="button" class="btn btn-danger" title="Удалить этот вариант ответа" id="{{$i}}" onclick="delAnswer(this)">Удалить</button>
                    <div>
                        @if($errors->has('answer.*'))
                            <span style="color: red">{{ $errors->first('answer.*') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="right_answer" id="right_answer{{$i}}" value="0" onclick="checkRightAnswer(this)" @if($rAnswerID == $answerID) checked @endif>
                        <label class="form-check-label" for="right_answer{{$i}}" id="forRadio">
                            Правильный
                        </label>
                        <div>
                        @if($errors->has('right_answer'))
                            <span style="color: red">{{ $errors->first('right_answer') }}</span>
                        @endif
                    </div>
                    </div>
                </div>
            </div>
            @endforeach

            <div class="form-group row">
                <div class="col-md-9">
                    <button type="button" class="btn btn-success" value="Add" id="add" onclick="addAnswer()">Добавить вариант ответа</button>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-9">
                    <button type="submit" class="btn btn-primary" value="Send">Сохранить вопрос</button>
                </div>
            </div>
        </form>     
    </div>
</div>
<script type="text/javascript">
    var i = '<?php echo $i; ?>';
    var radioButtons = $('input[type="radio"]');
    var index = radioButtons.index(radioButtons.filter(':checked'));
    $('input[type="radio"]:checked').val(index);

    function addAnswer(){
        i++;
        // добавляю новый блок с вариантом ответа
        var block_answer = $('.answerBlock:first').clone();
        $('input[type="text"]', block_answer).val('');
        $('input[type="text"]', block_answer).attr('id', function(){
            return 'answer'+i;
        });
        $('#forText', block_answer).attr('for', function(){
            return 'answer'+i;
        });
        $('button', block_answer).attr('id', function(){
            return i;
        });
        $('#forText', block_answer).html('Вариант ответа '+i);
        $('input[type="radio"]', block_answer).attr('id', function(){
            return 'right_answer'+i;
        });
        $('#forRadio', block_answer).attr('for', function(){
            return 'right_answer'+i;
        });
        $('.answerBlock:last').after(block_answer);

        var radioButtons = $('input[type="radio"]');
        var index = radioButtons.index(radioButtons.filter(':checked'));
        $('input[type="radio"]:checked').val(index);
    }

    function checkRightAnswer(el){
        $('input[type="radio"]', '#form').val(0);
        var radioButtons = $('input[type="radio"]');
        var index = radioButtons.index(radioButtons.filter(':checked'));
        $(el).val(index);
        console.log($(el).val());
    }

    function delAnswer(el){

        if($('.answerBlock').length == 1){
            $(el).siblings('input').val('')
        }else{
            $(el).parents('.answerBlock').remove();
            $('input[type="radio"]', '#form').val(0);
        var radioButtons = $('input[type="radio"]');
        var index = radioButtons.index(radioButtons.filter(':checked'));
        $('input[type="radio"]:checked').val(index);
        }

    }
</script>

@endsection