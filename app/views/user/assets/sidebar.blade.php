<?php
    $activeProjectCount = $archivedProjectCount = 0;
    foreach(ProjectOwners::where('user_id',Auth::user()->id)->with('projects')->get() as $project):
        if ($project->projects->in_archive):
            $archivedProjectCount++;
        else:
            $activeProjectCount++;
        endif;
    endforeach;
    foreach(ProjectTeam::where('user_id',Auth::user()->id)->with('projects')->get() as $project):
        if ($project->projects->in_archive):
            $archivedProjectCount++;
        else:
            $activeProjectCount++;
        endif;
    endforeach;
?>
<ul class="nav nav-sidebar">
    <li {{ Helper::isRoute('clients.index') }}>
        <a href="{{ URL::route('clients.index') }}">Клиенты <span class="badge">{{ Clients::where('superior_id',Auth::user()->id)->count() }}</span></a>
    </li>
    <li {{ Helper::isRoute('projects.index') }}>
        <a href="{{ URL::route('projects.index') }}">Проекты <span class="badge">{{ $activeProjectCount.'/'.$archivedProjectCount }}</span></a>
    </li>
    <li {{ Helper::isRoute('cooperators.index') }}>
        <a href="{{ URL::route('cooperators.index') }}">Команда <span class="badge">{{ count(Team::where('superior_id',Auth::user()->id)->orWhere('cooperator_id',Auth::user()->id)->groupBy('superior_id')->groupBy('cooperator_id')->get()) }}</span></a>
    </li>
    <li {{ Helper::isRoute('timesheets.index') }}>
        <a href="{{ URL::route('timesheets.index') }}">Табель учета рабочего времени</a>
    </li>
    @if(Clients::where('superior_id',Auth::user()->id)->exists())
    <li {{ Helper::isRoute('reports.list') }}>
        <a href="{{ URL::route('reports.list') }}">Мои счета</a>
    </li>
    @endif
</ul>
<ul class="nav nav-sidebar">
    <li {{ Helper::isRoute('clients.create') }}>
        <a href="{{ URL::route('clients.create') }}">Добавить клиента</a>
    </li>
    <li {{ Helper::isRoute('projects.create') }}>
        <a href="{{ URL::route('projects.create') }}">Добавить проект</a>
    </li>
    <li {{ Helper::isRoute('cooperators.create') }}>
        <a href="{{ URL::route('cooperators.create') }}">Пригласить сотрудника</a>
    </li>
    <li {{ Request::has('now') ? Helper::isRoute('timesheets.create') : '' }}>
        <a href="{{ URL::route('timesheets.create',['date'=>date('Y-m-d'),'now'=>1]) }}">Добавить текущую задачу</a>
    </li>
    <li {{ Helper::isRoute('report.create') }}>
        <a href="{{ URL::route('report.create') }}">Статистика</a>
    </li>
</ul>