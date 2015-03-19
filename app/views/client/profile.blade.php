@extends(Helper::acclayout())
@section('style') @stop

@section('content')
    @if(!isset($status))
    <form action="{{ action('RemindersController@postRemind') }}" method="POST">
        <input type="hidden" name="email" value="{{ Auth::user()->email }}">
        <input type="submit" value="Обновить пароль">
    </form>
    @elseif($status == 'password-remind')
        @include('assets.form.password-remind',['token'=>$token,'show_email'=>FALSE])
    @endif
@stop
@section('scripts') @stop