@extends(Helper::acclayout())
@section('style') @stop

@section('content')
    <h1 class="page-header">Список проектов</h1>
    @if(count($projects))
    <div class="row placeholders">
        @foreach($projects as $project)
        <div class="col-xs-6 col-sm-3 placeholder">
            <a href="{{ URL::route('projects.show',$project->id) }}" class="">
                <img data-src="holder.js/200x200/auto/sky" class="img-responsive" alt="Generic placeholder thumbnail">
            </a>
            <a href="{{ URL::route('projects.show',$project->id) }}" class=""><h4>{{ $project->title }}</h4></a>
            @if(!empty($project->description))
            <span class="text-muted">{{ $project->description }}</span><br>
            @endif
            @if(!empty($project->client))
                <a href="{{ URL::route('clients.show',$project->client->id) }}" class="">{{ $project->client->title }}</a><br>
            @endif
            @if($project->team->count())
            <span class="text-muted">{{ $project->team->count() }} {{ Lang::choice('участник|участника|участников',$project->team->count()) }}</span><br>
            @endif
            @if($project->tasks->count())
            <span class="text-muted">{{ $project->tasks->count() }} {{ Lang::choice('задача|задачи|задач',$project->tasks->count()) }}</span>
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