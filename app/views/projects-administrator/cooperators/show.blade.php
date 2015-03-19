@extends(Helper::acclayout())
@section('style') @stop

@section('content')
    <div class="jumbotron">
        <img class="img-circle" data-src="holder.js/140x140/auto/sky" alt="">
        <h1>{{ $user->fio }}</h1>
        <p class="lead">{{ $user->position }}</p>
        @if($access)
        {{ Form::open(array('route'=>array('cooperators.destroy',$user->id),'method'=>'DELETE','style'=>'display:inline-block')) }}
            {{ Form::submit('Исключить',['class'=>'btn btn-danger btn-sm']) }}
        {{ Form::close() }}
        @endif
    </div>
    @include(Helper::acclayout('assets.report-links'),['extended'=>['user'=>$user->id]])
@stop
@section('scripts')
    {{ HTML::script(Config::get('site.theme_path').'/js/docs.min.js') }}
@stop