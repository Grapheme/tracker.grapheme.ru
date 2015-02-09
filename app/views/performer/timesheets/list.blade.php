@extends(Helper::acclayout())
@section('style') @stop

@section('content')
    <h1 class="page-header">Список задач</h1>
    <a class="btn btn-success" href="{{ URL::route('timesheets.create',['date'=>$dt_request]) }}" title="Добавить задачу" role="button"><span aria-hidden="true" class="glyphicon glyphicon-plus"></span></a>
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
            <?php $earnMoneyCurrentDate = costCalculation(NULL,['tasks' => $tasks]);?>
            <?php $earnMoneyTotal = costCalculation(NULL,['accounts' => Team::where('cooperator_id',Auth::user()->id)->groupBy('superior_id')->lists('superior_id')]);?>
            @foreach($tasks as $task)
                <tr {{ ($task->start_status && !$task->stop_status) ? 'class="success"' : '' }}>
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
    @else

    @endif
@stop
@section('scripts')
{{ HTML::script(Config::get('site.theme_path').'/js/docs.min.js') }}
@stop