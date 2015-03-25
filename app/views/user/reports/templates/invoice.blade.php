@section('title'){{ '' }}@stop
@section('description'){{ '' }}@stop
@section('keywords'){{ '' }}@stop
        <!doctype html>
<html class="no-js">
<head>
    @yield('style')
</head>
<body>
<table border="1" cellpadding="0" cellspacing="0">
    <tbody>
    <tr>
        <td style="padding: 3pt 6pt 3pt 6pt;" valign="top" width="218">
            <p>ИНН 6164990894</p>
        </td>
        <td style="padding: 3pt;" width="218">
            <p>КПП 616401001</p>
        </td>
        <td style="padding: 3pt 6pt 3pt 6pt;" valign="top" width="218">
            <p>р/с №</p>
        </td>
        <td style="padding: 3pt 6pt 3pt 6pt;" rowspan="2" valign="top" width="218">
            <p>40703810626050000009</p>
        </td>
    </tr>
    <tr>
        <td style="padding: 3pt 6pt 3pt 6pt;" colspan="3" valign="top" width="654">
            <p>{{ Auth::user()->fio }}</p>

            <p>Получатель</p>
        </td>
    </tr>
    <tr>
        <td style="padding: 3pt 6pt 3pt 6pt;" rowspan="2" colspan="2" width="436">
            <p>Филиал "Ростовский" ОАО АЛЬФА-БАНК г. Ростов-на-Дону</p>

            <p>Банк получателя</p>
        </td>
        <td style="padding: 3pt 6pt 3pt 6pt;" valign="top" width="218">
            <p>БИК</p>
        </td>
        <td style="padding: 3pt 6pt 3pt 6pt;" rowspan="2" valign="top" width="218">
            <p>046015207</p>

            <p>30101810500000000207</p>
        </td>
    </tr>
    <tr>
        <td style="padding: 3pt;" width="218">
            <p>к/с №</p>
        </td>
    </tr>
    </tbody>
</table>
<h2> Счет № {{ @$report->id }} от {{ @$report->created_at->format('d.m.Y')}} </h2>
<table border="1" cellpadding="0" cellspacing="0">
    <tbody>
    <tr>
        <td style="padding: 3pt 6pt 3pt 6pt;" valign="top" width="108">
            <p>Поставщик:</p>
        </td>
        <td style="padding: 3pt 6pt 3pt 6pt;" valign="top" width="763">
            <p>
                <strong> {{ Auth::user()->fio }} </strong>
            </p>
        </td>
    </tr>
    </tbody>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="793">
    <tbody>
    <tr>
        <td style="padding: 3pt;" nowrap="" valign="top" width="98">
            <p>Покупатель:</p>
        </td>
        <td style="padding: 3pt;" valign="top" width="666">
            <p>{{ @$client->title}}</p>
        </td>
    </tr>
    </tbody>
</table>
<table class="table table-striped">
    <tbody>
    <tr>
        <td><p align="center"><strong>№ п/п</strong></p></td>
        <td><p align="center"><strong>Наименование задачи</strong></p></td>
        <td><p align="center"><strong>Дата выполнения</strong></p></td>
        <td><p align="center"><strong>Кол-во</strong></p></td>
        <td><p align="center"><strong>Ед.</strong></p></td>
        <td><p align="center"><strong>Цена (руб)</strong></p></td>
        <td><p align="center"><strong>Сумма (руб)</strong></p></td>
    </tr>
    <?php $tasks_total_time = 0;?>
    <?php $tasks_total_price = 0;?>
    <?php $earnMoneyCurrentDate = costCalculation(NULL, ['tasks' => $tasks]);?>
    <?php $index = 1;?>
    @foreach($tasks as $task)
        <?php $tasks_total_time += (getLeadTimeMinutes($task) + floor($task->lead_time / 60));?>
        @if(isset($earnMoneyCurrentDate[$task->id]['earnings']))
            <?php $tasks_total_price += $earnMoneyCurrentDate[$task->id]['earnings'];?>
        @endif
        <tr>
            <td><p align="center">{{ $index }}</p></td>
            <td>{{ $task->note }}</td>
            <td>{{ Carbon\Carbon::createFromFormat('Y-m-d 00:00:00',$task->set_date)->format('d.m.Y') }}</td>
            <td>1</td>
            <td>шт.</td>
            <td>{{ isset($earnMoneyCurrentDate[$task->id]['earnings']) ? number_format($earnMoneyCurrentDate[$task->id]['earnings'],2,'.',' ') : '' }}</td>
            <td>{{ isset($earnMoneyCurrentDate[$task->id]['earnings']) ? number_format($earnMoneyCurrentDate[$task->id]['earnings'],2,'.',' ') : '' }}</td>
        </tr>
        <?php $index++; ?>
    @endforeach
    </tbody>
</table>
<div align="right">
    <table style="float: right; margin-left: 50%; margin-bottom: 10%; width: 50%;" border="1" cellpadding="0"
           cellspacing="0" width="">
        <tbody>
        <tr>
            <td style="padding: 3pt;" nowrap="" valign="top" width="150">
                <p align="right">
                    <strong>Итого</strong>
                </p>
            </td>
            <td style="padding: 3pt;" nowrap="" valign="top" width="200">
                <p align="right">
                    <strong>{{ @$tasks_total_price }},00</strong>
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding: 3pt;" nowrap="" valign="top" width="133">
                <p align="right">
                    <strong>Сумма НДС</strong>
                </p>
            </td>
            <td style="padding: 3pt;" nowrap="" valign="top" width="115">
                <p align="right">
                    <strong>0,00</strong>
                </p>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<p>В поле "Назначение платежа" необходимо указать:</p>

<p>Оказание платных услуг</p>

<p>Всего наименований: {{ @$index-1 }}</p>

<p>на сумму {{ @$tasks_total_price }}.00 Руб</p>

<p>({{ price2str($tasks_total_price) }})</p>

<p>Руководитель /{{ Auth::user()->fio }}/</p>
@yield('scripts')
</body>
</html>