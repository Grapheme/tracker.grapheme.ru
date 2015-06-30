<div class="container marketing">
    <div class="row">
        <h1 class="page-header">Форма для создания кода</h1>
        {{ Form::open(['route'=>'category.create','method'=>'post','role'=>'form','class'=>'form-horizontal']) }}
            <div class="form-group">
                <label class="col-sm-2 control-label">Подпись к изображениям (ALT)</label>

                <div class="col-sm-3">
                    {{ Form::text('image_alt','',['class'=>'form-control']) }}
                </div>
            </div>
            <hr>
            <div class="form-group">
                <label class="col-sm-2 control-label">Банер (URL)</label>

                <div class="col-sm-6">
                    {{ Form::text('banner_link','',['class'=>'form-control']) }}
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Таблица с описанием продуктов (URL)</label>

                <div class="col-sm-6">
                    {{ Form::text('products_list_xslx','',['class'=>'form-control']) }}
                </div>
            </div>
            <hr>
            <div id="element-template">
                <div class="product-info">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Фото продукта</label>
                        <div class="col-sm-6">
                            <input type="text" name="product_image[]" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Название продукта</label>
                        <div class="col-sm-6">
                            <input type="text" name="product_name[]" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Ссылка на страницу продукта</label>
                        <div class="col-sm-6">
                            <input type="text" name="product_page[]" class="form-control" />
                        </div>
                    </div>
                </div>
                <hr>
            </div>
            <div id="element-lists"></div>
            <div class="form-group">
                <label class="col-sm-2 control-label"></label>
                <div class="col-sm-4">
                    {{ Form::button('Добавить товар',['class'=>'btn btn-primary','id'=>'insert-product']) }}
                </div>
            </div>
            {{ Form::submit('Получить код',['class'=>'btn btn-success']) }}
        {{ Form::close() }}
    </div>
</div>