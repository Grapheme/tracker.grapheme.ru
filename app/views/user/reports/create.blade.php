@extends(Helper::acclayout())
@section('style')
    {{ HTML::style(Config::get('site.theme_path').'/css/datapicker/jquery-ui-datapicker.css') }}
    {{ HTML::style(Config::get('site.theme_path').'/css/select2.min.css') }}
@stop
@section('content')
    <div class="container marketing">
        <div class="row">
            <h1 class="page-header">Статистика</h1>
            @include(Helper::acclayout('reports.forms.report-create'))
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
                @endif
            </div>
            <div class="clearfix"></div>
            @include(Helper::acclayout('reports.tasks-lists'),['tasks'=>$tasks,'showTotal'=>TRUE])
            @else
                <p>Список пуст</p>
            @endif
        </div>
    </div>
@stop
@section('scripts')
    {{ HTML::script(Config::get('site.theme_path').'/js/datepicker/jquery.ui.datepicker.js') }}
    {{ HTML::script(Config::get('site.theme_path').'/js/datepicker/jquery.ui.datepicker-ru.js') }}
    {{ HTML::script(Config::get('site.theme_path').'/js/select2.min.js') }}
    <script type="application/javascript">
        $("input.datepicker").datepicker({
            minDate: "01.01.2015",
            maxDate: "{{ date('d.m.Y') }}"
        });
        $("select").select2();
    </script>
@stop