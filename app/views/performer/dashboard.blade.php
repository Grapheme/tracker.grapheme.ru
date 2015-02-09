@extends(Helper::acclayout())
@section('style') @stop

@section('content')
<?php
    $projects = User::where('id',Auth::user()->id)->first()->cooperator_projects()->get();
    $tasks = ProjectTask::where('user_id',Auth::user()->id)->where('start_status',1)->where('stop_status',0)->with('project')->get();
    $dt_request = Request::get('date') ? Request::get('date') : date('Y-m-d');
?>

<h1 class="page-header">Dashboard</h1>
@if(count($projects))
<div class="row placeholders">
    @foreach($projects as $project)
        <div class="col-xs-6 col-sm-3 placeholder">
            <img data-src="holder.js/200x200/auto/sky" class="img-responsive" alt="Generic placeholder thumbnail">
            <h4>{{ $project->title }}</h4>
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
        <?php $earnMoneyCurrentDate = costCalculation(NULL,['tasks' => $tasks]);?>
        <?php $earnMoneyTotal = costCalculation(NULL,['accounts' => Team::where('cooperator_id',Auth::user()->id)->groupBy('superior_id')->lists('superior_id')]);?>
        @foreach($tasks as $task)
            <tr>
                <td>
                    {{ $task->project->title }}
                    <br>
                    {{ $task->note }}
                </td>
                <td>
                    {{ culcLeadTime($task) }} / {{ isset($earnMoneyCurrentDate[$task->project->id][$task->id]['earnings']) ? number_format($earnMoneyCurrentDate[$task->project->id][$task->id]['earnings'],2,'.',' ').' руб.' : '' }}
                    @if(isset($earnMoneyTotal[$task->project->id][$task->id]['overdose']) && $earnMoneyTotal[$task->project->id][$task->id]['overdose'] == 1)
                        <br><span class="label label-danger">Превышен допустимый лимит бюджета</span>
                        <br><span class="label label-info">Текущий заработок: {{ number_format($earnMoneyTotal[$task->project->id][$task->id]['overdose_money'],2,'.',' ').' руб.' }}</span>
                        <br><span class="label label-info">Доступный бюджет: {{ number_format($earnMoneyTotal[$task->project->id][$task->id]['budget'],2,'.',' ').' руб.' }}</span>
                    @endif
                </td>
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
                <td><a href="{{ URL::route('timesheets.edit',[$task->id,'date'=>$dt_request]) }}" class="btn btn-success">Редактировать</td>
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