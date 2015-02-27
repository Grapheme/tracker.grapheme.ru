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
        @if(Session::has('invite_id') && Session::has('invitee_user_id') && Session::has('invite_redirect'))
        {{ Form::hidden('invite_id',Session::get('invite_id')) }}
        {{ Form::hidden('invitee_user_id',Session::get('invitee_user_id')) }}
        {{ Form::hidden('invite_redirect',Session::get('invite_redirect')) }}
        @endif
        <h2 class="form-register-heading">Заполните форму</h2>
        <label for="inputFIO" class="sr-only">ФИО</label>
        {{ Form::text('fio', Input::old('fio'), ['class' => 'form-control','placeholder'=>'Ф.И.О.','required'=>'','id'=>'inputFIO','autofocus'=>'']) }}
        <label for="inputEmail" class="sr-only">Email адрес</label>
        {{ Form::email('email', Input::old('email'), ['class' => 'form-control','placeholder'=>'Email адрес','required'=>'','id'=>'inputEmail']) }}
        {{ Form::submit('Зарегистрироваться', ['class' => 'btn btn-lg btn-primary btn-block']) }}
    {{ Form::close() }}
@stop
@section('scripts') @stop