<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/patients/{name}', 'App\Http\Controllers\PatientController@getPatient');
Route::post('/patients/appointment', 'PatientController@store');

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::post('signup', 'App\Http\Controllers\AuthController@signup');

    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        Route::get('logout', 'App\Http\Controllers\AuthController@logout');
        Route::get('user', 'App\Http\Controllers\AuthController@user');
        Route::post('update', 'App\Http\Controllers\AuthController@update');
        Route::post('appointment', 'App\Http\Controllers\AppointmentController@saveAppointment');
        Route::post('doctorAppointments', 'App\Http\Controllers\DoctorController@getDoctorAppointments');
        Route::post('appointmentsPeriod', 'App\Http\Controllers\DoctorController@getAllAppointments');
    });
});
