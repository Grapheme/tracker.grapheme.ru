@section('title'){{ Config::get('site.default_page_title') }}@stop
@section('description'){{ '' }}@stop
@section('keywords'){{ '' }}@stop
<!doctype html>
<html class="no-js">
<head>
	@include(Helper::acclayout('assets.head'))
	@yield('style')
</head>
<body>
    @include(Helper::acclayout('assets.header'))
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3 col-md-2 sidebar">
                @include(Helper::acclayout('assets.sidebar'))
            </div>
            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                @if(Session::has('message'))
                    <div class="alert alert-info" role="alert">{{ Session::get('message') }}</div>
                @endif
                @if(count($errors))
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            @foreach ($errors->all() as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @yield('content', @$content)
            </div>
        </div>
        @include(Helper::acclayout('assets.footer'))
    </div>
    @include(Helper::acclayout('assets.scripts'))
    @yield('scripts')
</body>
</html>