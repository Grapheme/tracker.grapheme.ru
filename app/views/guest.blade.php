@section('title'){{ Config::get('site.default_page_title') }}@stop
@section('description'){{ '' }}@stop
@section('keywords'){{ '' }}@stop
<!doctype html>
<html class="no-js">
<head>
	@include(Helper::layout('assets.head'))
	@yield('style')
</head>
<body>
    @include(Helper::layout('assets.header'))
    @yield('register')
    <div class="container">
        @yield('content', @$content)
        <hr>
        @include(Helper::layout('assets.footer'))
    </div>
    @include(Helper::layout('assets.scripts'))
    @yield('scripts')
</body>
</html>