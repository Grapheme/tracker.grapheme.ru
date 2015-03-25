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
            <h2 class="sub-header">Список задач (от {{ (new myDateTime())->setDateString($startOfDay)->format('d.m.Y') }} до {{ (new myDateTime())->setDateString($endOfDay)->format('d.m.Y') }})</h2>
            @include(Helper::acclayout('assets.invoice'),compact('tasks'))
        </div>
    </div>
    @yield('scripts')
</body>
</html>