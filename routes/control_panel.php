<?php
/**
 * Created by PhpStorm.
 * User: Emad
 * Date: 12/10/2018
 * Time: 1:44 PM
 */

Route::get('/control-panel', function (){return redirect("control-panel/profile");});
Route::get('/control-panel/login', 'ControlPanel\LoginController@login');
Route::post('/control-panel/login', 'ControlPanel\LoginController@loginValidate');
Route::get('/control-panel/logout', 'ControlPanel\LogoutController@logout');


Route::resource('control-panel/profile', 'ControlPanel\ProfileController')->middleware("Login");
Route::resource('control-panel/admins', 'ControlPanel\AdminController')->middleware("Login");
Route::resource('control-panel/lecturers', 'ControlPanel\LecturerController')->middleware("Login");
Route::resource('control-panel/courses', 'ControlPanel\CourseController')->middleware("Login");