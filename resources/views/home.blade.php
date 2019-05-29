@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card-deck">
                <div class="card">
                    <div class="card-header">Личный кабинет</div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        @can('teacher-only', auth()->user()) 
                        <div>
                            <h3>Вопросы</h3>
                            <a href="/questions" class="btn btn-primary">Посмотреть список вопросов</a>
                            <a href="/questions/create" class="btn btn-success">Создать вопрос</a>
                            <a href="/tests/create" class="btn btn-success">Создать тест</a>
                        </div>
                        @endcan
                        <div>
                            <h3>Тесты</h3>
                            <a href="/tests" class="btn btn-primary">Посмотреть список тестов</a>
                            <a href="/tests/results" class="btn btn-success">Посмотреть результаты</a>
                        </div> 
                    </div>
                    <div class="card-header">
                        @if(auth()->user()->isTeacher)
                            {{ 'Вы залогинены как учитель!' }}
                        @else
                            {{ 'Вы залогинены как тестирующийся!' }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
