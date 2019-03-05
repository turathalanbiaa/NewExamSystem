<?php
/**
 * Created by PhpStorm.
 * User: Emad
 * Date: 12/10/2018
 * Time: 1:44 PM
 */

Route::get('control-panel', 'ControlPanel\MainController@index');
Route::get('control-panel/close', 'ControlPanel\MainController@close');
Route::get('control-panel/login', 'ControlPanel\LoginController@login');
Route::post('control-panel/login', 'ControlPanel\LoginController@loginValidate');
Route::get('control-panel/logout', 'ControlPanel\LogoutController@logout');

Route::resource('control-panel/profile', 'ControlPanel\ProfileController');
Route::resource('control-panel/admins', 'ControlPanel\AdminController');
Route::resource('control-panel/lecturers', 'ControlPanel\LecturerController');
Route::resource('control-panel/courses', 'ControlPanel\CourseController');
Route::resource('control-panel/exams', 'ControlPanel\ExamController');
Route::resource('control-panel/questions', 'ControlPanel\QuestionController');
Route::resource('control-panel/branches', 'ControlPanel\BranchController');

Route::get('control-panel/questions-correction/automatically/{id}', 'ControlPanel\QuestionsCorrectionController@QuestionCorrectionAutomatically');
Route::get('control-panel/questions-correction/manually/{id}', 'ControlPanel\QuestionsCorrectionController@ShowQuestionToCorrectionManually');
Route::post('control-panel/questions-correction/manually', 'ControlPanel\QuestionsCorrectionController@QuestionCorrectionManually');
Route::get('control-panel/exams/{exam}/sum', 'ControlPanel\ExamController@sum');

Route::get('control-panel/reports', 'ControlPanel\ReportController@index');
Route::get('control-panel/reports/students', 'ControlPanel\ReportController@showStudents');
Route::get('control-panel/reports/students/{id}', 'ControlPanel\ReportController@showStudent');
Route::get('control-panel/reports/courses', 'ControlPanel\ReportController@showCourses');
Route::get('control-panel/reports/courses/{id}', 'ControlPanel\ReportController@showCourse');
Route::get('control-panel/reports/exams', 'ControlPanel\ReportController@showExams');
Route::get('control-panel/reports/exams/{id}', 'ControlPanel\ReportController@showExam');