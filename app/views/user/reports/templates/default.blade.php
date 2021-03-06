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
            <h2 class="sub-header">Список задач</h2>
            @include(Helper::acclayout('reports.tasks-lists'),['tasks'=>$tasks,'showTotal'=>TRUE])
        </div>
    </div>
    @yield('scripts')
</body>
</html>