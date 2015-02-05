<?php

Route::get('/',array('as'=>'home','uses' => 'GuestController@index'));
Route::any('logout',array('as'=>'logout','uses' => 'GlobalController@logout'));

foreach(Group::all() as $group):
    $prefixes[$group->slug] = $group->dashboard;
endforeach;
$prefix = AuthAccount::getGroupStartUrl();

Route::group(array('before' => 'guest'), function(){
    Route::post('login',array('as'=>'login','uses' => 'GlobalController@login'));
    Route::get('register',array('as'=>'register','uses' => 'GuestController@register'));
    Route::post('register',array('before' => 'csrf', 'as'=>'register-store','uses' => 'GlobalController@register'));
});
Route::group(array('before' => 'auth','prefix' => $prefix), function(){
    Route::get('',array('as'=>'dashboard','uses'=>'AccountController@dashboard'));
    Route::get('settings',array('as'=>'settings','uses'=>'AccountController@settings'));
    Route::get('profile',array('as'=>'profile','uses'=>'AccountController@profile'));
});

Route::group(array('before' => 'auth.projectAdministrator','prefix' => $prefixes['admin-projects']), function(){
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
});

Route::group(array('before' => 'auth.performer','prefix' => $prefixes['performer']), function(){
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
    Route::post('timesheets/running_timer',array('as'=>'timesheets.run_timer','uses'=>'TimeSheetsPerformerController@RunningTimer'));
});