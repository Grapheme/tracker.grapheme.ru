@extends(Helper::acclayout())
@section('style') @stop

@section('content')
    <h1 class="page-header">Новый клиент</h1>
    <div class="row">
        <div class="col-md-8">
        {{ Form::open(array('route'=>'clients.store','role'=>'form','class'=>'form-horizontal','file'=>TRUE)) }}
            <div class="form-group has-feedback">
                <label for="inputTitle" class="col-sm-3 control-label">Название клиента</label>
                <div class="col-sm-4">
                    {{ Form::text('title',Input::old('title'),['class' => 'form-control','placeholder'=>'','id'=>'inputTitle','autofocus'=>'','required'=>'']) }}
                    <span class="glyphicon glyphicon-warning-sign form-control-feedback" aria-hidden="true"></span>
                    <span id="inputWarning2Status" class="sr-only">(warning)</span>
                </div>
            </div>
            <div class="form-group">
                <label for="inputDescription" class="col-sm-3 control-label">Описание</label>
                <div class="col-sm-6">
                    {{ Form::text('description',Input::old('description'),['class' => 'form-control','placeholder'=>'','id'=>'inputDescription']) }}
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
                <label for="priceHour" class="col-sm-3 control-label">Цена за час</label>
                <div class="col-sm-4">
                    {{ Form::text('hour_price',Input::old('hour_price'),['class'=>'form-control','placeholder'=>'Цена за час для владельца']) }}
                </div>
            </div>
            <div class="form-group">
                <label for="priceBudget" class="col-sm-3 control-label">Бюджет</label>
                <div class="col-sm-4">
                     {{ Form::text('budget',Input::old('budget'),['class'=>'form-control','placeholder'=>'Бюджет для владельца']) }}
                </div>
            </div>
            <div class="form-group">
                <label for="inputRequisites" class="col-sm-3 control-label">Реквизиты</label>
                <div class="col-sm-6">
                    {{ Form::textarea('requisites',Input::old('requisites'),['class' => 'form-control','placeholder'=>'','id'=>'inputRequisites']) }}
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-success">Создать</button>
                    <a href="{{ URL::route('projects.index') }}" class="btn btn-default">Отмена</a>
                </div>
            </div>
        {{ Form::close() }}
        </div>
    </div>
@stop
@section('scripts')
@stop