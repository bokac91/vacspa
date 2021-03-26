<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', 'AuthController@register');
Route::post('/login', 'AuthController@login');
Route::post('/logout', 'AuthController@logout');

Route::middleware('auth:sanctum')->get('/authenticated', function () {
    return true;
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return [
        'user' => $request->user(),
        'vacationDays' => $request->user()->getVacationDays(),
        'vacations' => $request->user()->vacations,
    ];
});

Route::middleware('auth:sanctum')->post(
    '/vacation/create', 'VacationController@create'
);

Route::middleware('auth:sanctum')->delete(
    '/vacation/delete/{id}', 'VacationController@delete'
);