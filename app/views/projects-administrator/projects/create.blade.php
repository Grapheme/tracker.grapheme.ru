@extends(Helper::acclayout())
@section('style') @stop

@section('content')
    <h1 class="page-header">Новый проект</h1>
    <div class="row">
        <div class="col-md-8">
        {{ Form::open(array('route'=>'project_admin.projects.store','role'=>'form','class'=>'form-horizontal','file'=>TRUE)) }}
            <div class="form-group has-feedback">
                <label for="inputTitle" class="col-sm-3 control-label">Название проекта</label>
                <div class="col-sm-4">
                    {{ Form::text('title',Input::old('title'),['class' => 'form-control','placeholder'=>'Первый проект','id'=>'inputTitle','autofocus'=>'']) }}
                    <span class="glyphicon glyphicon-warning-sign form-control-feedback" aria-hidden="true"></span>
                    <span id="inputWarning2Status" class="sr-only">(warning)</span>
                </div>
            </div>
            <div class="form-group">
                <label for="inputDescription" class="col-sm-3 control-label">Описание</label>
                <div class="col-sm-6">
                    {{ Form::text('description',Input::old('description'),['class' => 'form-control','placeholder'=>'Коротко опишите проект','id'=>'inputDescription']) }}
                </div>
            </div>
            <div class="form-group">
                <label for="inputHourPrice" class="col-sm-3 control-label">Цена за час</label>
                <div class="col-sm-3">
                    <div class="input-group">
                        {{ Form::text('hour_price',Input::old('hour_price'),['class' => 'form-control','placeholder'=>'500','id'=>'inputHourPrice']) }}
                        <div class="input-group-addon"> руб.</div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="iconFile" class="col-sm-3 control-label">Изображение </label>
                <div class="col-sm-6">
                    {{ Form::file('file',['id'=>"iconFile"]) }}
                    <p class="help-block">Доступные форматы: JPG, PNG, GIF.<br>Максимальный размер файла: 2 Мб</p>
                </div>
            </div>
            <div class="form-group">
                <label for="faviconFile" class="col-sm-3 control-label">Комманда </label>
                <div class="col-sm-6">
                    {{ Form::select('team[]',$project_team,Input::old('team'),['multiple'=>'','class'=>'form-control','size'=>5]) }}
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-success">Создать</button>
                    <a href="{{ URL::route('project_admin.projects.index') }}" class="btn btn-default">Отмена</a>
                </div>
            </div>
        {{ Form::close() }}
        </div>
    </div>
@stop
@section('scripts')
@stop