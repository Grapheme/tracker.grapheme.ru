@extends(Helper::acclayout())
@section('style') @stop

@section('content')
    <h1 class="page-header">Новая задача</h1>
    <div class="row">
        <div class="col-md-8">
        @if (Request::has('date'))
            <?php $dt_request = Request::get('date'); ?>
        @else
            <?php $dt_request = date('Y-m-d'); ?>
        @endif
        @include(Helper::acclayout('timesheets.forms.create'))
        </div>
    </div>
@stop
@section('scripts')
@stop