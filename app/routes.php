<?php
Route::group(array('before' => 'guest'), function(){
    Route::get('/',array('as'=>'home','uses' => 'GuestController@index'));
});
Route::any('logout',array('as'=>'logout','uses' => 'GlobalController@logout'));
Route::get('invite/{token}',array('as'=>'invite','uses' => 'GlobalController@invite'));

Route::group(array('before' => 'guest'), function(){
    Route::post('login',array('as'=>'login','uses' => 'GlobalController@login'));
    Route::get('register',array('as'=>'register','uses' => 'GuestController@register'));
    Route::post('register',array('before' => 'csrf', 'as'=>'register-store','uses' => 'GlobalController@register'));
});
Route::controller('password', 'RemindersController');
if (Auth::check()):
    foreach(Group::all() as $group):
        $prefixes[$group->slug] = $group->dashboard;
    endforeach;
    $prefix = AuthAccount::getGroupStartUrl();
    Route::group(array('before' => 'auth','prefix' => @$prefix), function(){
        Route::get('',array('as'=>'dashboard','uses'=>'AccountController@dashboard'));
        Route::get('settings',array('as'=>'settings','uses'=>'AccountController@settings'));
        Route::get('profile',array('as'=>'profile','uses'=>'AccountController@profile'));
        Route::put('profile',array('before'=>'csrf','as'=>'profile.update','uses'=>'AccountController@profileUpdate'));
        Route::put('profile/requisites',array('before'=>'csrf','as'=>'profile.requisites','uses'=>'AccountController@profileRequisitesUpdate'));

        Route::get('reports',array('as'=>'reports.list','uses'=>'ReportController@index'));
        Route::get('report',array('as'=>'report.create','uses'=>'ReportController@create'));
        Route::put('report/update',array('as'=>'report.update','uses'=>'ReportController@update'));
        Route::get('reports/{report_id}/show',array('as'=>'report.show','uses'=>'ReportController@show'));
        Route::post('report/{type}/save/{format}/{action}',array('as'=>'report.save','uses'=>'ReportController@save'));
        Route::delete('report/delete',array('as'=>'report.delete','uses'=>'ReportController@delete'));
        Route::post('report/download',array('before'=>'csrf', 'as'=>'report.download','uses'=>'ReportController@download'));
    });
    Route::group(array('before' => 'auth','prefix' => @$prefixes['admin-projects']), function(){
        if(isProjectAdministrator()):
            Route::resource('clients', 'ClientsController',
                array(
                    'names' => array(
                        'index'   => 'clients.index',
                        'create'  => 'clients.create',
                        'store'   => 'clients.store',
                        'show'    => 'clients.show',
                        'edit'    => 'clients.edit',
                        'update'  => 'clients.update',
                        'destroy' => 'clients.destroy'
                    )
                )
            );
            Route::get('projects/archive',array('as'=>'projects.archive','uses'=>'ProjectsController@archive'));
            Route::put('projects/{project_id}/favorite',array('as'=>'projects.favorite','uses'=>'ProjectsController@favorite'));
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
            Route::resource('officers.access', 'CooperatorsAccessController',
                array(
                    'except' => array('show','update','create','edit','destroy'),
                    'names' => array(
                        'index'    => 'cooperators.access.index',
                        'store'  => 'cooperators.access.store',
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
            Route::delete('invite/reject/{invite_id}',array('as'=>'cooperators.invite_reject','uses'=>'CooperatorsController@inviteReject'));
        endif;
    });
    Route::group(array('before' => 'auth'), function(){
        Route::post('oauth/register', array('before'=>'csrf','as'=>'oauth.register','uses' => 'BasecampController@oauth_url'));
        Route::get('oauth/callback', array('as'=>'oauth.callback','uses' => 'BasecampController@oauth_callback'));
    });
    Route::group(array('before' => 'auth.basecamp','prefix' => @$prefix), function(){
        Route::get('basecamp/accounts', array('as'=>'basecamp.accounts','uses' => 'BasecampController@userAccountsList'));
        Route::post('basecamp/projects', array('before'=>'csrf','as'=>'basecamp.projects','uses' => 'BasecampController@getUserAccountProjects'));
        Route::post('basecamp/project/import', array('before'=>'csrf','as'=>'basecamp.project.import','uses' => 'BasecampController@userAccountProjectImport'));
    });
endif;