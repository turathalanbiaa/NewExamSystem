<?php
/**
 * Created by PhpStorm.
 * User: Emad
 * Date: 12/10/2018
 * Time: 1:44 PM
 */

Route::get('/control-panel', 'ControlPanel\MainController@index');
Route::get('/control-panel/login', 'ControlPanel\MainController@login');
Route::post('/control-panel/login', 'ControlPanel\MainController@loginValidate');
Route::get('/control-panel/logout', 'ControlPanel\MainController@logout');

Route::resource('control-panel/admins', 'ControlPanel\AdminController');
Route::resource('control-panel/courses', 'ControlPanel\CourseController');
Route::resource('control-panel/lecturers', 'ControlPanel\LecturerController');
