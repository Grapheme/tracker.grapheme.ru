@extends(Helper::acclayout())
@section('style') @stop

@section('content')
    <h1 class="page-header">Basecamp. Выберите аккаунт</h1>
    @if(count($accounts))
        <div class="row placeholders">
            @foreach($accounts as $account)
            {{ Form::open(array('route'=>array('basecamp.projects'),'method'=>'POST','style'=>'display:inline-block')) }}
                {{ Form::hidden('account_id',$account['id']) }}
                {{ Form::hidden('account_href',$account['href']) }}
                {{ Form::hidden('account_name',$account['name']) }}
                {{ Form::hidden('account_product',$account['product']) }}
                <div class="col-xs-12 col-sm-8 placeholder">
                    <img src="{{ asset('theme/img/basecamp_logo.png') }}" class="img-responsive" alt="{{ $account['name'] }}">
                    {{ Form::submit($account['name'],['class'=>'btn btn-link']) }}
                </div>
            {{ Form::close() }}
            @endforeach
        </div>
    @else

    @endif
@stop
@section('scripts')
    {{ HTML::script(Config::get('site.theme_path').'/js/docs.min.js') }}
@stop