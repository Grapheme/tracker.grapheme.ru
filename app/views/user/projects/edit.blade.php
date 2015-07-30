@extends(Helper::acclayout())
@section('style') @stop

@section('content')
    <h1 class="page-header">Редактирование проекта</h1>
    <div class="row">
        <div class="col-md-8">
        {{ Form::model($project,array('route'=>array('projects.update',$project->id),'role'=>'form','class'=>'form-horizontal','method'=>'PUT','files'=>TRUE)) }}
            @if($project->superior_id == Auth::user()->id)
            <div class="form-group">
                <label class="col-sm-3 control-label">Клиент</label>
                <div class="col-sm-6">
                    {{ Form::select('client_id',$clients, Input::old('client_id'),['class'=>'form-control']) }}
                </div>
            </div>
            @else
                {{ Form::hidden('client_id',Input::old('client_id')) }}
            @endif
            <div class="form-group has-feedback">
                <label for="inputTitle" class="col-sm-3 control-label">Название проекта</label>
                <div class="col-sm-6">
                    {{ Form::text('title',Input::old('title'),['class' => 'form-control','placeholder'=>'Первый проект','required'=>'','id'=>'inputTitle','autofocus'=>'','required'=>'']) }}
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
                <label for="priceBudget" class="col-sm-3 control-label">Цена за час</label>
                <div class="col-sm-4">
                    {{ Form::text('superior_hour_price',$setProjectValues[$project->superior_id]['hour_price'],['class'=>'form-control','placeholder'=>'']) }}
                </div>
            </div>
            <div class="form-group">
                <label for="priceBudget" class="col-sm-3 control-label">Бюджет</label>
                <div class="col-sm-4">
                    {{ Form::text('budget',Input::old('budget'),['class'=>'form-control','placeholder'=>'']) }}
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Лого</label>
                <div class="col-sm-2">
                    {{ Form::file('logo') }}
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Доступ</label>
                <div class="col-sm-6">
                    {{ Form::select('visible',['Доступен всем','Ограниченный доступ'], Input::old('visible'),['class'=>'form-control']) }}
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"></label>
                <div class="col-sm-6">
                    <div class="form-inline">
                        {{ Form::checkbox('favorite',TRUE,ProjectFavorite::where('project_id',$project->id)->where('user_id',Auth::user()->id)->exists()) }} В избранном
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"></label>
                <div class="col-sm-6">
                    <div class="form-inline">
                        {{ Form::checkbox('in_archive',TRUE) }} В архиве
                    </div>
                </div>
            </div>
            <hr>
            @if(count($project_team))
            <div class="form-group">
                <label for="commands" class="col-sm-3 control-label">Команда</label>
                <div class="col-sm-6">
                @foreach($project_team as $user_id => $user_fio)
                    <?php
                        $selected = in_array($user_id,$setProjectTeamIDs);
                        $hour_price = '';
                        if ($selected && isset($setProjectValues[$user_id])):
                            $hour_price = $setProjectValues[$user_id]['hour_price'] > 0 ? $setProjectValues[$user_id]['hour_price'] : '';
                        endif;
                    ?>
                    <div class="form-inline">
                        {{ Form::checkbox('team['.$user_id.'][user_id]',$user_id,$selected) }} {{ getInitials($user_fio) }}
                        {{ Form::text('team['.$user_id.'][hour_price]',$hour_price,['class'=>'form-control','placeholder'=>'Цена за час']) }}
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