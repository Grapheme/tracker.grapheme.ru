@extends(Helper::acclayout())
@section('style') @stop

@section('content')
    @if(!isset($status))
    {{ Form::model($profile,['route'=>'profile.update','method'=>'put','role'=>'form','class'=>'form-horizontal']) }}
    <div class="form-group">
        <label class="col-sm-3 control-label">Цена за час, руб</label>
        <div class="col-sm-2">
            {{ Form::text('hour_price',Input::old('hour_price'),['class' => 'form-control','required'=>'']) }}
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            {{ Form::submit('Сохранить',['class'=>'btn btn-default']) }}
        </div>
    </div>
    {{ Form::close() }}
    <hr>
    {{ Form::open(['action'=>'RemindersController@postRemind','method'=>'post','role'=>'form','class'=>'form-horizontal']) }}
        {{ Form::hidden('email',Auth::user()->email) }}
        <div class="form-group">
            <div class="col-sm-10">
                {{ Form::submit('Обновить пароль',['class'=>'btn btn-default']) }}
            </div>
        </div>
    {{ Form::close() }}
    @elseif($status == 'password-remind')
        @include('assets.form.password-remind',['token'=>$token,'show_email'=>FALSE])
    @endif
@stop
@section('scripts') @stop