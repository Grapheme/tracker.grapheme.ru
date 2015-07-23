@extends(Helper::acclayout())
@section('style') @stop

@section('content')
    @if(!isset($status))
    @if(!empty($profile->avatar) && File::exists(public_path($profile->avatar->path)))
        <img src="{{ asset($profile->avatar->path) }}" class="img-circle" style="width: 140px; height: 140px; position: fixed;" alt="">
        <div class="clearfix"></div>
    @endif
    {{ Form::model($profile,['route'=>'profile.update','method'=>'put','role'=>'form','class'=>'form-horizontal','files'=>TRUE]) }}
    <div class="form-group">
        <label class="col-sm-3 control-label">Цена за час, руб</label>
        <div class="col-sm-2">
            {{ Form::text('hour_price',Input::old('hour_price'),['class' => 'form-control','required'=>'']) }}
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Аватар</label>
        <div class="col-sm-2">
            {{ Form::file('avatar') }}
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-10">
            {{ Form::submit('Сохранить',['class'=>'btn btn-default']) }}
        </div>
    </div>
    {{ Form::close() }}
    @if(Auth::user()->clients()->count())
    <hr>
    {{ Form::model($requisites,['route'=>'profile.requisites','method'=>'put','role'=>'form','class'=>'form-horizontal','files'=>TRUE]) }}
    {{ Form::hidden('id') }}
    {{ Form::hidden('superior_id',Auth::user()->id) }}
    <div class="form-group">
        <label class="col-sm-3 control-label">Название</label>
        <div class="col-sm-4">
            {{ Form::text('title',Input::old('title'),['class' => 'form-control','required'=>'']) }}
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Короткое название</label>
        <div class="col-sm-2">
            {{ Form::text('short_title',Input::old('short_title'),['class' => 'form-control','required'=>'']) }}
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Адрес</label>
        <div class="col-sm-4">
            {{ Form::textarea('address',Input::old('address'),['class' => 'form-control','rows'=>1]) }}
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Телефон</label>
        <div class="col-sm-2">
            {{ Form::text('phone',Input::old('phone'),['class' => 'form-control']) }}
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Логотип</label>
        <div class="col-sm-2">
            {{ Form::file('logo') }}
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Название банка</label>
        <div class="col-sm-4">
            {{ Form::text('bank',Input::old('bank'),['class' => 'form-control']) }}
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">БИК</label>
        <div class="col-sm-2">
            {{ Form::text('bik',Input::old('bik'),['class' => 'form-control']) }}
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">К/с</label>
        <div class="col-sm-3">
            {{ Form::text('kor_account',Input::old('kor_account'),['class' => 'form-control']) }}
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">ИНН</label>
        <div class="col-sm-2">
            {{ Form::text('inn',Input::old('inn'),['class' => 'form-control']) }}
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">КПП</label>
        <div class="col-sm-2">
            {{ Form::text('kpp',Input::old('kpp'),['class' => 'form-control']) }}
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Р/с</label>
        <div class="col-sm-3">
            {{ Form::text('payment_account',Input::old('payment_account'),['class' => 'form-control']) }}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-10">
            {{ Form::submit('Сохранить',['class'=>'btn btn-default']) }}
        </div>
    </div>
    {{ Form::close() }}
    @endif
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