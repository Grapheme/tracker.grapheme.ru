<?php

class GuestController extends \BaseController {

	public function __construct(){

	}

	public function index(){



		return View::make(Helper::layout('index'));
	}

	public function register(){

		return View::make(Helper::layout('register'));
	}
}