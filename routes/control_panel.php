<?php
/**
 * Created by PhpStorm.
 * User: Emad
 * Date: 12/10/2018
 * Time: 1:44 PM
 */

Route::get('/control-panel', 'ControlPanel\MainController@index')->middleware("login");
Route::get('/control-panel/login', 'ControlPanel\LoginController@login');
Route::post('/control-panel/login', 'ControlPanel\LoginController@loginValidate');
Route::get('/control-panel/logout', 'ControlPanel\LogoutController@logout');

//Admin
//Route::get('control-panel/admins', 'ControlPanel\AdminController@index')->middleware("login");
//Route::get('control-panel/admins/create', 'ControlPanel\AdminController@create')->middleware("login");
//Route::post('control-panel/admins', 'ControlPanel\AdminController@store');
//Route::get('control-panel/admins/{id}', 'ControlPanel\AdminController@show')->middleware("login");
//Route::get('control-panel/admins/{id}/edit', 'ControlPanel\AdminController@edit')->middleware("login");
//Route::put('control-panel/admins/{id}', 'ControlPanel\AdminController@update');
//Route::delete('control-panel/admins/{id}', 'ControlPanel\AdminController@destroy');



Route::resource('control-panel/admins', 'ControlPanel\AdminController')->middleware("login");

Route::resource('control-panel/courses', 'ControlPanel\CourseController')->middleware("login");
Route::resource('control-panel/lecturers', 'ControlPanel\LecturerController')->middleware("login");
