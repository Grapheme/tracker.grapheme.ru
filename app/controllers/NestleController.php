<?php

class NestleController extends \BaseController {

    public function __construct(){}

    public function category(){

        return View::make('nestle.category');
    }

    public function categoryCreate(){

        return Redirect::route('category.show')->withInput(Input::except('_token'));
    }

    public function product(){

        return View::make('nestle.product');
    }

    public function productCreate(){

        return Redirect::route('product.show')->withInput(Input::except('_token'));
    }
}