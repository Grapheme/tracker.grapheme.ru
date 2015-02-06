@extends(Helper::acclayout())
@section('style') @stop

@section('content')
    <h1 class="page-header">Редактирование проекта</h1>
    <div class="row">
        <div class="col-md-8">
        {{ Form::model($project,array('route'=>array('projects.update',$project->id),'role'=>'form','class'=>'form-horizontal','method'=>'PUT','file'=>TRUE)) }}
            <div class="form-group has-feedback">
                <label for="inputTitle" class="col-sm-3 control-label">Название проекта</label>
                <div class="col-sm-4">
                    {{ Form::text('title',NULL,['class' => 'form-control','placeholder'=>'Первый проект','required'=>'','id'=>'inputTitle','autofocus'=>'']) }}
                    <span class="glyphicon glyphicon-warning-sign form-control-feedback" aria-hidden="true"></span>
                    <span id="inputWarning2Status" class="sr-only">(warning)</span>
                </div>
            </div>
            <div class="form-group">
                <label for="inputDescription" class="col-sm-3 control-label">Описание</label>
                <div class="col-sm-6">
                    {{ Form::text('description',NULL,['class' => 'form-control','placeholder'=>'Коротко опишите проект','id'=>'inputDescription']) }}
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
                @foreach($project_team as $user_id => $user_fio)
                    <?php
                        $selected = in_array($user_id,$setProjectTeamIDs);
                        $hour_price = $budget = '';
                        if ($selected && isset($setProjectValues[$user_id])):
                            $hour_price = $setProjectValues[$user_id]['hour_price'];
                            $budget = $setProjectValues[$user_id]['budget'];
                        endif;
                    ?>
                    <div class="checkbox">
                        <label>
                            {{ Form::checkbox('team['.$user_id.'][user_id]',$user_id,$selected) }} {{ $user_fio }}
                        </label>
                    </div>
                    <div class="form-inline">
                        {{ Form::text('team['.$user_id.'][hour_price]',$hour_price,['class'=>'form-control','placeholder'=>'Цена за час']) }}
                        {{ Form::text('team['.$user_id.'][budget]',$budget,['class'=>'form-control','placeholder'=>'Бюджет']) }}
                    </div>
                @endforeach
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-success">Сохранить</button>
                    <a href="{{ URL::route('projects.show',$project->id) }}" class="btn btn-default">Отмена</a>
                </div>
            </div>
        {{ Form::close() }}
        </div>
    </div>
@stop
@section('scripts')
@stop