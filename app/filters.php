<?php

App::before(function($request){
	//
});


App::after(function($request, $response){
	//
});

App::missing(function ($exception) {
	if (in_array(Request::segment(1),Group::lists('dashboard'))):
		if (Auth::guest()):
			Session::set('redirect_to',Request::path());
			return Redirect::route('home');
		endif;
	endif;
	return Response::view('error404', array('message'=>$exception->getMessage()), 404);
});

Route::filter('auth', function(){

	if (Auth::guest()):
		if (Request::ajax()):
			return Response::make('Unauthorized', 401);
		else:
			return Redirect::route('home');
		endif;
	endif;
});

Route::filter('auth.projectAdministrator', function(){
	if(!isProjectAdministrator()):
		return Redirect::route('home');
	endif;
});

Route::filter('auth.performer', function(){
	if(!isPerformer()):
		return Redirect::route('home');
	endif;
});

Route::filter('auth.basic', function(){
	return Auth::basic();
});

Route::filter('guest', function(){
	if (Auth::check())
		return Redirect::to(AuthAccount::getGroupStartUrl());
});

Route::filter('csrf', function(){
	if (Session::token() !== Input::get('_token')){
		throw new Illuminate\Session\TokenMismatchException;
	}
});
