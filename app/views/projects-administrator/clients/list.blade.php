@extends(Helper::acclayout())
@section('style') @stop

@section('content')
    <h1 class="page-header">Список клиентов</h1>
    @if(count($clients))
    <div class="row placeholders">
        @foreach($clients as $client)
        <div class="col-xs-6 col-sm-3 placeholder">
            <a href="{{ URL::route('clients.show',$client->id) }}" class="">
                <img data-src="holder.js/200x200/auto/sky" class="img-responsive" alt="Generic placeholder thumbnail">
            </a>
            <a href="{{ URL::route('clients.show',$client->id) }}" class=""><h4>{{ $client->title }}</h4></a>
            <span class="text-muted">{{ $client->description }}</span>
            @if(count($client->projects))
            <br><span class="text-muted">{{ $client->projects->count() }} {{ Lang::choice('проект|проекта|проектов',$client->projects->count()) }}</span>
            @endif
        </div>
        @endforeach
    </div>
    @else

    @endif
@stop
@section('scripts')
{{ HTML::script(Config::get('site.theme_path').'/js/docs.min.js') }}
@stop