@extends(Helper::acclayout())
@section('style') @stop

@section('content')
<?php
    $tasks = array();
    if($projects = Project::where('superior_id',Auth::user()->id)->orderBy('updated_at','DESC')->limit(4)->get()):
        $projectsIDs = array();
        foreach($projects as $project):
            $projectsIDs[] = $project->id;
        endforeach;
        if ($projectsIDs):
            $tasks = ProjectTask::whereIn('project_id',$projectsIDs)->where('stop_status',0)->with('cooperator','project')->get();
        endif;
    endif;
?>

<h1 class="page-header">Dashboard</h1>
@if(count($projects))
<div class="row placeholders">
    @foreach($projects as $project)
        <div class="col-xs-6 col-sm-3 placeholder">
            <a href="{{ URL::route('project_admin.projects.show',$project->id) }}" class="">
                <img data-src="holder.js/200x200/auto/sky" class="img-responsive" alt="Generic placeholder thumbnail">
            </a>
            <a href="{{ URL::route('project_admin.projects.show',$project->id) }}" class=""><h4>{{ $project->title }}</h4></a>
            <span class="text-muted">{{ $project->description }}</span>
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
                    {{ $task->project->title }} <br>
                    {{ getInitials($task->cooperator->fio) }} - {{ $task->note }}
                </td>
                <td>{{ culcLeadTime($task) }}</td>
                <td>
                    <a href="#" class="btn btn-default">Остановить</a>
                </td>
                <td><a href="{{ URL::route('project_admin.timesheets.edit',$task->id) }}" class="btn btn-success">Редактировать</td>
                <td>
                    {{ Form::open(array('route'=>array('project_admin.timesheets.destroy',$task->id),'method'=>'DELETE','style'=>'display:inline-block')) }}
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