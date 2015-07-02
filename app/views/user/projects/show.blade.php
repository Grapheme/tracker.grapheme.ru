@extends(Helper::acclayout())
@section('style') @stop

@section('content')
    <div class="jumbotron" style="background-image: url(/uploads/images/1422287024_1908.jpg)">
        <h1>{{ $project->title }}</h1>
        <p class="lead">{{ $project->description }}</p>
    @if($access && $project->user_id == Auth::user()->id)
        @if(!empty($project->client))
            <a href="{{ URL::route('clients.show',$project->client->id) }}" class=""><h5>{{ !empty($project->client->short_title) ? $project->client->short_title : $project->client->title }}</h5></a>
        @endif
    @else
        @if(!empty($project->client))<h5>{{ !empty($project->client->short_title) ? $project->client->short_title : $project->client->title }}</h5>@endif
    @endif
    @if($project->in_archive)
        <p class="text-info">В архиве</p>
    @endif
    @if($access)
        <a role="button" href="{{ URL::route('projects.edit',$project->id) }}" class="btn btn-primary btn-sm">Редактировать</a>
        @if($project->superior_id == Auth::user()->id)
        {{ Form::open(array('route'=>array('projects.destroy',$project->id),'method'=>'DELETE','style'=>'display:inline-block')) }}
            {{ Form::submit('Удалить',['class'=>'btn btn-danger btn-sm js-btn-delete']) }}
        {{ Form::close() }}
        @endif
    @endif
        {{ Form::open(array('route'=>array('projects.favorite',$project->id),'style'=>'display:inline-block','method'=>'PUT')) }}
            {{ Form::hidden('favorite',$inFavorite) }}
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-success">{{ $inFavorite ? 'Убрать из избранных' : 'Добавить в избранные' }}</button>
                </div>
            </div>
        {{ Form::close() }}
    </div>
    @if($project->team->count())
    <div class="container marketing">
        <div class="row">
        @if($project->superior->count())
            <div class="col-lg-4">
                <img class="img-circle" data-src="holder.js/140x140/auto/sky" alt="">
                <h2>{{ getInitials($project->superior->fio) }}</h2>
                <p>{{ $project->superior->position }}</p>
                <p><a class="btn btn-default" href="{{ URL::route('cooperators.show',$project->superior->id) }}" role="button">Подробнее &raquo;</a></p>
            </div>
        @endif
        @foreach($project->team as $user)
            <div class="col-lg-4">
                <img class="img-circle" data-src="holder.js/140x140/auto/sky" alt="">
                <h2>{{ getInitials($user->fio) }}</h2>
                <p>{{ $user->position }}</p>
                <p><a class="btn btn-default" href="{{ URL::route('cooperators.show',$user->id) }}" role="button">Подробнее &raquo;</a></p>
            </div>
        @endforeach
        </div>
    </div>
    @endif
    @if($access)
        @include(Helper::acclayout('assets.report-links'),['extended'=>['project'=>$project->id]])
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
                    <td>
                        {{ $task->note }}
                        <br>{{ getInitials($task->cooperator->fio) }}
                        @if(count($task->basecamp_task))
                        <a href="{{ $task->basecamp_task->basecamp_task_link  }}" target="_blank"><span aria-hidden="true" class="glyphicon glyphicon-new-window"></span></a>
                        @endif
                    </td>
                    <td>
                        {{ culcLeadTime($task) }} @if($task->user_id == Auth::user()->id)/ {{ isset($earnMoneyCurrentDate[$task->id]['earnings']) ? number_format($earnMoneyCurrentDate[$task->id]['earnings'],2,'.',' ').' руб.' : '' }}@endif
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
                        <a href="{{ URL::route('timesheets.edit',$task->id) }}" class="btn btn-success">Редактировать</a></td>
                    @endif
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