<?php
$params['today']['begin_date'] = (new \Carbon\Carbon())->format('Y-m-d');
$params['today']['end_date'] = (new \Carbon\Carbon())->format('Y-m-d');

$params['week']['begin_date'] = (new \Carbon\Carbon())->startOfWeek()->format('Y-m-d');
$params['week']['end_date'] = (new \Carbon\Carbon())->endOfWeek()->format('Y-m-d');

$params['last_week']['begin_date'] = (new \Carbon\Carbon())->subWeek()->startOfWeek()->format('Y-m-d');
$params['last_week']['end_date'] = (new \Carbon\Carbon())->subWeek()->endOfWeek()->format('Y-m-d');

$params['month']['begin_date'] = (new \Carbon\Carbon())->startOfMonth()->format('Y-m-d');
$params['month']['end_date'] = (new \Carbon\Carbon())->endOfMonth()->format('Y-m-d');

$params['last_month']['begin_date'] = (new \Carbon\Carbon())->subMonth()->startOfMonth()->format('Y-m-d');
$params['last_month']['end_date'] = (new \Carbon\Carbon())->subMonth()->endOfMonth()->format('Y-m-d');

$params['year']['begin_date'] = (new \Carbon\Carbon())->startOfYear()->format('Y-m-d');
$params['year']['end_date'] = (new \Carbon\Carbon())->endOfYear()->format('Y-m-d');


$params['last_year']['begin_date'] = (new \Carbon\Carbon())->subYear()->startOfYear()->format('Y-m-d');
$params['last_year']['end_date'] = (new \Carbon\Carbon())->subYear()->endOfYear()->format('Y-m-d');

$params['all'] = [];
if (isset($extended) && !empty($extended)):
    foreach($extended as $index => $value):
        $params['today'][$index] = $value;
        $params['week'][$index] = $value;
        $params['last_week'][$index] = $value;
        $params['month'][$index] = $value;
        $params['last_month'][$index] = $value;
        $params['year'][$index] = $value;
        $params['last_year'][$index] = $value;
        $params['all'][$index] = $value;
    endforeach;
endif;

?>
<ul>
    <li><a href="{{ URL::route('report',$params['today']) }}">За сегодня</a></li>
    <li><a href="{{ URL::route('report',$params['week']) }}">За неделю</a></li>
    <li><a href="{{ URL::route('report',$params['last_week']) }}">За прошлую неделю</a></li>
    <li><a href="{{ URL::route('report',$params['month']) }}">За месяц</a></li>
    <li><a href="{{ URL::route('report',$params['last_month']) }}">За прошлый месяц</a></li>
    <li><a href="{{ URL::route('report',$params['year']) }}">За год</a></li>
    <li><a href="{{ URL::route('report',$params['last_year']) }}">За прошлый год</a></li>
    <li><a href="{{ URL::route('report',$params['all']) }}">За все время</a></li>
</ul>