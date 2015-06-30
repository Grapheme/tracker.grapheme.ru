<?php $input = Input::old(); ?>
<div>
    <a class="grphm-top-link" href="{{ $url_domen }}{{ $input['product_xslx'] }}">Таблица с описанием продукта (Excel)</a>
    <a class="grphm-top-link" href="{{ $url_domen }}{{ $input['product_zip'] }}">Фотографии высокого качества (ZIP)</a>
</div>
<h2>{{ $input['products_name'] }}</h2>
<div class="grphm">
    <div class="visuals">
        <div class="big">
            <a class="fancybox" rel="group" href="{{ $url_domen }}{{ $input['product_big_image'] }}">
                <img src="{{ $url_domen }}{{ $input['product_big_image'] }}" alt="{{ $input['image_alt'] }}">
            </a>
        </div>
        <div class="small">
    @if(count($input['product_small_images']))
        @foreach($input['product_small_images'] as $index => $product_small_image)
            @if(!empty($product_small_image))
            <a href="{{ $url_domen }}{{ $product_small_image }}">
                <img alt="{{ $input['image_alt'] }}" src="{{ $url_domen }}{{ $product_small_image }}">
            </a>
            @endif
        @endforeach
    @endif
        </div>
    </div>
    <h3>Описание</h3>
    <p><strong>{{ $input['product_description'] }}</strong></p>
    <h3>Основная информация </h3>
    <ul>
        <li>Вес: {{ $input['product_heft'] }} гр</li>
        <li>Габариты: {{ $input['product_length'] }}х{{ $input['product_width'] }}х{{ $input['product_height'] }} (ДхШхВ)</li>
        <li>Страна производства: Россия</li>
        <li>Контактная информация о компании: <br>
            Единый электронный адрес: <br>
            contact@ru.nestle.com <br>
            Телефон: 8 800 200 7 200 <br>
            Интернет сайт: www.nestle.ru
        </li>
    </ul>
    <h3>Состав и аллергическая информация </h3>
    {{ $input['product_composition'] }}
    <h3>Свойства и преимущества </h3>
    {{ $input['product_advantages'] }}
    <div class="frame">
        <div class="title">
            Пищевая ценность на 100 г:
        </div>
        <div class="content2">
            {{ $input['nutritional'] }}
        </div>
    </div>
    <div class="frame toggler">
        <div class="title"><span class="holder">Техническая информация</span></div>
        <ol class="toggle-content">
            <li>
                <ul>
                    Информация о продукте
                    <li>Сап-код: {{ $input['sap_code'] }}</li>
                    <li>Штрих-код единицы: {{ $input['bar_code'] }}</li>
                    <li>Длина шт: {{ $input['product_length'] }}мм</li>
                    <li>Ширина шт: {{ $input['product_width'] }}мм</li>
                    <li>Высота шт: {{ $input['product_height'] }}мм</li>
                    <li>Вес шт: {{ $input['product_heft'] }}г</li>
                    <li>Вложимость (кол-во штук в коробе): {{ $input['imbeddability'] }}</li>
                    <li>Общий срок годности: {{ $input['shelf_life'] }} мес.</li>
                    <li>Страна производства: Россия</li>
                </ul>
            </li>
        </ol>
    </div>
@if(isset($input['recommended_product_name'][0]) && !empty($input['recommended_product_name'][0]))
    <h3>С этим товаром также смотрят</h3>
    <div class="w490 catalog grphm">
        @foreach($input['recommended_product_name'] as $index => $product_name)
            @if(!empty($product_name))
                <div class="unit">
                    <div class="visual">
                        <img alt="{{ $input['image_alt'] }}" src="{{ $url_domen }}{{ $input['recommended_product_image'][$index] }}"> &nbsp;
                    </div>
                    <a class="title" href="{{ $url_domen }}{{ $input['recommended_product_page'][$index] }}">{{ $product_name }}</a>
                    <div class="descriptions">&nbsp;</div>
                </div>
            @endif
        @endforeach
    </div>
@endif
</div>