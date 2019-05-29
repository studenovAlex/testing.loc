@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        <form style="width: 100%" action="{{ route('tests.store') }}" method="post" id="form">
            {{ csrf_field() }}

            <div class="form-group row">
                <label for="title" class="col-md-2 col-form-label">Название теста</label>
                <div class="col-md-10">
                    <input
                            type="text"
                            class="form-control"
                            id="title"
                            name="title"
                            value="{{ old('title') }}"
                    >
                    <div>
                        @if($errors->has('title'))
                            <span style="color: red">{{ $errors->first('title') }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div>
                <p><b>Выберите вопросы, которые хотите добавить в тест:</b></p>
                <ul>
                    @foreach ($questions as $question)
                    <li><input type="checkbox" name="questions[]" value="{{$question->id}}"> {{$question->content}}</li>
                    @endforeach
                </ul>
            </div>  

            <div class="form-group row">
                <div class="col-md-9">
                    <button type="submit" class="btn btn-primary" value="Send">Сохранить тест</button>
                </div>
            </div>
        </form>     
    </div>
</div>
<script type="text/javascript">
</script>
@endsection