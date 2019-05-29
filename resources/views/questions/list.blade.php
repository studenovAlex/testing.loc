@extends('layouts.app')

@section('content')
<div class="container">
	<h1>Список вопросов</h1>
	<table class="table">
		<thead>
			<tr>
				<th scope="col">Вопрос</th>
				<th scope="col">Варианты ответов</th>
				<th scope="col">Редактирование</th>
				<th scope="col">Удаление</th>
			</tr>
		</thead>
		@foreach ($questions as $question)
		<tr scope="row">
			<td>{{$question->content}}</td>
			<td>
				@foreach ($question->answers as $answer)
				<li>{{$answer->answer}}</li>
				@endforeach
			</td>
			<td><a class="btn btn-warning" href="/questions/{{$question->id}}/edit">Редактировать</a></td>
			<td>
				<form action="{{ route('questions.update', [
                            'id' => $question->id
                        ]) }}" method="post">
				    <input class="btn btn-danger" type="submit" value="Удалить" onclick="return confirm('Вы уверены что хотите удалить запись?')"/>
				    <input type="hidden" name="_method" value="delete" />
				    {!! csrf_field() !!}
				</form>
			</td>
		</tr>
		@endforeach
	</table>
</div>

@endsection
