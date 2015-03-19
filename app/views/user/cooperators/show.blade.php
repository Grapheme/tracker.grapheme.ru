@extends(Helper::acclayout())
@section('style') @stop

@section('content')
    <div class="jumbotron">
        <img class="img-circle" data-src="holder.js/140x140/auto/sky" alt="">
        <h1>{{ $user->fio }}</h1>
        <p class="lead">{{ $user->position }}</p>
        @if($access)
        <a role="button" href="{{ URL::route('cooperators.access.index',$user->id) }}" class="btn btn-primary btn-sm">Настройка доступа</a>
        {{ Form::open(array('route'=>array('cooperators.destroy',$user->id),'method'=>'DELETE','style'=>'display:inline-block')) }}
            {{ Form::submit('Исключить',['class'=>'btn btn-danger btn-sm']) }}
        {{ Form::close() }}
        @endif
    </div>
    @include(Helper::acclayout('assets.report-links'),['extended'=>['user'=>$user->id]])
    @if(count($tasks))
    <h2 class="sub-header">Список задач</h2>
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
                        {{ $task->note }}
                        @if(count($task->basecamp_task))
                            <a href="{{ $task->basecamp_task->basecamp_task_link  }}" target="_blank"><span aria-hidden="true" class="glyphicon glyphicon-new-window"></span></a>
                        @endif
                    </td>
                    <td>
                        {{ culcLeadTime($task) }} / {{ isset($earnMoneyCurrentDate[$task->id]['earnings']) ? number_format($earnMoneyCurrentDate[$task->id]['earnings'],2,'.',' ').' руб.' : '' }}
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
                            <a href="{{ URL::route('timesheets.edit',$task->id) }}" class="btn btn-success">Редактировать</a>
                        @endif
                    </td>
                    <td>
                        @if($task->user_id == Auth::user()->id)
                            {{ Form::open(array('route'=>array('timesheets.destroy',$task->id),'method'=>'DELETE','style'=>'display:inline-block')) }}
                            {{ Form::submit('Удалить',['class'=>'btn btn-danger']) }}
                            {{ Form::close() }}
                        @endif
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
    @endif
@stop
@section('scripts')
    {{ HTML::script(Config::get('site.theme_path').'/js/docs.min.js') }}
@stop