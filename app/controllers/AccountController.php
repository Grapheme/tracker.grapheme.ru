<?php

class AccountController extends \BaseController {

	public function __construct(){

	}

	public function dashboard(){

		return View::make(Helper::acclayout('dashboard'));
	}

	public function settings(){

		return View::make(Helper::acclayout('settings'));
	}

	public function profile(){

		return View::make(Helper::acclayout('profile'));
	}
}