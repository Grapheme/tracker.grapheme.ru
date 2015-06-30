<?php $input = Input::old(); ?>
<div class="box">
    <p class="boxContent">
        <img alt="{{ $input['image_alt'] }}" src="{{ $url_domen }}{{ $input['banner_link'] }}">&nbsp;
    </p>
</div>
<div class="grphm-search"><input type="text"> <a href="#">Найти</a></div>
<a class="grphm-link" href="{{ $url_domen }}{{ $input['products_list_xslx'] }}">Таблица с описанием продуктов (Excel)</a>
<div class="w490 catalog grphm">
@if(count($input['product_name']))
    @foreach($input['product_name'] as $index => $product_name)
        @if(!empty($product_name))
    <div class="unit">
        <div class="visual">
            <img alt="{{ $input['image_alt'] }}" src="{{ $url_domen }}{{ $input['product_image'][$index] }}"> &nbsp;
        </div>
        <a class="title" href="{{ $url_domen }}{{ $input['product_page'][$index] }}">{{ $product_name }}</a>
        <div class="descriptions">&nbsp;</div>
    </div>
        @endif
    @endforeach
@endif
</div>
