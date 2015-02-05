<ul class="nav nav-sidebar">
    <li {{ Helper::isRoute('timesheets.index') }}>
        <a href="{{ URL::route('timesheets.index') }}">Табель учета рабочего времени</a>
    </li>
</ul>
<ul class="nav nav-sidebar">
    <li {{ Request::has('now') ? Helper::isRoute('timesheets.create') : '' }}>
        <a href="{{ URL::route('timesheets.create',['date'=>date('Y-m-d'),'now'=>1]) }}">Добавить текущую задачу</a>
    </li>
</ul>