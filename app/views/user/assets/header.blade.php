<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="{{ URL::route('home') }}">Tracker Grapheme</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="javascript:void(0);">{{ date("d.m.Y H:i") }}</a></li>
                <li><a href="{{ URL::route('dashboard') }}">Dashboard</a></li>
                <!--<li><a href="{{ URL::route('settings') }}">Настройка</a></li>-->
                <li><a href="{{ URL::route('profile') }}">Профиль</a></li>
                <li><a href="{{ URL::route('logout') }}">Выход</a></li>
            </ul>
        </div>
    </div>
</nav>