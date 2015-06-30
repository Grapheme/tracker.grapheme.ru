<div class="container marketing">
    <div class="row">
        <h1 class="page-header">Форма для создания кода</h1>
        {{ Form::open(['route'=>'product.create','method'=>'post','role'=>'form','class'=>'form-horizontal']) }}
        <div class="form-group">
            <label class="col-sm-2 control-label">Подпись к изображениям (ALT)</label>

            <div class="col-sm-3">
                {{ Form::text('image_alt','',['class'=>'form-control']) }}
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Таблица с описанием продукта (URL)</label>
            <div class="col-sm-6">
                {{ Form::text('product_xslx','',['class'=>'form-control']) }}
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Фотографии высокого качества (URL)</label>
            <div class="col-sm-6">
                {{ Form::text('product_zip','',['class'=>'form-control']) }}
            </div>
        </div>
        <hr>
        <div class="form-group">
            <label class="col-sm-2 control-label">Название продукта</label>
            <div class="col-sm-6">
                {{ Form::text('products_name','',['class'=>'form-control']) }}
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Описание</label>
            <div class="col-sm-6">
                {{ Form::textarea('product_description','',['class'=>'form-control redactor']) }}
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Состав и аллергическая информация</label>
            <div class="col-sm-6">
                {{ Form::textarea('product_composition','',['class'=>'form-control redactor']) }}
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Свойства и преимущества</label>
            <div class="col-sm-6">
                {{ Form::textarea('product_advantages','',['class'=>'form-control redactor']) }}
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Пищевая ценность на 100 г</label>
            <div class="col-sm-6">
                {{ Form::textarea('nutritional','',['class'=>'form-control redactor']) }}
            </div>
        </div>
        <hr>
        <div class="form-group">
            <label class="col-sm-2 control-label">Сап-код</label>
            <div class="col-sm-3">
                {{ Form::text('sap_code','',['class'=>'form-control']) }}
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Штрих-код</label>
            <div class="col-sm-3">
                {{ Form::text('bar_code','',['class'=>'form-control']) }}
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Длина</label>
            <div class="col-sm-2">
                {{ Form::text('product_length','',['class'=>'form-control']) }}
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Ширина</label>
            <div class="col-sm-2">
                {{ Form::text('product_width','',['class'=>'form-control']) }}
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Высота</label>
            <div class="col-sm-2">
                {{ Form::text('product_height','',['class'=>'form-control']) }}
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Вес</label>
            <div class="col-sm-2">
                {{ Form::text('product_heft','',['class'=>'form-control']) }}
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Вложимость</label>
            <div class="col-sm-2">
                {{ Form::text('imbeddability','',['class'=>'form-control']) }}
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Срок годности</label>
            <div class="col-sm-2">
                {{ Form::text('shelf_life','',['class'=>'form-control']) }}
            </div>
        </div>
        <hr>
        <div class="form-group">
            <label class="col-sm-2 control-label">Основное фото продукта</label>
            <div class="col-sm-6">
                <input type="text" name="product_big_image" class="form-control" />
            </div>
        </div>
        <div id="product-template">
            <div class="form-group">
                <label class="col-sm-2 control-label">Малые фото продукта</label>
                <div class="col-sm-6">
                    <input type="text" name="product_small_images[]" class="form-control" />
                </div>
            </div>
        </div>
        <div id="product-photo-lists"></div>
        <div class="form-group">
            <label class="col-sm-2 control-label"></label>
            <div class="col-sm-4">
                {{ Form::button('Добавить малое фото',['class'=>'btn btn-primary','id'=>'insert-product-photo']) }}
            </div>
        </div>
        <hr>
        <div id="recommended-product-template">
            <div class="form-group">
                <label class="col-sm-2 control-label">Фото продукта</label>
                <div class="col-sm-6">
                    <input type="text" name="recommended_product_image[]" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Название продукта</label>
                <div class="col-sm-6">
                    <input type="text" name="recommended_product_name[]" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Ссылка на страницу продукта</label>
                <div class="col-sm-6">
                    <input type="text" name="recommended_product_page[]" class="form-control" />
                </div>
            </div>
        </div>
        <div id="recommended-product-photo-lists"></div>
        <div class="form-group">
            <label class="col-sm-2 control-label"></label>
            <div class="col-sm-4">
                {{ Form::button('Добавить рекомендованый продукт',['class'=>'btn btn-primary','id'=>'insert-recommended-product-photo']) }}
            </div>
        </div>
        <hr>
        {{ Form::submit('Получить код',['class'=>'btn btn-success']) }}
        {{ Form::close() }}
    </div>
</div>