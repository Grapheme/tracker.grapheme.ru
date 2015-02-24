@extends(Helper::acclayout())
@section('style') @stop

@section('content')
    <h1 class="page-header">Новая задача</h1>
    <div class="row">
        <div class="col-md-8">
        @if (Request::has('date'))
            <?php $dt_request = Request::get('date'); ?>
        @else
            <?php $dt_request = date('Y-m-d'); ?>
        @endif
        {{ Form::open(array('route'=>'timesheets.store','role'=>'form','class'=>'form-horizontal')) }}
            {{ Form::hidden('set_date',$dt_request) }}
            <div class="form-group">
                <label class="col-sm-3 control-label">Проект</label>
                <div class="col-sm-6">
                    {{ Form::select('project',Project::where('superior_id',Auth::user()->id)->lists('title','id'),Input::old('project'),['class'=>'form-control']) }}
                </div>
            </div>
            {{ Form::hidden('performer',Auth::user()->id) }}
            <!--<div class="form-group">
                <label class="col-sm-3 control-label">Исполнитель</label>
                <div class="col-sm-6">
                    {{-- Form::select('performer',$project_team,Input::old('performer'),['class'=>'form-control']) --}}
                </div>
            </div>-->
            <div class="form-group has-feedback">
                <label for="inputNote" class="col-sm-3 control-label">Описание</label>
                <div class="col-sm-6">
                    {{ Form::textarea('note',Input::old('note'),['rows'=>"3",'class' => 'form-control','placeholder'=>'Коротко опишите задачу','required'=>'','id'=>'inputNote']) }}
                    <span class="glyphicon glyphicon-warning-sign form-control-feedback" aria-hidden="true"></span>
                    <span id="inputWarning2Status" class="sr-only">(warning)</span>
                </div>
            </div>
            <div class="form-group">
                <label for="inputLeadTime" class="col-sm-3 control-label">Время выполнения</label>
                <div class="col-sm-6">
                    {{ Form::text('lead_time',Input::old('lead_time'),['class' => 'form-control','placeholder'=>'0:00','id'=>'inputLeadTime']) }}
                    <p id="helpBlock" class="help-block">Оставьте это поле пустым, чтобы запустить таймер. Вы также можете ввести время, как 1.3 или 1:30 (они оба подразумевают 1 час и 30 минут).</p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-success">Создать</button>
                    <a href="{{ URL::route('timesheets.index',['date'=>$dt_request]) }}" class="btn btn-default">Отмена</a>
                </div>
            </div>
        {{ Form::close() }}
        </div>
    </div>
@stop
@section('scripts')
@stop