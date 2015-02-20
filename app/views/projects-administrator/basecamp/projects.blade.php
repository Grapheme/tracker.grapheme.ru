@extends(Helper::acclayout())
@section('style') @stop

@section('content')
    <h1 class="page-header">Basecamp: {{ @$account['account_name'] }}</h1>
    @if(count($projects))
        <div class="row">
            <h2>Выберите проекты для импорта</h2>
            {{ Form::open(array('route'=>array('basecamp.project.import'),'role'=>'form','method'=>'POST','class'=>'form-horizontal')) }}
                {{ Form::hidden('account_id',@$account['account_id']) }}
                {{ Form::hidden('account_name',@$account['account_name']) }}
            @foreach($projects as $project)
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            {{ Form::checkbox('projects[]',@$project['id'],FALSE) }} {{ HTML::link(@$project['app_url'],@$project['name'],['target'=>'_blank']) }}
                        </label>
                        <span id="helpBlock" class="help-block">{{ @$project['description'] }}</span>
                    </div>
                </div>
            @endforeach
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            {{ Form::checkbox('sync_people',1,TRUE) }} Синхронизировать пользователей {{ $basecamp_peoples_count ?  '('.$basecamp_peoples_count.' '.Lang::choice('человек|человека|человек',$basecamp_peoples_count).')' : '' }}
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            {{ Form::checkbox('sync_tasks',1,TRUE) }} Синхронизировать задачи
                        </label>
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