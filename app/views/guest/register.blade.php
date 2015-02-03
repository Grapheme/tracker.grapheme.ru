@extends(Helper::layout())
@section('style') @stop
@section('content')
    @if(count($errors))
    <div class="alert alert-danger" role="alert">
        <ul>
    @foreach ($errors->all() as $message)
            <li>{{ $message }}</li>
    @endforeach
        </ul>
    </div>
    @endif
    {{ Form::open(array('route'=>'register-store','role'=>'form','class'=>'form-register','id'=>'register-form')) }}
        <h2 class="form-register-heading">Заполните форму</h2>
        <label for="inputFIO" class="sr-only">ФИО</label>
        {{ Form::text('fio', Input::old('fio'), ['class' => 'form-control','placeholder'=>'Ф.И.О.','required'=>'','id'=>'inputFIO','autofocus'=>'']) }}
        <label for="inputEmail" class="sr-only">Email адрес</label>
        {{ Form::email('email', Input::old('email'), ['class' => 'form-control','placeholder'=>'Email адрес','required'=>'','id'=>'inputEmail']) }}
        {{ Form::submit('Зарегистрироваться', ['class' => 'btn btn-lg btn-primary btn-block']) }}
    {{ Form::close() }}
@stop
@section('scripts') @stop