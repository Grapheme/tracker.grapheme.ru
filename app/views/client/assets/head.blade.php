@section('title')
    {{{ isset($page_title) ? $page_title : Config::get('site.default_page_title') }}}
@stop
@section('description')
    {{{ isset($page_description) ? $page_description : '' }}}
@stop
@section('keywords')
    {{{ isset($page_keywords) ? $page_keywords : '' }}}
@stop
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="@yield('description')">
<meta name="keywords" content="@yield('keywords')">
<link rel="icon" href="{{ Config::get('site.theme_path') }}/favicon.png">
<title>@yield('title')</title>

{{ HTML::style(Config::get('site.theme_path').'/css/bootstrap.min.css') }}
{{ HTML::style(Config::get('site.theme_path').'/css/main.css') }}

<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->