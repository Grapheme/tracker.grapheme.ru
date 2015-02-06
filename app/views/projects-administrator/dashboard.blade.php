@extends(Helper::acclayout())
@section('style') @stop

@section('content')
<?php
    $tasks = array();
    $projects = Project::where('superior_id',Auth::user()->id)->orderBy('updated_at','DESC')->with('team','tasks')->limit(4)->get();
    if($projects->count()):
        $projectsIDs = Project::where('superior_id',Auth::user()->id)->lists('id');
        $tasks = ProjectTask::whereIn('project_id',$projectsIDs)->where('stop_status',0)->with('cooperator','project')->get();
    endif;
    $dt_request = Request::get('date') ? Request::get('date') : date('Y-m-d');
?>

<h1 class="page-header">Dashboard</h1>
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
@if(count($tasks))
<h2 class="sub-header">Список активных задач</h2>
<div class="table-responsive">
    <table class="table table-striped">
        <tbody>
        @foreach($tasks as $task)
            <tr>
                <td>
                    <a href="{{ URL::route('projects.show',$task->project->id) }}">{{ $task->project->title }}</a>
                    <br>
                    <a href="{{ URL::route('cooperators.show',$task->cooperator->id) }}">{{ getInitials($task->cooperator->fio) }}</a>
                    <br>
                    {{ $task->note }}
                </td>
                <td>{{ culcLeadTime($task) }}</td>
                <td>
                    {{ Form::open(array('route'=>array('timesheets.run_timer'),'method'=>'POST','style'=>'display:inline-block')) }}
                    {{ Form::hidden('task',$task->id) }}
                    @if($task->start_status && !$task->stop_status)
                        {{ Form::hidden('run',0) }}
                        {{ Form::submit('Остановить',['class'=>'btn btn-primary']) }}
                    @else
                        {{ Form::hidden('run',1) }}
                        {{ Form::submit('Начать',['class'=>'btn btn-default']) }}
                    @endif
                    {{ Form::close() }}
                </td>
                <td><a href="{{ URL::route('timesheets.edit',[$task->id,'date'=>$dt_request]) }}" class="btn btn-success">Редактировать</a></td>
                <td>
                    {{ Form::open(array('route'=>array('timesheets.destroy',$task->id),'method'=>'DELETE','style'=>'display:inline-block')) }}
                    {{ Form::submit('Удалить',['class'=>'btn btn-danger']) }}
                    {{ Form::close() }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endif
@stop
@section('scripts')
{{ HTML::script(Config::get('site.theme_path').'/js/docs.min.js') }}
@stop