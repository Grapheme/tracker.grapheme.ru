<?php

class AuthAccount {
	
    public static function getStartPage($url = NULL){

		$StartPage = '';
		if(Auth::check()):
			$StartPage = Auth::user()->group->dashboard;
		endif;
		if(!is_null($url)):
			return $StartPage.'/'.$url;
		else:
			return $StartPage;
		endif;
	}
	
	public static function getGroupID(){
		
		if(Auth::check()):
			return Auth::user()->group->id;
		else:
			return FALSE;
		endif;
	}

    public static function getGroupName(){

        if(Auth::check()):
            return Auth::user()->group->name;
        else:
            return '';
        endif;
    }

	public static function getGroupStartUrl(){

		if(Auth::check()):
            return Auth::user()->group->dashboard;
		else:
			return URL::route('home');
		endif;
	}
	
}
