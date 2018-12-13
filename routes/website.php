<?php
/**
 * Created by PhpStorm.
 * User: Emad
 * Date: 12/10/2018
 * Time: 1:44 PM
 */


Route::get('/', function () {
    return view("Website/index");
})->middleware('StudentAuth');

Route::get('/info', function () {
        return view("Website/info");
});