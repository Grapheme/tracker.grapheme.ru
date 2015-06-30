@extends(Helper::layout())
@section('style')
    {{ HTML::style("//auth-prod.nestle.ru/Documents/categories-styles.css") }}
@stop
<?php $url_domen = 'https://auth-prod.nestle.ru';?>
@section('content')
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#view" aria-controls="home" role="tab" data-toggle="tab">Просмотр</a>
        </li>
        <li role="presentation"><a href="#form" aria-controls="profile" role="tab" data-toggle="tab">Форма</a></li>
        <li role="presentation"><a href="#code" aria-controls="messages" role="tab" data-toggle="tab">Код</a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="view">
            <div class="container marketing">
                <div class="row">
                    <h1 class="page-header">Предварительный просмотр</h1>
                    @include('nestle.assets.category.view', ['url_domen'=> $url_domen])
                </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="form">
            @include('nestle.assets.category.form')
        </div>
        <div role="tabpanel" class="tab-pane" id="code">
            <div id="nestle-code" class="hidden">
                @include('nestle.assets.category.view', ['url_domen'=> ''])
            </div>
            @include('nestle.assets.category.code')
        </div>
    </div>
@stop
@section('scripts')
    {{ HTML::style("//auth-prod.nestle.ru/Documents/categories-scripts.js") }}
    <script type="text/javascript">
        $(document).ready(function(){
            $("#insert-product").click(function () {
                var element_template = $("#element-template").html();
                $("#element-lists").append(element_template);

            });
            $("#nestle-code-source").html($("#nestle-code").html().trim());
        });
        $("#copy-buffer").click(function(){
            $("#nestle-code-source").select();
            //document.execCommand("copy");
        });
    </script>
@stop