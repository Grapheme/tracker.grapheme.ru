@extends(Helper::layout())
@section('style')
    {{ HTML::style("//auth-prod.nestle.ru/Documents/products-style.css") }}
    {{ HTML::style("//auth-prod.nestle.ru/Documents/categories-styles.css") }}
    {{ HTML::style("theme/css/redactor.css") }}
    <style type="text/css">
        .redactor_box {
            height: 145px; !important;
        }
        .redactor_box .redactor_redactor {
            height: 155px; !important;
        }
    </style>
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
                    @include('nestle.assets.product.view', ['url_domen'=> $url_domen])
                </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="form">
            @include('nestle.assets.product.form')
        </div>
        <div role="tabpanel" class="tab-pane" id="code">
            <div id="nestle-code" class="hidden">
                @include('nestle.assets.product.view', ['url_domen'=> ''])
            </div>
            @include('nestle.assets.product.code')
        </div>
    </div>
@stop
@section('scripts')
    {{ HTML::script("//auth-prod.nestle.ru/Documents/products-scripts.js") }}
    {{ HTML::script("theme/js/redactor.js") }}
    {{ HTML::script("theme/js/redactor-config.js") }}
    <script type="text/javascript">
        $(document).ready(function(){
            $("#insert-product-photo").click(function () {
                var element_template = $("#product-template").html();
                $("#product-photo-lists").append(element_template);

            });
            $("#insert-recommended-product-photo").click(function () {
                var element_template = $("#recommended-product-template").html();
                $("#recommended-product-photo-lists").append(element_template);

            });
            $("#nestle-code-source").html($("#nestle-code").html().trim());
        });
        $("#copy-buffer").click(function(){
            $("#nestle-code-source").select();
            //document.execCommand("copy");
        });
    </script>
@stop