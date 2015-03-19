<div class="row">
    <div class="col-md-8">
    {{ Form::open(['action'=>'RemindersController@postReset','role'=>'form','class'=>'form-horizontal']) }}
        {{ Form::hidden('token',$token) }}
        @if(@$show_email === FALSE && Auth::check())
            {{ Form::hidden('email',Auth::user()->email) }}
        @else:
        <div class="form-group">
            <label class="col-sm-3 control-label">Email</label>
            <div class="col-sm-6">
                {{ Form::text('email',@$email,['class'=>'form-control','placeholder'=>'']) }}
            </div>
        </div>
        @endif
        <div class="form-group has-feedback">
            <label class="col-sm-3 control-label">Новый пароль</label>
            <div class="col-sm-6">
                {{ Form::password('password',Input::old('password'),['class' => 'form-control','placeholder'=>'','required'=>'']) }}
            </div>
        </div>
        <div class="form-group has-feedback">
            <label class="col-sm-3 control-label">Подтвердите пароль</label>
            <div class="col-sm-6">
                {{ Form::password('password_confirmation',Input::old('password_confirmation'),['class' => 'form-control','placeholder'=>'','required'=>'']) }}
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-success">Обновить пароль</button>
            @if(Auth::check())
                <a href="{{ URL::route('profile') }}" class="btn btn-default">Отмена</a>
            @else
                <a href="{{ URL::to('/') }}" class="btn btn-default">Отмена</a>
            @endif
            </div>
        </div>
    {{ Form::close() }}
    </div>
</div>