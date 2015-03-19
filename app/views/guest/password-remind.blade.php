@extends(Helper::layout())
@section('style') @stop
@section('content')
    @include('assets.form.password-remind',['token'=>$token,'show_email'=>TRUE,'email'=>@$email])
@stop
@section('scripts') @stop