@extends(Helper::acclayout())
@section('style') @stop

@section('content')
    <h1 class="page-header">Список сотрудников</h1>
    @if(!empty($users))
    <div class="row placeholders">
        @foreach($users as $user)
        <div class="col-xs-6 col-sm-3 placeholder">
            <a href="{{ URL::route('project_admin.cooperators.show',$user->cooperator->id) }}" class="">
                <img data-src="holder.js/200x200/auto/sky" class="img-responsive" alt="Generic placeholder thumbnail">
            </a>
            <a href="{{ URL::route('project_admin.cooperators.show',$user->cooperator->id) }}" class=""><h4>{{ $user->cooperator->fio }}</h4></a>
            <span class="text-muted">{{ $user->cooperator->position }}</span>
            @if( $user->cooperator->tasks->count())
                <br><span class="text-muted">{{  $user->cooperator->tasks->count() }} {{ Lang::choice('задача|задачи|задач', $user->cooperator->tasks->count()) }}</span>
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