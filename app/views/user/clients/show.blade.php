@extends(Helper::acclayout())
@section('style') @stop

@section('content')
    <div class="jumbotron">
        @if(!empty($client->logo) && File::exists(public_path($client->logo->path)))
            <img src="{{ asset($client->logo->path) }}" alt="">
        @endif
        <h1>{{ !empty($client->title) ? $client->short_title : $client->title }}</h1>
        <p class="lead">{{ $client->description }}</p>
        <a role="button" href="{{ URL::route('clients.edit',$client->id) }}" class="btn btn-primary btn-sm">Редактировать</a>
        {{ Form::open(array('route'=>array('clients.destroy',$client->id),'method'=>'DELETE','style'=>'display:inline-block')) }}
            {{ Form::submit('Удалить',['class'=>'btn btn-danger btn-sm js-btn-delete']) }}
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
    @include(Helper::acclayout('assets.report-links'),['extended'=>['client'=>$client->id]])
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
                        {{ getInitials($task->cooperator->fio) }}
                        <br>{{ $task->note }}
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
                        {{ Form::submit('Удалить',['class'=>'btn btn-danger js-btn-delete']) }}
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