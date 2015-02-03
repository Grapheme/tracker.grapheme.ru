@extends(Helper::layout())
@section('style') @stop
@section('register')
<div class="jumbotron">
    <div class="container">
        <h1>Tracker Grapheme</h1>
        <p>Система контроля выполнения работ.</p>
        <p><a class="btn btn-primary btn-lg" href="{{ URL::route('register') }}" role="button">Зарегистрироваться &raquo;</a></p>
    </div>
</div>
@stop
@section('content')
<div class="row">
    <div class="col-md-4">
        <h2>Новость №1</h2>
        <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
    </div>
    <div class="col-md-4">
        <h2>Новость №2</h2>
        <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
    </div>
    <div class="col-md-4">
        <h2>Новость №3</h2>
        <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
    </div>
</div>
@stop
@section('scripts') @stop