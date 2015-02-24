@extends(Helper::acclayout())
@section('style') @stop

@section('content')
    <div class="jumbotron" style="background-image: url(/uploads/images/1422287024_1908.jpg)">
        <h1>{{ $client->title }}</h1>
        <p class="lead">{{ $client->description }}</p>
        <a role="button" href="{{ URL::route('clients.edit',$client->id) }}" class="btn btn-primary btn-sm">Редактировать</a>
        {{ Form::open(array('route'=>array('clients.destroy',$client->id),'method'=>'DELETE','style'=>'display:inline-block')) }}
            {{ Form::submit('Удалить',['class'=>'btn btn-danger btn-sm']) }}
        {{ Form::close() }}
    </div>
    @if(count($projects))
    <div class="container marketing">
        <div class="row">
        @foreach($projects as $project)
            <div class="col-lg-4">
                <img class="img-circle" data-src="holder.js/140x140/auto/sky" alt="">
                <h2>{{ $project->title }}</h2>
                <p><a class="btn btn-default" href="{{ URL::route('projects.show',$project->id) }}" role="button">Подробнее &raquo;</a></p>
            </div>
        @endforeach
        </div>
    </div>
    @endif
    @if(count($tasks))
    <h2 class="sub-header">Список задач</h2>
    <div class="table-responsive">
        <table class="table table-striped">
            <tbody>
            <?php $tasks_total_time = 0;?>
            <?php $earnMoneyCurrentDate = costCalculation(NULL,['tasks' => $tasks]);?>
            <?php $earnMoneyTotal = costCalculation();?>
            @foreach($tasks as $task)
                <?php $tasks_total_time += (getLeadTimeMinutes($task)+floor($task->lead_time/60));?>
                <tr {{ ($task->start_status && !$task->stop_status) ? 'class="success"' : '' }}>
                    <td>
                        <a href="{{ URL::route('cooperators.show',$task->cooperator->id) }}">{{ getInitials($task->cooperator->fio) }}</a>
                        <br>{{ $task->note }}
                        @if(count($task->basecamp_task))
                        <a href="{{ $task->basecamp_task->basecamp_task_link  }}" target="_blank"><span aria-hidden="true" class="glyphicon glyphicon-new-window"></span></a>
                        @endif
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
                    <td><a href="{{ URL::route('timesheets.edit',$task->id) }}" class="btn btn-success">Редактировать</td>
                    <td>
                        {{ Form::open(array('route'=>array('timesheets.destroy',$task->id),'method'=>'DELETE','style'=>'display:inline-block')) }}
                        {{ Form::submit('Удалить',['class'=>'btn btn-danger']) }}
                        {{ Form::close() }}
                    </td>
                </tr>
            @endforeach
                <tr>
                    <td>
                        Всего {{ count($tasks) }} {{ Lang::choice('задача|задачи|задач',count($tasks)) }}. <br>
                        Время выполнения: {{ getLeadTimeFromMinutes($tasks_total_time) }} ч.
                    </td>
                    <td colspan="4"></td>
                </tr>
            </tbody>
        </table>
    </div>
    @else

    @endif
@stop
@section('scripts')
    {{ HTML::script(Config::get('site.theme_path').'/js/docs.min.js') }}
@stop