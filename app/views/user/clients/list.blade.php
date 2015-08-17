@extends(Helper::acclayout())
@section('style') @stop

@section('content')
    <h1 class="page-header">Список клиентов</h1>
    @if(count($clients))
    <div class="row placeholders">
        @foreach($clients as $client)
        <div style="min-height: 300px;" class="col-xs-6 col-sm-3 placeholder">
            <a href="{{ URL::route('clients.show',$client->id) }}" class="">
            @if(!empty($client->logo) && File::exists(public_path($client->logo->path)))
                <img src="{{ asset($client->logo->path) }}" class="img-responsive" alt="">
            @else
                <img style="max-height: 220px" src="http://www.iscalio.com/cats/{{ rand(1, 355) }}.jpg" class="img-responsive" alt="">
            @endif
            </a>
            <a href="{{ URL::route('clients.show',$client->id) }}" class=""><h4>{{ !empty($client->short_title) ? $client->short_title : $client->title }}</h4></a>
            <span class="text-muted">{{ $client->description }}</span>
            @if(count($client->projects))
            <br><span class="text-muted">{{ $client->projects->count() }} {{ Lang::choice('проект|проекта|проектов',$client->projects->count()) }}</span>
            @endif
        </div>
        @endforeach
    </div>
    @endif
@stop
@section('scripts')
{{ HTML::script(Config::get('site.theme_path').'/js/docs.min.js') }}
@stop