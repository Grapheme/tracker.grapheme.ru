@extends(Helper::acclayout())
@section('style') @stop

@section('content')
    <h1 class="page-header">Список задач</h1>
    @if(strtotime($dt_request) <= strtotime(date("Y-m-d",time())))
    <a class="btn btn-success" href="{{ URL::route('timesheets.create',['date'=>$dt_request]) }}" title="Добавить задачу" role="button"><span aria-hidden="true" class="glyphicon glyphicon-plus"></span></a>
    @endif
    <a class="btn btn-info" href="{{ URL::route('timesheets.index') }}" role="button">Сегодня</a>
    <div class="btn-group" role="group">
        <a href="{{ URL::route('timesheets.index',['date'=>$startOfWeek->subWeek()->format('Y-m-d')]) }}" class="btn btn-primary">Предыдущая <br> неделя</a>
    @foreach($weekTasks as $date => $weekTask)
        <a href="{{ URL::route('timesheets.index',['date'=>$date]) }}" class="btn btn-default{{ $dt_request == $date ? ' active' : '' }}">
            {{ $weekTask['label'] }}
            @if($weekTask['tasks_count'])
                <br>{{ $weekTask['tasks_count'] }} - {{ getLeadTimeFromMinutes($weekTask['lead_time']) }}
            @else
                <br> нет задач
            @endif
        </a>
    @endforeach
        <a href="{{ URL::route('timesheets.index',['date'=>$endOfWeek->addWeek()->format('Y-m-d')]) }}" class="btn btn-primary">Следующая <br> неделя</a>
    </div>
    @if(count($tasks))
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
                <tr {{ ($task->start_status && !$task->stop_status) ? 'class="success"' : '' }}>
                    <td>
                    @if(count($task->project))
                        <a href="{{ URL::route('projects.show',$task->project->id) }}">{{ $task->project->title }}</a>
                        @if($task->project->superior_id == Auth::user()->id)
                            @if(count($task->project->client))
                            (<a href="{{ URL::route('clients.show',$task->project->client->id) }}">{{ $task->project->client->short_title }}</a>)
                            @endif
                        @else
                            @if(count($task->project->client))({{ $task->project->client->short_title }})@endif
                        @endif
                        <br>
                    @endif
                        @if($task->cooperator->id != Auth::user()->id)
                        <a href="{{ URL::route('cooperators.show',$task->cooperator->id) }}">{{ getInitials($task->cooperator->fio) }}</a>
                        <br>
                        @endif
                        {{ $task->note }}
                    </td>
                    <td>
                        {{ culcLeadTime($task) }} / {{ isset($earnMoneyCurrentDate[$task->id]['earnings']) ? number_format($earnMoneyCurrentDate[$task->id]['earnings'],2,'.',' ').' руб.' : '' }}
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
                    <td><a href="{{ URL::route('timesheets.edit',[$task->id,'date'=>$dt_request]) }}" class="btn btn-success">Редактировать</a></td>
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
                        Время выполнения: {{ getLeadTimeFromMinutes($tasks_total_time) }} ч.<br>
                        Общая сумма: {{ number_format($tasks_total_price,2,'.',' ') }} руб.
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