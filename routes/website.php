<?php
/**
 * Created by PhpStorm.
 * User: Emad
 * Date: 12/10/2018
 * Time: 1:44 PM
 */

Route::get('/', 'Website\HomeController@index')->middleware('StudentAuth');
Route::get('/student-auth', 'Website\StudentAuthController@studentAuth');
Route::get('/info', 'Website\HomeController@info');
Route::get('/student/get-student-exam', 'Website\StudentExamController@index');


