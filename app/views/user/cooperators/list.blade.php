@extends(Helper::acclayout())
@section('style') @stop

@section('content')
    <h1 class="page-header">Список сотрудников</h1>
    @if(!empty($users))
    <div class="row placeholders">
        @foreach($users as $user)
            @if($user->excluded == 1) <?php continue;?> @endif
            @if($user->cooperator->id == Auth::user()->id)
                <?php $type = 'superior'; ?>
            @else
                <?php $type = 'cooperator'; ?>
            @endif
        <div style="min-height: 300px;" class="col-xs-6 col-sm-3 placeholder">
            <a href="{{ URL::route('cooperators.show',$user->$type->id) }}" class="">
                @if(!empty($user->$type->avatar) && File::exists(public_path($user->$type->avatar->path)))
                <img src="{{ asset($user->$type->avatar->path) }}" class="img-responsive" alt="">
                @else
                    <img style="max-height: 220px" src="http://www.iscalio.com/cats/{{ rand(1, 355) }}.jpg" class="img-responsive" alt="">
                @endif
            </a>
            <a href="{{ URL::route('cooperators.show',$user->$type->id) }}" class=""><h4>{{ $user->$type->fio }}</h4></a>
            @if(!empty($user->$type->position))<span class="text-muted">{{ $user->$type->position }}</span><br>@endif
            @if( $user->$type->tasks->count() && $type == 'cooperator')
                <span class="text-muted">{{  $user->$type->tasks->count() }} {{ Lang::choice('задача|задачи|задач', $user->$type->tasks->count()) }}</span>
            @endif
        </div>
        @endforeach
    </div>
    @endif
    @if($invites->count())
        <h1 class="page-header">Заявки</h1>
        <div class="row">
            <div class="table-responsive">
                <table class="table table-striped">
                    <tbody>
                    @foreach($invites as $invite)
                        <tr>
                            <td>{{ $invite->email }}</td>
                            <td>{{ $invite->created_at->format("d.m.Y H:i") }}</td>
                            <td>
                            {{ Form::open(array('route'=>array('cooperators.invite_reject',$invite->id),'method'=>'DELETE','style'=>'display:inline-block')) }}
                                {{ Form::submit('Отклонить',['class'=>'btn btn-danger']) }}
                            {{ Form::close() }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@stop
@section('scripts')
{{ HTML::script(Config::get('site.theme_path').'/js/docs.min.js') }}
@stop