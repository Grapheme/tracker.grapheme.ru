@extends(Helper::acclayout())
@section('style') @stop

@section('content')
    <h1 class="page-header">Список задач</h1>
    @if (Request::has('date'))
        <?php $dt_request = Request::get('date'); ?>
    @else
        <?php $dt_request = date('Y-m-d'); ?>
    @endif
    <a class="btn btn-success" href="{{ URL::route('project_admin.timesheets.create',['date'=>$dt_request]) }}" title="Добавить задачу" role="button"><span aria-hidden="true" class="glyphicon glyphicon-plus"></span></a>
    <a class="btn btn-info" href="{{ URL::route('project_admin.timesheets.index') }}" role="button">Сегодня</a>
    <div class="btn-group" role="group">
        <a href="{{ URL::route('project_admin.timesheets.index',['date'=>(new \Carbon\Carbon($dt_request))->subWeek()->format('Y-m-d')]) }}" class="btn btn-primary">Предыдущая неделя</a>
    @for($day = 0; $day < 7; $day++)
        <?php $dt = (new \Carbon\Carbon($dt_request))->startOfWeek()->AddDays($day); ?>
        <a href="{{ URL::route('project_admin.timesheets.index',['date'=>$dt->format('Y-m-d')]) }}" class="btn btn-default{{ $dt_request == $dt->format('Y-m-d') ? ' active' : '' }}">{{ $dt->format('d.m') }}</a>
    @endfor
        <a href="{{ URL::route('project_admin.timesheets.index',['date'=>(new \Carbon\Carbon($dt_request))->addWeek()->format('Y-m-d')]) }}" class="btn btn-primary">Следующая неделя</a>
    </div>
    @if(count($tasks))
    <div class="table-responsive">
        <table class="table table-striped">
            <tbody>
            @foreach($tasks as $task)
                <tr>
                    <td>
                        {{ $task->project->title }} <br>
                        {{ getInitials($task->cooperator->fio) }} - {{ $task->note }}
                    </td>
                    <td>{{ culcLeadTime($task) }}</td>
                    <td>
                        <a href="#" class="btn btn-default">Остановить</a>
                    </td>
                    <td><a href="{{ URL::route('project_admin.timesheets.edit',$task->id) }}" class="btn btn-success">Редактировать</td>
                    <td>
                        {{ Form::open(array('route'=>array('project_admin.timesheets.destroy',$task->id),'method'=>'DELETE','style'=>'display:inline-block')) }}
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