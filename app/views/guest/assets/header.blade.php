<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            @if (Request::is('/'))
                <div class="navbar-brand">Tracker Grapheme</div>
            @else
                <a class="navbar-brand" href="{{ URL::route('home') }}">Tracker Grapheme</a>
            @endif
        </div>
        <div id="navbar" class="navbar-collapse collapse">
        {{ Form::open(array('route'=>'login','role'=>'form','class'=>'navbar-form navbar-right','id'=>'signin-form')) }}
            <div class="form-group">
                {{ Form::email('login',NULL,['placeholder'=>'Email','class'=>'form-control','required'=>'']) }}
            </div>
            <div class="form-group">
                {{ Form::password('password', ['class' => 'form-control','placeholder'=>'Password','required'=>'']) }}
            </div>
            {{ Form::submit('Вход', ['class' => 'btn btn-success']) }}
        {{ Form::close() }}
        </div>
    </div>
</nav>