@extends('layouts.app')

@section('content')
<div class="container">
	<h1>Список тестов</h1>
	<table class="table">
		<thead>
			<tr>
				<th scope="col">Наименование теста</th>
				<th scope="col">Вопросы</th>
				<th scope="col">Пройти тест</th>
				@can('teacher-only', auth()->user()) 
				<th scope="col">Редактирование</th>
				<th scope="col">Удаление</th>
				@endcan	
			</tr>
		</thead>
		@foreach ($tests as $test)
		<tr scope="row">
			<td>{{$test->title}}</td>
			<td>
				<b>Количество вопросов: {{count($test->questions)}}</b>
				@foreach ($test->questions as $question)
				<li>{{$question->content}}</li>
				@endforeach
			</td>
			<td><a class="btn btn-success" href="/tests/{{$test->id}}/pass">Пройти</a></td>
			@can('teacher-only', auth()->user()) 
			<td><a class="btn btn-warning" href="/tests/{{$test->id}}/edit">Редактировать</a></td>
			<td>
				<form action="{{ route('tests.update', [
                            'id' => $test->id
                        ]) }}" method="post">
				    <input class="btn btn-danger" type="submit" value="Удалить" onclick="return confirm('Вы уверены что хотите удалить запись?')"/>
				    <input type="hidden" name="_method" value="delete" />
				    {!! csrf_field() !!}
				</form>
			</td>
			@endcan	
		</tr>
		@endforeach
	</table>
</div>

@endsection
