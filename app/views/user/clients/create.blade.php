@extends(Helper::acclayout())
@section('style') @stop

@section('content')
    <h1 class="page-header">Новый клиент</h1>
    <div class="row">
        <div class="col-md-8">
        {{ Form::open(array('route'=>'clients.store','role'=>'form','class'=>'form-horizontal','files'=>TRUE)) }}
            {{ Form::hidden('superior_id',Auth::user()->id) }}
            <div class="form-group has-feedback">
                <label for="inputTitle" class="col-sm-3 control-label">Полное название</label>
                <div class="col-sm-4">
                    {{ Form::text('title',Input::old('title'),['class'=>'form-control','placeholder'=>'','id'=>'inputTitle','autofocus'=>'','required'=>'']) }}
                    <span class="glyphicon glyphicon-warning-sign form-control-feedback" aria-hidden="true"></span>
                    <span id="inputWarning2Status" class="sr-only">(warning)</span>
                </div>
            </div>
            <div class="form-group has-feedback">
                <label for="inputShortTitle" class="col-sm-3 control-label">Краткое название</label>
                <div class="col-sm-4">
                    {{ Form::text('short_title',Input::old('short_title'),['class'=>'form-control','placeholder'=>'','id'=>'inputShortTitle']) }}
                </div>
            </div>
            <div class="form-group has-feedback">
                <label for="inputAddress" class="col-sm-3 control-label">Адрес</label>
                <div class="col-sm-6">
                    {{ Form::textarea('address',Input::old('address'),['class'=>'form-control','placeholder'=>'','id'=>'inputAddress','rows'=>2]) }}
                </div>
            </div>
            <div class="form-group has-feedback">
                <label for="inputPhone" class="col-sm-3 control-label">Телефон</label>
                <div class="col-sm-4">
                    {{ Form::text('phone',Input::old('phone'),['class'=>'form-control','placeholder'=>'','id'=>'inputPhone']) }}
                </div>
            </div>
            <div class="form-group has-feedback">
                <label for="inputEmail" class="col-sm-3 control-label">Email</label>
                <div class="col-sm-4">
                    {{ Form::email('email',Input::old('email'),['class'=>'form-control','placeholder'=>'','id'=>'inputEmail']) }}
                </div>
            </div>
            <div class="form-group has-feedback">
                <label for="priceHour" class="col-sm-3 control-label">Цена за час</label>
                <div class="col-sm-4">
                    {{ Form::text('hour_price',Input::old('hour_price'),['class'=>'form-control','placeholder'=>'Цена за час для владельца']) }}
                </div>
            </div>
            <div class="form-group has-feedback">
                <label for="inputBank" class="col-sm-3 control-label">Банк</label>
                <div class="col-sm-4">
                    {{ Form::text('bank',Input::old('bank'),['class'=>'form-control','placeholder'=>'','id'=>'inputBank']) }}
                </div>
            </div>
            <div class="form-group has-feedback">
                <label for="inputPaymentAccount" class="col-sm-3 control-label">Расчетный счет</label>
                <div class="col-sm-4">
                    {{ Form::text('payment_account',Input::old('payment_account'),['class'=>'form-control','placeholder'=>'','id'=>'inputPaymentAccount']) }}
                </div>
            </div>
            <div class="form-group has-feedback">
                <label for="inputFioSignature" class="col-sm-3 control-label">ФИО для подписи</label>
                <div class="col-sm-4">
                    {{ Form::text('fio_signature',Input::old('fio_signature'),['class'=>'form-control','placeholder'=>'','id'=>'inputFioSignature']) }}
                </div>
            </div>
            <div class="form-group has-feedback">
                <label for="inputContactPerson" class="col-sm-3 control-label">Должность контактного лица</label>
                <div class="col-sm-4">
                    {{ Form::text('contact_person',Input::old('contact_person'),['class'=>'form-control','placeholder'=>'Должность','id'=>'inputContactPersonPosition']) }}
                </div>
            </div>
            <div class="form-group has-feedback">
                <label for="inputContactPerson" class="col-sm-3 control-label">Имя контактного лица</label>
                <div class="col-sm-4">
                    {{ Form::text('fio',Input::old('fio'),['class'=>'form-control','placeholder'=>'Имя','id'=>'inputContactPersonFio']) }}
                </div>
            </div>
            <div class="form-group has-feedback">
                <label for="inputContactPerson" class="col-sm-3 control-label">Имя контактного лица в род.падеже</label>
                <div class="col-sm-4">
                    {{ Form::text('fio_rod',Input::old('fio_rod'),['class'=>'form-control','placeholder'=>'Имя в род.падеже','id'=>'inputContactPersonFioRod']) }}
                </div>
            </div>
            <div class="form-group has-feedback">
                <label for="inputInn" class="col-sm-3 control-label">ИНН</label>
                <div class="col-sm-4">
                    {{ Form::text('inn',Input::old('inn'),['class'=>'form-control','placeholder'=>'','id'=>'inputInn']) }}
                </div>
            </div>
            <div class="form-group has-feedback">
                <label for="inputKpp" class="col-sm-3 control-label">КПП</label>
                <div class="col-sm-4">
                    {{ Form::text('kpp',Input::old('kpp'),['class'=>'form-control','placeholder'=>'','id'=>'inputKpp']) }}
                </div>
            </div>
            <div class="form-group has-feedback">
                <label for="inputOgrn" class="col-sm-3 control-label">ОГРН</label>
                <div class="col-sm-4">
                    {{ Form::text('ogrn',Input::old('ogrn'),['class'=>'form-control','placeholder'=>'','id'=>'inputOgrn']) }}
                </div>
            </div>
            <div class="form-group has-feedback">
                <label for="inputOkpo" class="col-sm-3 control-label">ОКПО</label>
                <div class="col-sm-4">
                    {{ Form::text('okpo',Input::old('okpo'),['class'=>'form-control','placeholder'=>'','id'=>'inputOkpo']) }}
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Лого</label>
                <div class="col-sm-2">
                    {{ Form::file('logo') }}
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-success">Создать</button>
                    <a href="{{ URL::route('projects.index') }}" class="btn btn-default">Отмена</a>
                </div>
            </div>
        {{ Form::close() }}
        </div>
    </div>
@stop
@section('scripts')
@stop