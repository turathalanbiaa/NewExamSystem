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

Route::resource('control-panel/admins', 'ControlPanel\AdminController')->middleware("login");
Route::resource('control-panel/courses', 'ControlPanel\CourseController')->middleware("login");
Route::resource('control-panel/lecturers', 'ControlPanel\LecturerController')->middleware("login");
