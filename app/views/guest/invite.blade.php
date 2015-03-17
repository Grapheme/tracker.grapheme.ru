@extends(Helper::layout())
@section('style') @stop
@section('register')
@section('content')
    <div class="alert alert-success" role="alert">
        {{ $invitee->fio }} приветствует Вас в его комманде!
    </div>
    <a href="{{ URL::route('dashboard') }}" class="btn btn-success">Личный кабинет</a>
@stop
@section('scripts') @stop