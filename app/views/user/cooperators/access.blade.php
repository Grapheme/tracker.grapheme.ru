@extends(Helper::acclayout())
@section('style') @stop

@section('content')
    <div class="jumbotron">
        @if(!empty($user->avatar) && File::exists(public_path($user->avatar->path)))
            <img src="{{ asset($user->avatar->path) }}" alt="">
        @endif
        <h1>{{ $user->fio }}</h1>
        <p class="lead">{{ $user->position }}</p>
    </div>
    @if(count($projects))
    <div class="table-responsive">
    {{ Form::open(['route'=>['cooperators.access.store',$user->id],'role'=>'form','class'=>'form-horizontal']) }}
        {{ Form::hidden('superior_id',$user->id) }}
        <table class="table table-striped">
            <tbody>
            @foreach($projects as $project_info)
                <tr>
                    <td>
                        {{ $project_info->project->title }}
                    </td>
                    <td>
                        <?php $setAdmin = count($project_info->project->owners) ? TRUE : FALSE; ?>
                        {{ Form::checkbox('owner['.$project_info->project->id.']',$user->id,$setAdmin,['autocomplete'=>'off']) }} Полный доступ
                    </td>
                    <td>
                        <?php $setAcceess = count($project_info->project->team) ? 1 : 0; ?>
                        <?php $setAcceess = $setAdmin ? 1 : $setAcceess; ?>
                        {{ Form::select('access['.$project_info->project->id.']',['Не доступен','Доступен'],$setAcceess,['class'=>'col-sm-6', 'autocomplete'=>'off']) }}
                    </td>
                    <td>
                        <?php $hour_price = !empty($project_info->project->client->hour_price) ? $project_info->project->client->hour_price : $user->hour_price; ?>
                        {{ Form::text('superior_hour_price['.$project_info->project->id.']',$hour_price?$hour_price:'',['class'=>'col-sm-5','placeholder'=>'Цена за час, руб']) }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ Form::submit('Применить',['class'=>'btn btn-success']) }}
    {{ Form::close() }}
    </div>
    @endif
@stop
@section('scripts')
    {{ HTML::script(Config::get('site.theme_path').'/js/docs.min.js') }}
@stop