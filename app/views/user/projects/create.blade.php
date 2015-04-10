@extends(Helper::acclayout())
@section('style') @stop

@section('content')
    <h1 class="page-header">Новый проект</h1>
    <div class="row">
        <div class="col-md-8">
        {{ Form::open(array('route'=>'projects.store','role'=>'form','class'=>'form-horizontal')) }}
            {{ Form::hidden('superior_id',Auth::user()->id) }}
            {{ Form::hidden('superior_hour_price',Auth::user()->hour_price,['class'=>'form-control','placeholder'=>'']) }}
            <div class="form-group">
                <label class="col-sm-3 control-label">Клиент</label>
                <div class="col-sm-6">
                    {{ Form::select('client_id',$clients, Input::old('client_id'),['class'=>'form-control']) }}
                </div>
            </div>
            <div class="form-group has-feedback">
                <label for="inputTitle" class="col-sm-3 control-label">Название проекта</label>
                <div class="col-sm-6">
                    {{ Form::text('title',Input::old('title'),['class' => 'form-control','placeholder'=>'Первый проект','id'=>'inputTitle','autofocus'=>'','required'=>'']) }}
                    <span class="glyphicon glyphicon-warning-sign form-control-feedback" aria-hidden="true"></span>
                    <span id="inputWarning2Status" class="sr-only">(warning)</span>
                </div>
            </div>
            <div class="form-group has-feedback">
                <label for="inputDescription" class="col-sm-3 control-label">Описание</label>
                <div class="col-sm-6">
                    {{ Form::text('description',Input::old('description'),['class' => 'form-control','placeholder'=>'Коротко опишите проект','id'=>'inputDescription']) }}
                </div>
            </div>
            <div class="form-group">
                <label for="priceBudget" class="col-sm-3 control-label">Бюджет</label>
                <div class="col-sm-4">
                    {{ Form::text('budget',NULL,['class'=>'form-control','placeholder'=>'']) }}
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"></label>
                <div class="col-sm-6">
                    <div class="form-inline">
                        {{ Form::checkbox('favorite',TRUE) }} Добавить в избранные
                    </div>
                </div>
            </div>
            <hr>
            <div class="form-group">
                <label class="col-sm-3 control-label">Доступ</label>
                <div class="col-sm-6">
                    {{ Form::select('visible',['Доступен всем','Ограниченный доступ'], Input::old('visible'),['class'=>'form-control']) }}
                </div>
            </div>
            @if(count($project_team))
            <div class="form-group">
                <label for="faviconFile" class="col-sm-3 control-label">Команда </label>
                <div class="col-sm-6">
                @foreach($project_team as $user_id => $user)
                    <div class="form-inline">
                        {{ Form::checkbox('team['.$user_id.'][user_id]',$user_id) }} {{ getInitials($user['fio']) }}
                        {{ Form::text('team['.$user_id.'][hour_price]',$user['hour_price'] ? $user['hour_price'] : '',['class'=>'form-control','placeholder'=>'Цена за час']) }}
                    </div>
                @endforeach
                </div>
            </div>
            @endif
            <div class="form-group">
                <label for="faviconFile" class="col-sm-3 control-label">Пригласить в команду</label>
                <div class="col-sm-6">
                    @for($i=0;$i<5;$i++)
                        <div class="form-inline">
                            {{ Form::text('invite_team[email][]','',['class'=>'form-control','placeholder'=>'Email-адрес']) }}
                            {{ Form::text('invite_team[hour_price][]','',['class'=>'form-control','placeholder'=>'Цена за час']) }}
                        </div>
                    @endfor
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