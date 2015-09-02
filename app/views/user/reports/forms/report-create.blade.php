{{ Form::open(['route'=>'report.create','method'=>'get','role'=>'form','class'=>'form-horizontal']) }}
<div class="form-group">
    <label class="col-sm-3 control-label">От</label>
    <div class="col-sm-3">
        {{ Form::text('begin_date',Input::has('begin_date') ? Input::get('begin_date') : (new myDateTime())->setDateString($startOfDay)->format('d.m.Y'),['class'=>'form-control datepicker','readonly'=>'']) }}
    </div>
</div>
<div class="form-group has-feedback">
    <label class="col-sm-3 control-label">До</label>
    <div class="col-sm-3">
        {{ Form::text('end_date',Input::has('end_date') ? Input::get('end_date') : (new myDateTime())->setDateString($endOfDay)->format('d.m.Y'),['class'=>'form-control datepicker', 'readonly'=>'']) }}
    </div>
</div>
@if(count($clients) > 1)
    <div class="form-group">
        <label class="col-sm-3 control-label">Клиент</label>
        <div class="col-sm-6">
            {{ Form::select('client',$clients,Input::get('client'),['class'=>'form-control','autocomplete'=>'off']) }}
        </div>
    </div>
@endif
@if(count($projects) > 1)
    <div class="form-group">
        <label class="col-sm-3 control-label">Проект</label>
        <div class="col-sm-6">
            {{ Form::select('project',$projects,Input::get('project'),['class'=>'form-control','autocomplete'=>'off']) }}
        </div>
    </div>
@endif
@if(count($users) > 1)
    <div class="form-group">
        <label class="col-sm-3 control-label">Команда</label>
        <div class="col-sm-6">
            {{ Form::select('user',$users,Input::get('user'),['class'=>'form-control','autocomplete'=>'off']) }}
        </div>
    </div>
@endif
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {{ Form::submit('Сформировать',['class'=>'btn btn-success']) }}
    </div>
</div>
{{ Form::close() }}