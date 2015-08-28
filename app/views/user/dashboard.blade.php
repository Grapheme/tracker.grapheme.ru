@extends(Helper::acclayout())
@section('style')
    <style type="text/css">
        .popover {
            width: 745px !important;
            max-width: 745px !important;
        }
    </style>
@stop
@section('content')
    <?php
    $projects = ProjectFavorite::where('user_id', Auth::user()->id)->with('project.logo', 'project.client', 'project.team', 'project.tasks')->get();
    $tasks = ProjectTask::where('user_id', Auth::user()->id)->where('start_status', 1)->where('stop_status', 0)->with('cooperator', 'project')->get();
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
            @foreach($projects as $index => $project)
                <div class="col-xs-6 col-sm-3 placeholder">
                    <div class="project-img">
                        <a href="{{ URL::route('projects.show',$project->project->id) }}" class="">
                            @if(!empty($project->project->logo) && File::exists(public_path($project->project->logo->path)))
                                <img src="{{ asset($project->project->logo->path) }}" class="img-responsive"
                                     alt="{{ $project->project->title }}">
                            @else
                                <img style="max-height: 220px" src="http://www.iscalio.com/cats/{{ rand(1, 355) }}.jpg"
                                     class="img-responsive" alt="{{ $project->projects->title }}">
                            @endif
                        </a>
                        <button type="button" class="btn btn-link btn-popover-add-task"
                                data-project-id="{{ $project->project->id }}" data-placement="bottom"
                                data-toggle="popover" title="Добавить текущую задачу"
                                style="position: absolute; left: 30px; top: 10px;">
                            <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
                        </button>
                    </div>
                    <div id="popover_content_wrapper" class="hidden">
                        @include(Helper::acclayout('timesheets.forms.create'),['projects' => [], 'popover' => 1,'redirect_route' => 'dashboard'])
                    </div>
                    {{ $project->project->in_archive? '<p>В архиве</p>' : '' }}
                    <a href="{{ URL::route('projects.show',$project->project->id) }}" class="">
                        <h4>{{ $project->project->title }}</h4></a>
                    <span class="text-muted">{{ $project->project->description }}</span>
                    @if(count($project->project->team))
                        <br><span
                                class="text-muted">{{ $project->project->team->count() }} {{ Lang::choice('участник|участника|участников',$project->project->team->count()) }}</span>
                    @endif
                    @if(count($project->project->tasks))
                        <br><span
                                class="text-muted">{{ $project->project->tasks->count() }} {{ Lang::choice('задача|задачи|задач',$project->project->tasks->count()) }}</span>
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
                <?php $earnMoneyCurrentDate = costCalculation(NULL, ['tasks' => $tasks]);?>
                @foreach($tasks as $task)
                    <?php
                    $showMoney = FALSE;
                    if (isset($task->project->superior_id) && $task->project->superior_id == Auth::user()->id):
                        $showMoney = TRUE;
                        if (isset($earnMoneyCurrentDate[$task->id]['earnings'])):
                            $tasks_total_price += $earnMoneyCurrentDate[$task->id]['earnings'];
                        endif;
                    elseif (!$task->project_id):
                        $showMoney = TRUE;
                    endif;
                    ?>
                    <tr {{ ($task->start_status && !$task->stop_status) ? 'class="success"' : '' }}>
                        <td style="width: 800px;">
                            {{ $task->note }}
                            <br>{{ getInitials($task->cooperator->fio) }}
                            @if(count($task->project))
                                <br>{{ $task->project->title }}
                                @if(count($task->basecamp_task))
                                    <a href="{{ $task->basecamp_task->basecamp_task_link  }}" target="_blank"><span
                                                aria-hidden="true" class="glyphicon glyphicon-new-window"></span></a>
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
                                @if($earnMoneyCurrentDate[$task->id]['whose_price'])<br><span
                                        class="label label-info">{{ @$earnMoneyCurrentDate[$task->id]['whose_price'] }}</span>@endif
                            @endif
                        </td>
                        <td>
                            <nobr>Создана: {{ $task->created_at->format('H:i') }}</nobr>
                            <br>
                            @if($task->created_at != $task->start_date)
                                <nobr>
                                    Запущена: {{ (new myDateTime())->setDateString($task->start_date)->format('H:i') }}</nobr>
                                <br>
                            @endif
                            @if($task->stop_status)
                                <nobr>
                                    Остановлена: {{ (new myDateTime())->setDateString($task->stop_date)->format('H:i') }}</nobr>
                                <br>
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
                        <td><a href="{{ URL::route('timesheets.edit',[$task->id,'date'=>$dt_request]) }}"
                               class="btn btn-success">Редактировать</a></td>
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
    <script type="application/javascript">
        $(function () {
            $.fn.hasAttr = function (name) {
                return this.attr(name) !== undefined;
            };
            $(".btn-popover-add-task").popover({
                html: true,
                content: function () {
                    var project_id = $(this).data('project-id');
                    $("#input-project-id").val(project_id);
                    return $("#popover_content_wrapper").html();
                }
            }).on('shown.bs.popover', function (e) {
                var current_popover_describedby = $(e.target).attr('aria-describedby');
                $(".btn-popover-add-task").each(function (index) {
                    if ($(this).hasAttr('aria-describedby')) {
                        var popover_describedby = $(this).attr('aria-describedby')
                        if (popover_describedby != current_popover_describedby) {
                            $(this).popover('hide');
                        }
                    }
                });
            });
            ;
            $(document).on('click', '.btn-popover-task-cancel', function (event) {
                $(".btn-popover-add-task").popover('hide');
            });
        });
    </script>
@stop