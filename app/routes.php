<?php

Route::get('/',array('as'=>'home','uses' => 'GuestController@index'));
Route::any('logout',array('as'=>'logout','uses' => 'GlobalController@logout'));

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

Route::group(array('before' => 'auth.projectAdministrator','prefix' => $prefix), function(){
    Route::resource('projects', 'ProjectsController',
        array(
            'names' => array(
                'index'   => 'project_admin.projects.index',
                'show'    => 'project_admin.projects.show',
                'create'  => 'project_admin.projects.create',
                'store'   => 'project_admin.projects.store',
                'edit'    => 'project_admin.projects.edit',
                'update'  => 'project_admin.projects.update',
                'destroy' => 'project_admin.projects.destroy'
            )
        )
    );
    Route::resource('officers', 'CooperatorsController',
        array(
            'names' => array(
                'index'   => 'project_admin.cooperators.index',
                'show'    => 'project_admin.cooperators.show',
                'create'  => 'project_admin.cooperators.create',
                'store'   => 'project_admin.cooperators.store',
                'edit'    => 'project_admin.cooperators.edit',
                'update'  => 'project_admin.cooperators.update',
                'destroy' => 'project_admin.cooperators.destroy'
            )
        )
    );
    Route::resource('timesheets', 'TimeSheetsController',
        array(
            'except' => array('show'),
            'names' => array(
                'index'   => 'project_admin.timesheets.index',
                'create'  => 'project_admin.timesheets.create',
                'store'   => 'project_admin.timesheets.store',
                'edit'    => 'project_admin.timesheets.edit',
                'update'  => 'project_admin.timesheets.update',
                'destroy' => 'project_admin.timesheets.destroy'
            )
        )
    );
    Route::post('timesheets/running_timer',array('as'=>'project_admin.timesheets.run_timer','uses'=>'TimeSheetsController@RunningTimer'));
});