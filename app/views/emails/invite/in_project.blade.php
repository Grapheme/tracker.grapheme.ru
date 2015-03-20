<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<div>
    </p>{{ Auth::user()->fio }} добавил Вас в проект <a href="{{ URL::route('projects.show',$project->id) }}">{{ $project->title }}</a></p>
</div>
</body>
</html>