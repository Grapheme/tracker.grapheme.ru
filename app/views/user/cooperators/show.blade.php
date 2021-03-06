@extends(Helper::acclayout())
@section('style') @stop

@section('content')
    <div class="jumbotron">
        @if(!empty($user->avatar) && File::exists(public_path($user->avatar->path)))
            <img src="{{ asset($user->avatar->path) }}" alt="">
        @endif
        <h1>{{ $user->fio }}</h1>
        <p class="lead">{{ $user->position }}</p>
        @if($access)
        <a role="button" href="{{ URL::route('cooperators.access.index',$user->id) }}" class="btn btn-primary btn-sm">Настройка доступа</a>
        {{ Form::open(array('route'=>array('cooperators.destroy',$user->id),'method'=>'DELETE','style'=>'display:inline-block', 'class'=>'js-btn-excluded')) }}
            {{ Form::submit('Исключить',['class'=>'btn btn-danger btn-sm']) }}
        {{ Form::close() }}
        @endif
    </div>
    @if($access)
        @include(Helper::acclayout('assets.report-links'),['extended'=>['user'=>$user->id]])
    @endif
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
                    <td style="width: 800px;">
                        @if($task->stop_status)
                            {{ (new myDateTime())->setDateString($task->stop_date)->format('d.m.y в H:i') }}
                        @else
                            Текущая
                        @endif
                    </td>
                    <td>
                        {{ $task->note }}
                        @if(count($task->basecamp_task))
                            <a href="{{ $task->basecamp_task->basecamp_task_link  }}" target="_blank"><span aria-hidden="true" class="glyphicon glyphicon-new-window"></span></a>
                        @endif
                    </td>
                    <td>
                        {{ culcLeadTime($task) }}@if($access) / {{ isset($earnMoneyCurrentDate[$task->id]['earnings']) ? number_format($earnMoneyCurrentDate[$task->id]['earnings'],2,'.',' ').' руб.' : '' }}@endif
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
                            {{ Form::submit('Удалить',['class'=>'btn btn-danger js-btn-delete']) }}
                            {{ Form::close() }}
                        @endif
                    </td>
                </tr>
            @endforeach
            <tr>
                <td>
                    <nobr>Всего {{ count($tasks) }} {{ Lang::choice('задача|задачи|задач',count($tasks)) }}. </nobr><br>
                    <nobr>Время выполнения: {{ getLeadTimeFromMinutes($tasks_total_time) }} ч.</nobr><br>
                    <nobr>Общая сумма: {{ number_format($tasks_total_price,2,'.',' ') }} руб.</nobr>
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