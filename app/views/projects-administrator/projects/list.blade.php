@extends(Helper::acclayout())
@section('style') @stop

@section('content')
    <h1 class="page-header">Список проектов</h1>
    @if(count($projects))
    <div class="row placeholders">
        @foreach($projects as $project)
        <div class="col-xs-6 col-sm-3 placeholder">
            <a href="{{ URL::route('project_admin.projects.show',$project->id) }}" class="">
                <img data-src="holder.js/200x200/auto/sky" class="img-responsive" alt="Generic placeholder thumbnail">
            </a>
            <a href="{{ URL::route('project_admin.projects.show',$project->id) }}" class=""><h4>{{ $project->title }}</h4></a>
            <span class="text-muted">{{ $project->description }}</span>
            @if($project->team->count())
            <p class="text-muted">{{ $project->team->count() }} {{ Lang::choice('участник|участника|участников',$project->team->count()) }}</p>
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