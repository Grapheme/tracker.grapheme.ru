@extends(Helper::acclayout())
@section('style') @stop

@section('content')
<?php
    $tasks[] = ProjectTask::where('user_id',Auth::user()->id)->where('start_status',1)->where('stop_status',0)->with('cooperator','project')->first();
    $projects = ProjectOwners::where('user_id',Auth::user()->id)->orderBy('updated_at','DESC')->with('projects','projects.client','projects.team','projects.tasks')->limit(4)->get();
    if(count($projects)):
        $projectsIDs = ProjectOwners::where('user_id',Auth::user()->id)->lists('project_id');
        foreach(ProjectTask::whereIn('project_id',$projectsIDs)->where('start_status',1)->where('stop_status',0)->with('cooperator','project')->get() as $task):
            #$tasks[] = $task;
        endforeach;
    endif;
    $dt_request = Request::get('date') ? Request::get('date') : date('Y-m-d');
?>

<h1 class="page-header">Dashboard</h1>
<div class="row">
    <!--<div class="col-xs-6 pull-right">
        {{ Form::open(array('route'=>array('oauth.register'),'method'=>'POST','style'=>'display:inline-block')) }}
        {{ Form::submit('Импорт из Basecamp',['class'=>'btn btn-success']) }}
        {{ Form::close() }}
    </div>-->
</div>
@if(count($projects))
<div class="row placeholders">
    @foreach($projects as $project)
        <div class="col-xs-6 col-sm-3 placeholder">
            <a href="{{ URL::route('projects.show',$project->projects->id) }}" class="">
                <img data-src="holder.js/200x200/auto/sky" class="img-responsive" alt="Generic placeholder thumbnail">
            </a>
            <a href="{{ URL::route('projects.show',$project->projects->id) }}" class=""><h4>{{ $project->projects->title }}</h4></a>
            <span class="text-muted">{{ $project->projects->description }}</span>
            @if(count($project->projects->team))
                <br><span class="text-muted">{{ $project->projects->team->count() }} {{ Lang::choice('участник|участника|участников',$project->projects->team->count()) }}</span>
            @endif
            @if(count($project->projects->tasks))
                <br><span class="text-muted">{{ $project->projects->tasks->count() }} {{ Lang::choice('задача|задачи|задач',$project->projects->tasks->count()) }}</span>
            @endif
        </div>
    @endforeach
</div>
@else
    <p>В не являететсь админитратором проектов</p>
@endif
@if(count($tasks))
<h2 class="sub-header">Список активных задач</h2>
<div class="table-responsive">
    <table class="table table-striped">
        <tbody>
        <?php $tasks_total_time = 0;?>
        <?php $tasks_total_price = 0;?>
        <?php $earnMoneyCurrentDate = costCalculation(NULL,['tasks' => $tasks]);?>
        @foreach($tasks as $task)
            <?php $tasks_total_time += (getLeadTimeMinutes($task)+floor($task->lead_time/60));?>
            @if(isset($earnMoneyCurrentDate[$task->id]['earnings']))
                <?php $tasks_total_price += $earnMoneyCurrentDate[$task->id]['earnings'];?>
            @endif
            <tr>
                <td>
                    {{ $task->note }}
                    <br>{{ getInitials($task->cooperator->fio) }}
                    @if(count($task->project))
                        <br>{{ $task->project->title }}
                        @if(count($task->basecamp_task))
                            <a href="{{ $task->basecamp_task->basecamp_task_link  }}" target="_blank"><span aria-hidden="true" class="glyphicon glyphicon-new-window"></span></a>
                        @endif
                        @if($task->project->superior_id == Auth::user()->id)
                            @if(count($task->project->client))
                                ({{ $task->project->client->short_title }})
                            @endif
                        @else
                            @if(count($task->project->client))({{ $task->project->client->short_title }})@endif
                        @endif
                    @endif
                    <br>
                </td>
                <td>
                    {{ culcLeadTime($task) }} @if($task->user_id == Auth::user()->id)/ {{ isset($earnMoneyCurrentDate[$task->id]['earnings']) ? number_format($earnMoneyCurrentDate[$task->id]['earnings'],2,'.',' ').' руб.' : '' }}@endif
                </td>
                <td>
                @if($task->user_id == Auth::user()->id)
                    {{ Form::open(array('route'=>array('timesheets.run_timer'),'method'=>'POST','style'=>'display:inline-block')) }}
                    {{ Form::hidden('task',$task->id) }}
                    @if($task->start_status && !$task->stop_status)
                        {{ Form::hidden('run',0) }}
                        {{ Form::submit('Остановить',['class'=>'btn btn-primary']) }}
                    @else
                        {{ Form::hidden('run',1) }}
                        {{ Form::submit('Продолжить',['class'=>'btn btn-default']) }}
                    @endif
                    {{ Form::close() }}
                @endif
                </td>
                <td>
                @if($task->user_id == Auth::user()->id)
                    <a href="{{ URL::route('timesheets.edit',[$task->id,'date'=>$dt_request]) }}" class="btn btn-success">Редактировать</a></td>
                @endif
                <td>
                @if($task->user_id == Auth::user()->id)
                    {{ Form::open(array('route'=>array('timesheets.destroy',$task->id),'method'=>'DELETE','style'=>'display:inline-block')) }}
                    {{ Form::submit('Удалить',['class'=>'btn btn-danger']) }}
                    {{ Form::close() }}
                @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@else
    <p>Сейчас никто не выполняет задачи</p>
@endif
@stop
@section('scripts')
{{ HTML::script(Config::get('site.theme_path').'/js/docs.min.js') }}
@stop