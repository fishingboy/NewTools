<?php

//routes here
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use WcStudio\AdminUi\Http\Controllers\Purview\MenuManagementController;
use WcStudio\AdminUi\Http\Controllers\Purview\GroupController;

Route::get('/adminui', 'WcStudio\AdminUi\Http\Controllers\AdminUiIndex@index')->name('adminui');

Route::group(['prefix' => 'adminui'], function () {
    if ($options['login'] ?? true) {
        Route::get('login', 'WcStudio\AdminUi\Http\Controllers\Auth\LoginController@showLoginForm')->name('login');
        Route::post('login', 'WcStudio\AdminUi\Http\Controllers\Auth\LoginController@login');
    }

    if ($options['register'] ?? true) {
        Route::get('register', 'WcStudio\AdminUi\Http\Controllers\Auth\RegisterController@showRegistrationForm')->name('register');
        Route::post('register', 'WcStudio\AdminUi\Http\Controllers\Auth\RegisterController@create');
    }

    if ($options['logout'] ?? true) {
        Route::post('logout', 'WcStudio\AdminUi\Http\Controllers\Auth\LoginController@logout')->name('logout');
    }

});

Route::get('/adminui/menu-management', [MenuManagementController::class, 'index'])->name('adminui.menu.management.index');
Route::get('/adminui/menu-management/get-menu-item/{id}', [MenuManagementController::class, 'getMenu'])->name('adminui.menu.management.get.item');
Route::post('/adminui/menu-management/add-menu-item', [MenuManagementController::class, 'addMenu'])->name('adminui.menu.management.add.item');
Route::post('/adminui/menu-management/update-menu-item/{id}', [MenuManagementController::class, 'updateMenuItem'])->name('adminui.menu.management.update.item');
Route::post('/adminui/menu-management/update-menu-list', [MenuManagementController::class, 'updateMenuList'])->name('adminui.menu.management.update.list');


Route::group(['prefix' => 'adminui/user-group-management'], function () {
    Route::get('', [GroupController::class, 'index'])->name('adminui.group.management.index');
    Route::get('get-group-member', [GroupController::class, 'getGroupUsers'])->name('adminui.group.management.get.member');
    Route::post('remove-group-member', [GroupController::class, 'removeGroupUsers'])->name('adminui.group.management.remove.member');
    Route::post('add-group-member', [GroupController::class, 'addGroupUsers'])->name('adminui.group.management.add.member');
    Route::get('group-purview', [GroupController::class, 'getGroupPurview'])->name('adminui.group.management.purview.index');
    Route::post('edit-group-purview', [GroupController::class, 'updateGroupPurview'])->name('adminui.group.management.purview.edit');
    Route::post('update-group-status', [GroupController::class, 'updateGroupStatus'])->name('adminui.group.management.update.status');
});

Route::group(['prefix' => 'adminui'], function() {

    Route::get('{folder_name}-{controller_name}', function ($folder_name, $controller_name) {
        return app('App\Http\Controllers\\'.$folder_name.'\Admin\\'.$controller_name)->index();
    });

    Route::get('{folder_name}-{controller_name}/create/', function ($folder_name, $controller_name) {
        return app('App\Http\Controllers\\'.$folder_name.'\Admin\\'.$controller_name)->showCreateForm();
    });

    Route::post('{folder_name}-{controller_name}/create/', function ($folder_name, $controller_name) {
        return app('App\Http\Controllers\\'.$folder_name.'\Admin\\'.$controller_name)->create();
    });

    Route::get('{folder_name}-{controller_name}/edit/{id}', function ($folder_name, $controller_name, $id) {
        return app('App\Http\Controllers\\'.$folder_name.'\Admin\\'.$controller_name)->showEditForm($id);
    });

    Route::post('{folder_name}-{controller_name}/edit/{id}', function ($folder_name, $controller_name, $id) {
        return app('App\Http\Controllers\\'.$folder_name.'\Admin\\'.$controller_name)->edit($id);
    });

    Route::post('{folder_name}-{controller_name}/delete/{id}', function ($folder_name, $controller_name, $id) {
        return app('App\Http\Controllers\\'.$folder_name.'\Admin\\'.$controller_name)->delete($id);
    });

    Route::get('{folder_name}', function ($folder_name) {
        return app('App\Http\Controllers\\'.$folder_name.'\Admin\Index')->index();
    });

    Route::get('{folder_name}/create/', function ($folder_name) {
        return app('App\Http\Controllers\\'.$folder_name.'\Admin\Index')->showCreateForm();
    });

    Route::post('{folder_name}/create/', function ($folder_name) {
        return app('App\Http\Controllers\\'.$folder_name.'\Admin\Index')->create();
    });

    Route::get('{folder_name}/edit/{id}', function ($folder_name, $id) {
        return app('App\Http\Controllers\\'.$folder_name.'\Admin\Index')->showEditForm($id);
    });

    Route::post('{folder_name}/edit/{id}', function ($folder_name, $id) {
        return app('App\Http\Controllers\\'.$folder_name.'\Admin\Index')->edit($id);
    });

    Route::post('{folder_name}/delete/{id}', function ($folder_name, $id) {
        return app('App\Http\Controllers\\'.$folder_name.'\Admin\Index')->delete($id);
    });


});
