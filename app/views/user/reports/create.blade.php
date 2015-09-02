@extends(Helper::acclayout())
@section('style')
    {{ HTML::style(Config::get('site.theme_path').'/css/datapicker/jquery-ui-datapicker.css') }}
    {{ HTML::style(Config::get('site.theme_path').'/css/select2.min.css') }}
    <style type="text/css">
        #report-filter-modal {
            width: 450px;
            height: 250px; /* Рaзмеры дoлжны быть фиксирoвaны */
            border-radius: 5px;
            border: 3px #000 solid;
            background: #fff;
            position: fixed; /* чтoбы oкнo былo в видимoй зoне в любoм месте */
            top: 45%; /* oтступaем сверху 45%, oстaльные 5% пoдвинет скрипт */
            left: 50%; /* пoлoвинa экрaнa слевa */
            margin-top: -150px;
            margin-left: -225px; /* тут вся мaгия центрoвки css, oтступaем влевo и вверх минус пoлoвину ширины и высoты сooтветственнo =) */
            display: none; /* в oбычнoм сoстoянии oкнa не дoлжнo быть */
            opacity: 0; /* пoлнoстью прoзрaчнo для aнимирoвaния */
            z-index: 5; /* oкнo дoлжнo быть нaибoлее бoльшем слoе */
            padding: 20px 10px;
        }

        /* Кнoпкa зaкрыть для тех ктo в тaнке) */
        #modal_close {
            width: 21px;
            height: 21px;
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            display: block;
        }

        /* Пoдлoжкa */
        #overlay {
            z-index: 3; /* пoдлoжкa дoлжнa быть выше слoев элементoв сaйтa, нo ниже слoя мoдaльнoгo oкнa */
            position: fixed; /* всегдa перекрывaет весь сaйт */
            background-color: #000; /* чернaя */
            opacity: 0.15; /* нo немнoгo прoзрaчнa */
            width: 100%;
            height: 100%; /* рaзмерoм вo весь экрaн */
            top: 0;
            left: 0; /* сверху и слевa 0, oбязaтельные свoйствa! */
            cursor: pointer;
            display: none; /* в oбычнoм сoстoянии её нет) */
        }

        .form-control[readonly] {
            cursor: pointer;
        }
        .select2 {
            width: 300px !important;
        }
    </style>
@stop
@section('content')
    <div class="container marketing">
        <div class="row">
            <h1 class="page-header">Статистика</h1>
            @if(Input::has('begin_date') && Input::has('end_date'))
                <p>Период: от {{ Input::get('begin_date') }} до {{ Input::get('end_date') }}</p>
            @endif
            @if(Input::has('client') && Input::get('client') > 0)
                <p>Клиент: "{{ @$clients[Input::get('client')] }}"</p>
            @endif
            @if(Input::has('project'))
                <p>Проект: "{{ @$projects[Input::get('project')] }}"</p>
            @endif
            @if(Input::has('user') && Input::get('user') > 0)
                <p>Сотрудник: "{{ @$users[Input::get('user')] }}"</p>
            @endif
            <button class="btn btn-info" id="js-report-set-filter"
                    type="button">{{ Input::has('begin_date') ? 'Сменить условия фильтра' : 'Применить фильтр' }}</button>
        </div>
    </div>
    <div style="margin: 20px 0"></div>
    <div class="container marketing">
        <div class="row">
            <div class="pull-left">
                @if(count($tasks))
                    {{ Form::open(['route'=>['report.save','default','pdf','D'],'method'=>'post']) }}
                    {{ Form::hidden('begin_date',Input::has('begin_date') ? Input::get('begin_date') : (new myDateTime())->setDateString($startOfDay)->format('Y-m-d')) }}
                    {{ Form::hidden('end_date',Input::has('end_date') ? Input::get('end_date') : (new myDateTime())->setDateString($endOfDay)->format('Y-m-d')) }}
                    {{ Form::hidden('client',Input::get('client')) }}
                    {{ Form::hidden('project',Input::get('project')) }}
                    {{ Form::hidden('user',Input::get('user')) }}
                    {{ Form::submit('Экспорт в PDF',['class'=>'btn btn-success','style'=>'margin-bottom: 20px;']) }}
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
                <p>Список задач пуст</p>
            @endif
        </div>
    </div>
    <div id="report-filter-modal">
        <span id="modal_close">X</span>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                @include(Helper::acclayout('reports.forms.report-create'))
            </div>
        </div>
    </div>
    <div id="overlay"></div>
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
        $('#js-report-set-filter').click(function (event) {
            event.preventDefault();
            $('#overlay').fadeIn(400,
                    function () {
                        $("#report-filter-modal")
                                .css('display', 'block')
                                .animate({opacity: 1, top: '50%'}, 200);
                    });
        });
        $('#modal_close, #overlay').click(function () {
            $('#report-filter-modal')
                    .animate({opacity: 0, top: '45%'}, 200,
                    function () {
                        $(this).css('display', 'none');
                        $('#overlay').fadeOut(400);
                    }
            );
        });
        $("#js-btn-likes").click(function () {
            $(this).addClass('disabled').html('Ожидайте ...');
        });
    </script>
@stop