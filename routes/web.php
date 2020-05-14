<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/users', 'UserController@index')->name('users.list');
Route::get('/users/create', 'UserController@create')->name('users.create');
Route::post('/users/{id}', 'UserController@update')->name('users.update');
Route::post('/users', 'UserController@store')->name('users.store');
Route::get('/users/{id}', 'UserController@show')->name('users.get');
Route::get('/users/destroy/{id}', 'UserController@destroy')->name('users.destroy');
Route::post('/users/update/password', 'UserController@updatePassword')->name('users.update.password');



Route::get('/tasks', 'TaskController@index')->name('tasks.list');
Route::post('/tasks/{id}', 'TaskController@update')->name('tasks.update');
Route::get('/tasks/create', 'TaskController@create')->name('tasks.create');
Route::post('/tasks', 'TaskController@store')->name('tasks.store'); 
Route::get('/tasks/{id}', 'TaskController@show')->name('tasks.get');
Route::get('/tasks/destroy/{id}', 'TaskController@destroy')->name('tasks.destroy');


Route::get('/recruitments', 'RecruitmentController@index')->name('recruitments.list');
Route::get('/recruitments/{id}', 'RecruitmentController@show')->name('recruitments.get');
Route::get('/recruitments/create/new', 'RecruitmentController@create')->name('recruitments.create');
Route::post('/recruitments', 'RecruitmentController@store')->name('recruitments.store');
Route::post('/recruitments/{id}', 'RecruitmentController@update')->name('recruitments.update');
Route::get('/recruitments/destroy/{id}', 'RecruitmentController@destroy')->name('recruitments.destroy');

Route::get('/user_requests', 'UserRequestController@index')->name('user_requests.list');
Route::get('/user_requests/{id}', 'UserRequestController@show')->name('user_requests.get');
Route::get('/user_requests/create/new', 'UserRequestController@create')->name('user_requests.create');
Route::post('/user_requests', 'UserRequestController@store')->name('user_requests.store');
Route::post('/user_requests/{id}', 'UserRequestController@update')->name('user_requests.update');
Route::post('/user_requests/destroy/{id}', 'UserRequestController@destroy')->name('user_requests.destroy');
Route::get('/user_requests/approve/{id}', 'UserRequestController@approve')->name('user_requests.approve');
Route::get('/user_requests/deny/{id}', 'UserRequestController@deny')->name('user_requests.deny');

Route::get('/payrolls', 'PayrollController@index')->name('payrolls.list');
Route::get('/payrolls/{id}', 'PayrollController@show')->name('payrolls.get');
Route::get('/payrolls/create/new', 'PayrollController@create')->name('payrolls.create');
Route::post('/payrolls', 'PayrollController@store')->name('payrolls.store');
Route::post('/payrolls/{id}', 'PayrollController@update')->name('payrolls.update');
Route::post('/payrolls/destroy/{id}', 'PayrollController@destroy')->name('payrolls.destroy');

Route::get('/departments', 'DepartmentController@index')->name('departments.list');
Route::get('/departments/{id}', 'DepartmentController@show')->name('departments.get');
Route::get('/departments/create/new', 'DepartmentController@create')->name('departments.create');
Route::post('/departments', 'DepartmentController@store')->name('departments.store');
Route::post('/departments/{id}', 'DepartmentController@update')->name('departments.update');
Route::post('/departments/destroy/{id}', 'DepartmentController@destroy')->name('departments.destroy');

Route::get('/report', 'ReportControllet@index')->name('reports.list');
Route::get('/reports/create', 'ReportController@create')->name('reports.create');
Route::get('/reports/{id}', 'ReportController@update')->name('reports.update');
Route::get('/reports/destroy/{id}', 'ReportController@destroy')->name('reports.destroy');

Route::get('/positions', 'PositionController@index')->name('positions.list');
Route::get('/positions/{id}', 'PositionController@show')->name('positions.get');
Route::get('/positions/create/new', 'PositionController@create')->name('positions.create');
Route::post('/positions', 'PositionController@store')->name('positions.store');
Route::post('/positions/{id}', 'PositionController@update')->name('positions.update');
Route::post('/positions/destroy/{id}', 'PositionController@destroy')->name('positions.destroy');


Route::get('/recruitments/send/mail/{email}', 'RecruitmentController@sendMail')->name('recruitments.mail');