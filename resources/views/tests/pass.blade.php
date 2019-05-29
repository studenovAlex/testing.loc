@extends('layouts.app')

@section('content')
<div class="container">
	@php
	$i = 1;
	@endphp
	<form style="width: 100%" action="{{ route('tests.result', [
                            'id' => $test->id
                        ]) }}" method="post" id="form">
            {{ csrf_field() }}
        <div>
        	<h1>{{$test->title}}</h1>
        	<ul>
				@foreach ($test->questions as $questionID => $question)
				<li>
					<b>{{$i++.') '.$question->content}}</b>
					<ol>
						@foreach ($question->answers as $answerID => $answer)
						<li><input type="radio" id="{{$answer->id}}" name="{{$question->id}}" value="{{$answer->id}}">
							<label for="{{$answer->id}}">{{$answer->answer}}</label>
						</li>
						@endforeach
					</ol>
				</li>
				@endforeach
			</ul>
        </div>
		<div class="form-group row">
            <div class="col-md-9">
                <button type="submit" class="btn btn-primary" value="Send">Проверить результаты</button>
            </div>
        </div>
    </form>     
</div>	
			
@endsection
