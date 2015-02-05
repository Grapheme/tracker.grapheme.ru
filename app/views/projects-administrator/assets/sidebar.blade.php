<ul class="nav nav-sidebar">
    <li {{ Helper::isRoute('projects.index') }}>
        <a href="{{ URL::route('projects.index') }}">Проекты <span class="badge">{{ Project::where('superior_id',Auth::user()->id)->count() }}</span></a>
    </li>
    <li {{ Helper::isRoute('cooperators.index') }}>
        <a href="{{ URL::route('cooperators.index') }}">Сотрудники <span class="badge">{{ Team::where('superior_id',Auth::user()->id)->count() }}</span></a>
    </li>
    <li {{ Helper::isRoute('timesheets.index') }}>
        <a href="{{ URL::route('timesheets.index') }}">Табель учета рабочего времени</a>
    </li>
</ul>
<ul class="nav nav-sidebar">
    <li {{ Helper::isRoute('projects.create') }}>
        <a href="{{ URL::route('projects.create') }}">Создать проект</a>
    </li>
    <li {{ Helper::isRoute('cooperators.create') }}>
        <a href="{{ URL::route('cooperators.create') }}">Добавить сотрудника</a>
    </li>
    <li {{ Request::has('now') ? Helper::isRoute('timesheets.create') : '' }}>
        <a href="{{ URL::route('timesheets.create',['date'=>date('Y-m-d'),'now'=>1]) }}">Добавить текущую задачу</a>
    </li>
</ul>