@extends('layouts.app')

@section('content')
<div class="container">
	<h1>Ваш результат</h1>
	<table class="table">
		<tr scope="row">
			<td>Количество баллов</td>
			<td>{{$result}}</td>
		</tr>
		<tr scope="row">
			<td>Правильных ответов</td>
			<td>{{$answers.' из '.$questions}}</td>
		</tr>
	</table>
	<td><a class="btn btn-primary" href="/tests">Вернуться к тестам</a></td>
</div>

@endsection
