@extends(Helper::acclayout())
@section('style') @stop

@section('content')
    <h1 class="page-header">Новый сотрудник</h1>
    <div class="row">
        <div class="col-md-8">
        {{ Form::open(array('route'=>'cooperators.store','role'=>'form','class'=>'form-horizontal')) }}
            <div class="form-group has-feedback">
                <label for="inputEmail" class="col-sm-3 control-label">Email</label>
                <div class="col-sm-4">
                    {{ Form::text('email',Input::old('email'),['class' => 'form-control','placeholder'=>'i.ivanov@mysite.com','required'=>'','id'=>'inputEmail','autofocus'=>'']) }}
                    <span class="glyphicon glyphicon-warning-sign form-control-feedback" aria-hidden="true"></span>
                    <span id="inputWarning2Status" class="sr-only">(warning)</span>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-success">Пригласить</button>
                    <a href="{{ URL::route('cooperators.index') }}" class="btn btn-default">Отмена</a>
                </div>
            </div>
        {{ Form::close() }}
        </div>
    </div>
@stop
@section('scripts')
@stop