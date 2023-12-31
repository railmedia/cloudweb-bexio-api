<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', 'App\Http\Controllers\DashboardController@dashboard')->name('dashboard');
    Route::get('/dashboard/bexio-auth', 'App\Http\Controllers\DashboardController@bexioAuth')->name('bexio.auth');
    Route::get('/dashboard/bexio-refresh-auth', 'App\Http\Controllers\DashboardController@bexioAuthRefreshToken')->name('bexio.auth.refresh');
    // Route::get('/dashboard/bexio-auth', 'App\Http\Controllers\DashboardController@bexioAuthResponse')->name('bexio-auth-response');
    Route::get('/dashboard/bexio-main', 'App\Http\Controllers\DashboardController@bexioMain')->name('bexio.main');
    Route::get('/dashboard/bexio-contacts', 'App\Http\Controllers\DashboardController@bexioContacts')->name('bexio.contacts');
    Route::get('/dashboard/bexio-contacts-fetch', 'App\Http\Controllers\DashboardController@bexioContactsFetch')->name('bexio.contacts.fetch');
    Route::get('/dashboard/bexio-contacts-relations-fetch', 'App\Http\Controllers\DashboardController@bexioContactsRelationsFetch')->name('bexio.contacts.relations.fetch');

    Route::get('/dashboard/bexio-projects', 'App\Http\Controllers\DashboardController@bexioProjects')->name('bexio.projects');
    Route::get('/dashboard/bexio-projects-fetch', 'App\Http\Controllers\DashboardController@bexioProjectsFetch')->name('bexio.projects.fetch');
    Route::post('/dashboard/bexio-projects-search', 'App\Http\Controllers\DashboardController@bexioProjectsSearch')->name('bexio.projects.search');
    Route::post('/dashboard/project-is-synchronized', 'App\Http\Controllers\DashboardController@bexioProjectsSyncCheck')->name('bexio.projects.sync.check');
    Route::post('/dashboard/synchronize-project', 'App\Http\Controllers\ProjectsController@syncProject')->name('sync.project');
    Route::post('/dashboard/bexio-project-fetch-timesheets', 'App\Http\Controllers\DashboardController@bexioProjectFetchTimesheets')->name('bexio.project.fetch.timesheets');
    Route::post('/dashboard/bexio-project-fetch-contacts', 'App\Http\Controllers\DashboardController@bexioProjectFetchContacts')->name('bexio.project.fetch.contacts');

    Route::get('/dashboard/bexio-timesheets', 'App\Http\Controllers\DashboardController@bexioTimesheets')->name('bexio.timesheets');
    Route::get('/dashboard/bexio-timesheets-fetch', 'App\Http\Controllers\DashboardController@bexioTimesheetsFetch')->name('bexio.timesheets.fetch');

    Route::get('/dashboard/downloads-basket', 'App\Http\Controllers\DashboardController@downloadsBasket')->name('download.basket');
    Route::post('/dashboard/downloads-basket-add', 'App\Http\Controllers\DashboardController@downloadsBasketAdd')->name('download.basket.add');
    Route::get('/dashboard/downloads-basket-export-to-csv', 'App\Http\Controllers\DashboardController@exportDownloadsBasketToCSV')->name('download.basket.export.to.csv');
    Route::get('/dashboard/downloads-basket-detect-items-number', 'App\Http\Controllers\DashboardController@downloadsDetectNumber')->name('download.basket.detect.item.number');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::resource('/users', 'App\Http\Controllers\UsersController')->names([
        'index'  => 'users.list',
        'show'   => 'users.show',
        'create' => 'users.create',
        'store'  => 'users.store',
        'edit'   => 'users.edit',
        'update' => 'users.update',
        'destroy'=> 'users.delete'
    ]);

    Route::resource('/projects', 'App\Http\Controllers\ProjectsController')->names([
        'index'  => 'projects.list',
        'show'   => 'projects.show',
        'create' => 'projects.create',
        'store'  => 'projects.store',
        'edit'   => 'projects.edit',
        'update' => 'projects.update',
        'destroy'=> 'projects.delete'
    ]);

    Route::get('/projects/all/fetch', 'App\Http\Controllers\ProjectsController@getAllProjects')->name('projects.get.all');
    Route::get('/projects/{project_id}/tasks', 'App\Http\Controllers\ProjectsController@getProjectTasks')->name('project.tasks');
    Route::get('/projects/{project_id}/tasks/raw', 'App\Http\Controllers\ProjectsController@getProjectTasksRaw')->name('project.tasks.raw');
    Route::get('/projects/{project_id}/contacts/raw', 'App\Http\Controllers\ProjectsController@getProjectContactsRaw')->name('project.contacts.raw');
    Route::get('/projects/{project_id}/contacts', 'App\Http\Controllers\ProjectsController@getProjectContacts')->name('project.contacts');

    Route::resource('/tasks', 'App\Http\Controllers\TasksController')->names([
        'index'  => 'tasks.list',
        'show'   => 'tasks.show',
        'create' => 'tasks.create',
        'store'  => 'tasks.store',
        'edit'   => 'tasks.edit',
        'update' => 'tasks.update',
        'destroy'=> 'tasks.delete'
    ]);

    Route::get('/tasks/single/{task_id}', 'App\Http\Controllers\TasksController@getSingleTask')->name('tasks.single');

    Route::resource('/contacts', 'App\Http\Controllers\ContactsController')->names([
        'index'  => 'contacts.list',
        'show'   => 'contacts.show',
        'create' => 'contacts.create',
        'store'  => 'contacts.store',
        'edit'   => 'contacts.edit',
        'update' => 'contacts.update',
        'destroy'=> 'contacts.delete'
    ]);

    Route::get('/contacts/single/{contact_id}', 'App\Http\Controllers\ContactsController@getSingleContact')->name('contacts.single');

});

require __DIR__.'/auth.php';
