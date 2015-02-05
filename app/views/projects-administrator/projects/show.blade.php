@extends(Helper::acclayout())
@section('style') @stop

@section('content')
    <div class="jumbotron" style="background-image: url(/uploads/images/1422287024_1908.jpg)">
        <h1>{{ $project->title }}</h1>
        <p class="lead">{{ $project->description }}</p>
        <a role="button" href="{{ URL::route('project_admin.projects.edit',$project->id) }}" class="btn btn-primary btn-sm">Редактировать</a>
        {{ Form::open(array('route'=>array('project_admin.projects.destroy',$project->id),'method'=>'DELETE','style'=>'display:inline-block')) }}
            {{ Form::submit('Удалить',['class'=>'btn btn-danger btn-sm']) }}
        {{ Form::close() }}
    </div>
    @if($project->team->count())
    <div class="container marketing">
        <div class="row">
        @foreach($project->team as $user)
            <div class="col-lg-4">
                <img class="img-circle" data-src="holder.js/140x140/auto/sky" alt="">
                <h2>{{ getInitials($user->fio) }}</h2>
                <p>{{ $user->position }}</p>
                <p><a class="btn btn-default" href="{{ URL::route('project_admin.cooperators.show',$user->id) }}" role="button">Подробнее &raquo;</a></p>
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
            @foreach($tasks as $task)
                <?php $tasks_total_time += (getLeadTimeMinutes($task)+floor($task->lead_time/60));?>
                <tr {{ ($task->start_status && !$task->stop_status) ? 'class="success"' : '' }}>
                    <td>
                        <a href="{{ URL::route('project_admin.cooperators.show',$task->cooperator->id) }}">{{ getInitials($task->cooperator->fio) }}</a>
                        <br>{{ $task->note }}
                    </td>
                    <td>{{ culcLeadTime($task) }}</td>
                    <td>
                        {{ Form::open(array('route'=>array('project_admin.timesheets.run_timer',),'method'=>'POST','style'=>'display:inline-block')) }}
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
                    <td><a href="{{ URL::route('project_admin.timesheets.edit',$task->id) }}" class="btn btn-success">Редактировать</td>
                    <td>
                        {{ Form::open(array('route'=>array('project_admin.timesheets.destroy',$task->id),'method'=>'DELETE','style'=>'display:inline-block')) }}
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