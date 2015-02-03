@extends(Helper::acclayout())
@section('style') @stop

@section('content')
    <h1 class="page-header">Редактирование проекта</h1>
    <div class="row">
        <div class="col-md-8">
        {{ Form::model($user,array('route'=>array('project_admin.cooperators.update',$user->id),'role'=>'form','class'=>'form-horizontal','method'=>'PUT','file'=>TRUE)) }}
            <div class="form-group has-feedback">
                <label for="inputFIO" class="col-sm-3 control-label">Ф.И.О.</label>
                <div class="col-sm-4">
                    {{ Form::text('fio',Input::old('fio'),['class' => 'form-control','placeholder'=>'Иванов Иван Иванович','required'=>'','id'=>'inputFIO','autofocus'=>'']) }}
                    <span class="glyphicon glyphicon-warning-sign form-control-feedback" aria-hidden="true"></span>
                    <span id="inputWarning2Status" class="sr-only">(warning)</span>
                </div>
            </div>
            <div class="form-group">
                <label for="inputPosition" class="col-sm-3 control-label">Должность</label>
                <div class="col-sm-4">
                    {{ Form::text('position',Input::old('position'),['class' => 'form-control','placeholder'=>'Программист','id'=>'inputPosition']) }}
                </div>
            </div>
            <div class="form-group">
                <label for="iconFile" class="col-sm-3 control-label">Аватар </label>
                <div class="col-sm-6">
                    {{ Form::file('file',['id'=>"iconFile"]) }}
                    <p class="help-block">Доступные форматы: JPG, PNG, GIF.<br>Максимальный размер файла: 2 Мб</p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-success">Сохранить</button>
                    <a href="{{ URL::route('project_admin.cooperators.show',$user->id) }}" class="btn btn-default">Отмена</a>
                </div>
            </div>
        {{ Form::close() }}
        </div>
    </div>
@stop
@section('scripts')
@stop