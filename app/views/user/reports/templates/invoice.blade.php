@section('title'){{ '' }}@stop
@section('description'){{ '' }}@stop
@section('keywords'){{ '' }}@stop
<!doctype html>
<html class="no-js">
<head>
    @include(Helper::layout('assets.head'))
	@yield('style')
</head>
<body>
    <div class="container">
        <div class="row">
            <h1 class="sub-header">Счет</h1>
            @include(Helper::acclayout('reports.tasks-lists'),compact('tasks'))
        </div>
    </div>
    @yield('scripts')
</body>
</html>