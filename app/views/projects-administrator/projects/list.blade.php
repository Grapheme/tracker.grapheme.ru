@extends(Helper::acclayout())
@section('style') @stop

@section('content')
    <h1 class="page-header">Список проектов</h1>
    {{ Form::open(array('route'=>array('oauth.register'),'method'=>'POST','style'=>'display:inline-block')) }}
        {{ Form::submit('Импорт из Basecamp',['class'=>'btn btn-default']) }}
    {{ Form::close() }}
    @if(count($projects))
    <div class="row placeholders">
        @foreach($projects as $project)
        <div class="col-xs-6 col-sm-3 placeholder">
            <a href="{{ URL::route('projects.show',$project->id) }}" class="">
                <img data-src="holder.js/200x200/auto/sky" class="img-responsive" alt="Generic placeholder thumbnail">
            </a>
            <a href="{{ URL::route('projects.show',$project->id) }}" class=""><h4>{{ $project->title }}</h4></a>
            <span class="text-muted">{{ $project->description }}</span>
            @if($project->team->count())
            <br><span class="text-muted">{{ $project->team->count() }} {{ Lang::choice('участник|участника|участников',$project->team->count()) }}</span>
            @endif
            @if($project->tasks->count())
            <br><span class="text-muted">{{ $project->tasks->count() }} {{ Lang::choice('задача|задачи|задач',$project->tasks->count()) }}</span>
            @endif
        </div>
        @endforeach
    </div>
    @else

    @endif
@stop
@section('scripts')
{{ HTML::script(Config::get('site.theme_path').'/js/docs.min.js') }}
@stop