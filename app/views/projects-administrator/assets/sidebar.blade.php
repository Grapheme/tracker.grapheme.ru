<ul class="nav nav-sidebar">
    <li {{ Helper::isRoute('project_admin.projects.index') }}>
        <a href="{{ URL::route('project_admin.projects.index') }}">Проекты <span class="badge">{{ Project::where('superior_id',Auth::user()->id)->count() }}</span></a>
    </li>
    <li {{ Helper::isRoute('project_admin.cooperators.index') }}>
        <a href="{{ URL::route('project_admin.cooperators.index') }}">Сотрудники <span class="badge">{{ Team::where('superior_id',Auth::user()->id)->count() }}</span></a>
    </li>
    <li {{ Helper::isRoute('project_admin.timesheets.index') }}>
        <a href="{{ URL::route('project_admin.timesheets.index') }}">Табель учета рабочего времени</a>
    </li>
</ul>
<ul class="nav nav-sidebar">
    <li {{ Helper::isRoute('project_admin.projects.create') }}>
        <a href="{{ URL::route('project_admin.projects.create') }}">Создать проект</a>
    </li>
    <li {{ Helper::isRoute('project_admin.cooperators.create') }}>
        <a href="{{ URL::route('project_admin.cooperators.create') }}">Добавить сотрудника</a>
    </li>
    <li {{ Request::has('now') ? Helper::isRoute('project_admin.timesheets.create') : '' }}>
        <a href="{{ URL::route('project_admin.timesheets.create',['date'=>date('Y-m-d'),'now'=>1]) }}">Добавить текущую задачу</a>
    </li>
</ul>