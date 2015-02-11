@extends(Helper::acclayout())
@section('style') @stop

@section('content')
    <h1 class="page-header">Basecamp: {{ @$account['account_name'] }}</h1>
    @if(count($projects))
        <div class="row">
            <h2>Выбирите проекты для импорта</h2>
            {{ Form::open(array('route'=>array('basecamp.project.import'),'role'=>'form','method'=>'POST','class'=>'form-horizontal')) }}
                {{ Form::hidden('account_id',@$account['account_id']) }}
                {{ Form::hidden('account_name',@$account['account_name']) }}
            @foreach($projects as $project)
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            {{ Form::checkbox('projects[]',@$project['id'],TRUE) }} {{ HTML::link(@$project['app_url'],@$project['name'],['target'=>'_blank']) }}
                        </label>
                        <span id="helpBlock" class="help-block">{{ @$project['description'] }}</span>
                    </div>
                </div>
            @endforeach
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            {{ Form::checkbox('sync_tasks',1,TRUE) }} Синхронизировать задачи
                        </label>
                        <span id="helpBlock" class="help-block">ПОКА НЕ РАБОТАЕТ</span>
                    </div>
                </div>
                {{ Form::submit('Импортировать и завершить',['class'=>'btn btn-success']) }}
            {{ Form::close() }}
        </div>
    @else

    @endif
@stop
@section('scripts')
    {{ HTML::script(Config::get('site.theme_path').'/js/docs.min.js') }}
@stop