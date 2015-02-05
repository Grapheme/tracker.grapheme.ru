@extends(Helper::acclayout())
@section('style') @stop

@section('content')
    <h1 class="page-header">Редактирование проекта</h1>
    <div class="row">
        <div class="col-md-8">
        @if (Request::has('date'))
            <?php $dt_request = Request::get('date'); ?>
        @else
            <?php $dt_request = date('Y-m-d'); ?>
        @endif
        {{ Form::model($task,array('route'=>array('timesheets.update',$task->id),'role'=>'form','class'=>'form-horizontal','method'=>'PUT')) }}
            {{ Form::hidden('set_date',$dt_request) }}
            <div class="form-group has-feedback">
                <label for="inputNote" class="col-sm-3 control-label">Описание</label>
                <div class="col-sm-6">
                    {{ Form::textarea('note',Input::old('note'),['rows'=>"3",'class' => 'form-control','placeholder'=>'Коротко опишите задачу','required'=>'','id'=>'inputNote']) }}
                    <span class="glyphicon glyphicon-warning-sign form-control-feedback" aria-hidden="true"></span>
                    <span id="inputWarning2Status" class="sr-only">(warning)</span>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-success">Сохранить</button>
                    <a href="{{ URL::route('timesheets.index',$task->id) }}" class="btn btn-default">Отмена</a>
                </div>
            </div>
        {{ Form::close() }}
        </div>
    </div>
@stop
@section('scripts')
@stop