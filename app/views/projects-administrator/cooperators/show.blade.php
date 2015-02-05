@extends(Helper::acclayout())
@section('style') @stop

@section('content')
    <div class="jumbotron">
        <img class="img-circle" data-src="holder.js/140x140/auto/sky" alt="">
        <h1>{{ $user->fio }}</h1>
        <p class="lead">{{ $user->position }}</p>
        <a role="button" href="{{ URL::route('cooperators.edit',$user->id) }}" class="btn btn-primary btn-sm">Редактировать</a>
        {{ Form::open(array('route'=>array('cooperators.destroy',$user->id),'method'=>'DELETE','style'=>'display:inline-block')) }}
            {{ Form::submit('Удалить',['class'=>'btn btn-danger btn-sm']) }}
        {{ Form::close() }}
    </div>
    @if(count($tasks))
    <h2 class="sub-header">Список задач</h2>
    <div class="table-responsive">
        <table class="table table-striped">
            <tbody>
            <?php $tasks_total_time = 0;?>
            @foreach($tasks as $task)
                <?php $tasks_total_time += (getLeadTimeMinutes($task)+floor($task->lead_time/60));?>
                <tr {{ ($task->start_status && !$task->stop_status) ? 'class="success"' : '' }}>
                    <td>
                        <a href="{{ URL::route('projects.show',$task->project->id) }}">{{ $task->project->title }}</a>
                        <br>{{ $task->note }}
                    </td>
                    <td>{{ culcLeadTime($task) }}</td>
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