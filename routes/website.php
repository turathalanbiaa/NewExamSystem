<?php
/**
 * Created by PhpStorm.
 * User: Emad
 * Date: 12/10/2018
 * Time: 1:44 PM
 */
Route::get('/student-auth', 'Website\StudentAuthController@studentAuth');
Route::get('/info', 'Website\StudentAuthController@info');
Route::get('/student-logout', 'Website\StudentAuthController@logout');
Route::get('/', 'Website\StudentExamController@exams')->middleware('StudentAuth');
Route::get('/finished-exams', 'Website\StudentExamController@finishedExams')->middleware('StudentAuth');
Route::get('/exam/{id}', 'Website\StudentExamController@exam');
Route::post('/store', 'Website\StudentExamController@store');
Route::post('/finish', 'Website\StudentExamController@finish');


