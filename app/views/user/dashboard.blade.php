@extends(Helper::acclayout())
@section('style') @stop

@section('content')
<?php
    $projects = ProjectFavorite::where('user_id',Auth::user()->id)->with('project','project.client','project.team','project.tasks')->get();
    $tasks = ProjectTask::where('user_id',Auth::user()->id)->where('start_status',1)->where('stop_status',0)->with('cooperator','project')->get();
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
            <a href="{{ URL::route('projects.show',$project->project->id) }}" class="">
            @if(File::exists(public_path('uploads/cats/cat-'.(rand(0,14)+1).'.jpg')))
                <img src="{{ asset('uploads/cats/cat-'.(rand(0,14)+1).'.jpg') }}" class="img-responsive" alt="{{ $project->projects->title }}">
            @else
                <img data-src="holder.js/200x200/auto/sky" class="img-responsive" alt="{{ $project->projects->title }}">
            @endif
            </a>
            {{ $project->project->in_archive? '<p>В архиве</p>' : '' }}
            <a href="{{ URL::route('projects.show',$project->project->id) }}" class=""><h4>{{ $project->project->title }}</h4></a>
            <span class="text-muted">{{ $project->project->description }}</span>
            @if(count($project->project->team))
                <br><span class="text-muted">{{ $project->project->team->count() }} {{ Lang::choice('участник|участника|участников',$project->project->team->count()) }}</span>
            @endif
            @if(count($project->project->tasks))
                <br><span class="text-muted">{{ $project->project->tasks->count() }} {{ Lang::choice('задача|задачи|задач',$project->project->tasks->count()) }}</span>
            @endif
        </div>
    @endforeach
</div>
@else
    <p>Добавьте проекты в список избранных</p>
@endif
@if($tasks->count())
<h2 class="sub-header">Текущая задача</h2>
<div class="table-responsive">
    <table class="table table-striped">
        <tbody>
        <?php $earnMoneyCurrentDate = costCalculation(NULL,['tasks' => $tasks]);?>
        @foreach($tasks as $task)
            <?php
            $showMoney = FALSE;
            if(isset($task->project->superior_id) && $task->project->superior_id == Auth::user()->id):
                $showMoney = TRUE;
                if(isset($earnMoneyCurrentDate[$task->id]['earnings'])):
                    $tasks_total_price += $earnMoneyCurrentDate[$task->id]['earnings'];
                endif;
            elseif(!$task->project_id):
                $showMoney = TRUE;
            endif;
            ?>
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
                    {{ culcLeadTime($task) }}
                    @if($showMoney)
                        / {{ isset($earnMoneyCurrentDate[$task->id]['earnings']) ? number_format($earnMoneyCurrentDate[$task->id]['earnings'],2,'.',' ').' руб.' : '' }}
                        @if($earnMoneyCurrentDate[$task->id]['whose_price'])<br><span class="label label-info">{{ @$earnMoneyCurrentDate[$task->id]['whose_price'] }}</span>@endif
                    @endif
                </td>
                <td>
                    Создана: {{ $task->created_at->format('H:i') }}<br>
                    @if($task->created_at != $task->start_date)
                        Запущена: {{ (new myDateTime())->setDateString($task->start_date)->format('H:i') }}<br>
                    @endif
                    @if($task->stop_status)
                        Остановлена: {{ (new myDateTime())->setDateString($task->stop_date)->format('H:i') }}<br>
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
                        {{ Form::submit('Продолжить',['class'=>'btn btn-default']) }}
                    @endif
                    {{ Form::close() }}
                </td>
                <td>
                <td><a href="{{ URL::route('timesheets.edit',[$task->id,'date'=>$dt_request]) }}" class="btn btn-success">Редактировать</a></td>
                <td>
                    {{ Form::open(array('route'=>array('timesheets.destroy',$task->id),'method'=>'DELETE','style'=>'display:inline-block')) }}
                    {{ Form::submit('Удалить',['class'=>'btn btn-danger js-btn-delete']) }}
                    {{ Form::close() }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@else
    <p>Нет выполняемых задач</p>
@endif
@stop
@section('scripts')
{{ HTML::script(Config::get('site.theme_path').'/js/docs.min.js') }}
@stop