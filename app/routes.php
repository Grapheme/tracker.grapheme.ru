<?php

Route::get('/',array('as'=>'home','uses' => 'GuestController@index'));
Route::any('logout',array('as'=>'logout','uses' => 'GlobalController@logout'));

Route::group(array('before' => 'guest'), function(){
    Route::post('login',array('as'=>'login','uses' => 'GlobalController@login'));
    Route::get('register',array('as'=>'register','uses' => 'GuestController@register'));
    Route::post('register',array('before' => 'csrf', 'as'=>'register-store','uses' => 'GlobalController@register'));
});

Route::group(array('before' => 'auth'), function(){
    Route::post('oauth/register', array('as'=>'oauth.register','uses' => 'GlobalController@oauth_url'));
    Route::get('oauth/callback', array('as'=>'oauth.callback','uses' => 'GlobalController@oauth_callback'));
});

if (Auth::check()):
    foreach(Group::all() as $group):
        $prefixes[$group->slug] = $group->dashboard;
    endforeach;
    $prefix = AuthAccount::getGroupStartUrl();
    Route::group(array('before' => 'auth','prefix' => @$prefix), function(){
        Route::get('',array('as'=>'dashboard','uses'=>'AccountController@dashboard'));
        Route::get('settings',array('as'=>'settings','uses'=>'AccountController@settings'));
        Route::get('profile',array('as'=>'profile','uses'=>'AccountController@profile'));
    });
    Route::group(array('before' => 'auth','prefix' => @$prefixes['admin-projects']), function(){
        if(isProjectAdministrator()):
            Route::resource('projects', 'ProjectsController',
                array(
                    'names' => array(
                        'index'   => 'projects.index',
                        'show'    => 'projects.show',
                        'create'  => 'projects.create',
                        'store'   => 'projects.store',
                        'edit'    => 'projects.edit',
                        'update'  => 'projects.update',
                        'destroy' => 'projects.destroy'
                    )
                )
            );
            Route::resource('officers', 'CooperatorsController',
                array(
                    'names' => array(
                        'index'   => 'cooperators.index',
                        'show'    => 'cooperators.show',
                        'create'  => 'cooperators.create',
                        'store'   => 'cooperators.store',
                        'edit'    => 'cooperators.edit',
                        'update'  => 'cooperators.update',
                        'destroy' => 'cooperators.destroy'
                    )
                )
            );
            Route::resource('timesheets', 'TimeSheetsController',
                array(
                    'except' => array('show'),
                    'names' => array(
                        'index'   => 'timesheets.index',
                        'create'  => 'timesheets.create',
                        'store'   => 'timesheets.store',
                        'edit'    => 'timesheets.edit',
                        'update'  => 'timesheets.update',
                        'destroy' => 'timesheets.destroy'
                    )
                )
            );
            Route::post('timesheets/running_timer',array('as'=>'timesheets.run_timer','uses'=>'TimeSheetsController@RunningTimer'));
        endif;
    });
    Route::group(array('before' => 'auth','prefix' => @$prefixes['performer']), function(){
        if(isPerformer()):
            Route::resource('timesheets', 'TimeSheetsPerformerController',
                array(
                    'except' => array('show'),
                    'names' => array(
                        'index'   => 'timesheets.index',
                        'create'  => 'timesheets.create',
                        'store'   => 'timesheets.store',
                        'edit'    => 'timesheets.edit',
                        'update'  => 'timesheets.update',
                        'destroy' => 'timesheets.destroy'
                    )
                )
            );
            Route::post('timesheets/running_timer',array('as'=>'timesheets.run_timer','uses'=>'TimeSheetsPerformerController@RunningTimer'));
        else:
            return Redirect::route('home');
        endif;
    });
endif;