@extends(Helper::acclayout())
@section('style') @stop
@section('content')
    <div class="container marketing">
        <div class="row">
            <h1 class="page-header">Статистика</h1>
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
            <h2 class="sub-header">Список задач</h2>
            <div class="pull-left">
            @if(count($tasks))
                {{ Form::open(['route'=>['report.save','default','pdf','D'],'method'=>'post']) }}
                    {{ Form::hidden('begin_date',Input::has('begin_date') ? Input::get('begin_date') : (new myDateTime())->setDateString($startOfDay)->format('Y-m-d')) }}
                    {{ Form::hidden('end_date',Input::has('end_date') ? Input::get('end_date') : (new myDateTime())->setDateString($endOfDay)->format('Y-m-d')) }}
                    {{ Form::hidden('client',Input::get('client')) }}
                    {{ Form::hidden('project',Input::get('project')) }}
                    {{ Form::hidden('user',Input::get('user')) }}
                    {{ Form::submit('Экспорт в PDF',['class'=>'btn btn-success']) }}
                {{ Form::close() }}
            </div>
            <div class="pull-right">
            @if(count($clients) > 1)
                @if(Input::has('client') && Input::get('client') > 0)
                {{ Form::open(['route'=>['report.save','invoice','pdf','F'],'method'=>'post']) }}
                    {{ Form::hidden('begin_date',Input::has('begin_date') ? Input::get('begin_date') : (new myDateTime())->setDateString($startOfDay)->format('Y-m-d')) }}
                    {{ Form::hidden('end_date',Input::has('end_date') ? Input::get('end_date') : (new myDateTime())->setDateString($endOfDay)->format('Y-m-d')) }}
                    {{ Form::hidden('client',Input::get('client')) }}
                    {{ Form::hidden('project',Input::get('project')) }}
                    {{ Form::hidden('user',Input::get('user')) }}
                    {{ Form::submit('Сформирвать счет',['class'=>'btn btn-info']) }}
                {{ Form::close() }}
                @else
                <p class="text-info">Для сохранения счета выберите клиента.</p>
                @endif
            </div>
            <div class="clearfix"></div>
            @endif
                @include(Helper::acclayout('reports.tasks-lists'),['tasks'=>$tasks,'showTotal'=>TRUE])
            @else
            <p>Список пуст</p>
        @endif
        </div>
    </div>
@stop
@section('scripts') @stop