@extends(Helper::acclayout())
@section('style') @stop

@section('content')
    <h1 class="page-header">Список проектов</h1>
    @if($archive)
        <a href="{{ URL::route('projects.index') }}" class="btn btn-link">Активные</a>
    @else
        <a href="{{ URL::route('projects.archive') }}" class="btn btn-link">Архивные</a>
    @endif
    <div style="margin-top: 40px"></div>
    @if(count($projects['my']))
        <div class="row placeholders">
            @foreach($projects['my'] as $index => $project)
                @if($project->projects->in_archive == $archive)
                    <div style="min-height: 350px;" class="col-xs-6 col-sm-3 placeholder">
                        <a href="{{ URL::route('projects.show',$project->projects->id) }}" class="">
                            @if(!empty($project->projects->logo) && File::exists(public_path($project->projects->logo->path)))
                                <img src="{{ asset($project->projects->logo->path) }}" class="img-responsive"
                                     alt="{{ $project->projects->title }}">
                            @else
                                <img src="http://www.iscalio.com/cats/{{ rand(1, 355) }}.jpg" class="img-responsive"
                                     alt="{{ $project->projects->title }}">
                            @endif
                        </a>
                        <a href="{{ URL::route('projects.show',$project->projects->id) }}" class="">
                            <h4>{{ $project->projects->title }}</h4></a>
                        @if(!empty($project->projects->description))
                            <span class="text-muted">{{ $project->projects->description }}</span><br>
                        @endif
                        @if(!empty($project->projects->client))
                            @if($project->projects->superior_id == Auth::user()->id)
                                <a href="{{ URL::route('clients.show',$project->projects->client->id) }}"
                                   class="">{{ !empty($project->projects->client->short_title) ? $project->projects->client->short_title : $project->projects->client->title }}</a>
                                <br>
                            @else
                                {{ !empty($project->projects->client->short_title) ? $project->projects->client->short_title : $project->projects->client->title }}
                                <br>
                            @endif
                        @endif
                        @if($project->projects->team->count())
                            <span class="text-muted">{{ $project->projects->team->count()+1 }} {{ Lang::choice('участник|участника|участников',$project->projects->team->count()+1) }}</span>
                            <br>
                        @endif
                        @if($project->projects->tasks->count())
                            <span class="text-muted">{{ $project->projects->tasks->count() }} {{ Lang::choice('задача|задачи|задач',$project->projects->tasks->count()) }}</span>
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
    @endif
    @if(count($projects['subscribe']))
        <div class="row placeholders">
            @foreach($projects['subscribe'] as $project)
                @if($project->projects->in_archive == $archive)
                    <div style="min-height: 350px;" class="col-xs-6 col-sm-3 placeholder">
                        <a href="{{ URL::route('projects.show',$project->projects->id) }}" class="">
                            @if(!empty($project->projects->logo) && File::exists(public_path($project->projects->logo->path)))
                                <img src="{{ asset($project->projects->logo->path) }}" class="img-responsive"
                                     alt="{{ $project->projects->title }}">
                            @else
                                <img src="http://www.iscalio.com/cats/{{ rand(1, 355) }}.jpg" class="img-responsive"
                                     alt="{{ $project->projects->title }}">
                            @endif
                        </a>
                        <a href="{{ URL::route('projects.show',$project->projects->id) }}" class="">
                            <h4>{{ $project->projects->title }}</h4></a>
                        @if(!empty($project->projects->description))
                            <span class="text-muted">{{ $project->projects->description }}</span><br>
                        @endif
                        @if(!empty($project->projects->client))
                            {{ $project->projects->client->title }}<br>
                        @endif
                        @if($project->projects->team->count())
                            <span class="text-muted">{{ $project->projects->team->count() }} {{ Lang::choice('участник|участника|участников',$project->projects->team->count()) }}</span>
                            <br>
                        @endif
                        @if($project->projects->tasks->count())
                            <span class="text-muted">{{ $project->projects->tasks->count() }} {{ Lang::choice('задача|задачи|задач',$project->projects->tasks->count()) }}</span>
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
    @endif
@stop
@section('scripts')
    {{ HTML::script(Config::get('site.theme_path').'/js/docs.min.js') }}
@stop