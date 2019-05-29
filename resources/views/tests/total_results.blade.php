@extends('layouts.app')

@section('content')
<div class="container">
	@if ($results->isEmpty())
		<p>У Вас еще нет результатов. Пройдите тест!</p>
		<td><a class="btn btn-primary" href="/tests">Вернуться к тестам</a></td>
	@else
		<h1>Ваши результаты</h1>
		<table class="table">
			<thead>
				<tr>
					<th scope="col">Наименование теста</th>
					<th scope="col">Количество баллов</th>
					<th scope="col">Количество правильных ответов</th>
				</tr>
			</thead>
			@foreach ($results as $resultID => $result)
			<tr scope="row">
				<td>{{$result->test->title}}</td>
				<td>{{$result->total_mark}}</td>
				<td>{{$result->right_answers_count}}</td>
			</tr>
			@endforeach
		</table>
	@endif
</div>

@endsection
