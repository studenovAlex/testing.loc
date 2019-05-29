@extends('layouts.app')

@section('content')
<div class="container">
	<h1>Все результаты</h1>
	<table class="table">
		<thead>
			<tr>
				<th scope="col">Студент</th>
				<th scope="col">Наименование теста</th>
				<th scope="col">Количество баллов</th>
				<th scope="col">Количество правильных ответов</th>
			</tr>
		</thead>
		@foreach ($results as $resultID => $result)
		<tr scope="row">
			<td>{{$result->user->name}}</td>
			<td>{{$result->test->title}}</td>
			<td>{{$result->total_mark}}</td>
			<td>{{$result->right_answers_count}}</td>
		</tr>
		@endforeach
	</table>
</div>

@endsection
