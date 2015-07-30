@extends(Helper::acclayout())
@section('style')
    {{ HTML::style(Config::get('site.theme_path').'/css/select2.min.css') }}
@stop

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
    {{ HTML::script(Config::get('site.theme_path').'/js/select2.min.js') }}
    <script type="application/javascript">
        $(function(){
            $("select").select2();
        });
    </script>
@stop