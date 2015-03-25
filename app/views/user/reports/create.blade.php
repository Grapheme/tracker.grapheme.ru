@extends(Helper::acclayout())
@section('style') @stop
@section('content')
    <div class="container marketing">
        <div class="row">
            {{ Form::open(['route'=>'report.create','method'=>'get','role'=>'form','class'=>'form-horizontal']) }}
                <div class="form-group">
                    <label class="col-sm-3 control-label">От</label>
                    <div class="col-sm-3">
                        {{ Form::text('begin_date',Input::has('begin_date') ? Input::get('begin_date') : (new myDateTime())->setDateString($startOfDay)->format('Y-m-d'),['class'=>'form-control']) }}
                    </div>
                </div>
                <div class="form-group has-feedback">
                    <label class="col-sm-3 control-label">До</label>
                    <div class="col-sm-3">
                        {{ Form::text('end_date',Input::has('end_date') ? Input::get('end_date') : (new myDateTime())->setDateString($endOfDay)->format('Y-m-d'),['class'=>'form-control']) }}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"></label>
                    <div class="col-sm-6">
                        <p class="text-info">
                            Выберите один из доступных фильтров. Приоритет верху вниз.
                        </p>
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
                    <label class="col-sm-3 control-label">Сотрудник</label>
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
        </div>
    </div>
    <div class="container marketing">
        <div class="row">
            <h2 class="sub-header">Список задач (от {{ (new myDateTime())->setDateString($startOfDay)->format('d.m.Y') }} до {{ (new myDateTime())->setDateString($endOfDay)->format('d.m.Y') }})</h2>
        @if(count($tasks))
            {{ Form::open(['route'=>['report.save','pdf','D'],'method'=>'post']) }}
                {{ Form::hidden('begin_date',Input::has('begin_date') ? Input::get('begin_date') : (new myDateTime())->setDateString($startOfDay)->format('Y-m-d')) }}
                {{ Form::hidden('end_date',Input::has('end_date') ? Input::get('end_date') : (new myDateTime())->setDateString($endOfDay)->format('Y-m-d')) }}
                {{ Form::hidden('client',Input::get('client')) }}
                {{ Form::hidden('project',Input::get('project')) }}
                {{ Form::hidden('user',Input::get('user')) }}
                {{ Form::submit('Просмотреть счет',['class'=>'btn btn-success']) }}
            {{ Form::close() }}
        @if(count($clients) > 1)
            {{ Form::open(['route'=>['report.save','pdf','F'],'method'=>'post']) }}
                {{ Form::hidden('begin_date',Input::has('begin_date') ? Input::get('begin_date') : (new myDateTime())->setDateString($startOfDay)->format('Y-m-d')) }}
                {{ Form::hidden('end_date',Input::has('end_date') ? Input::get('end_date') : (new myDateTime())->setDateString($endOfDay)->format('Y-m-d')) }}
                {{ Form::hidden('client',Input::get('client')) }}
                {{ Form::hidden('project',Input::get('project')) }}
                {{ Form::hidden('user',Input::get('user')) }}
                {{ Form::submit('Сохранить счет',['class'=>'btn btn-info']) }}
            {{ Form::close() }}
        @endif
            @include(Helper::acclayout('assets.invoice'),compact('tasks'))
        @else
            <p>Список пуст</p>
        @endif
        </div>
    </div>
@stop
@section('scripts') @stop