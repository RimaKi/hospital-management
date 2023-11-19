<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\API\UserController;
use \App\Http\Controllers\API\PatientController;
use \App\Http\Controllers\API\AdminController;
use \App\Http\Controllers\API\DoctorController;
use \App\Http\Controllers\API\ScheduleController;
use \App\Http\Controllers\API\DiagnosisController;
use \App\Http\Controllers\API\PatientMedicineController;
use \App\Http\Controllers\API\MedicineController;
use \App\Http\Controllers\API\BillController;

Route::post('/add-user', [UserController::class, 'addUser']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [UserController::class, 'logout']);
    Route::post('/edit/{id?}', [UserController::class, 'edit']);
    Route::get('/profile', [UserController::class, 'profile']);
    Route::post('/change-password', [UserController::class, 'change_password']);

    //ADMIN
    Route::prefix('/admin')->group(function () {
        Route::get('/user/view/{id?}', [AdminController::class, 'viewUser']);
        Route::get('/view/statistics', [AdminController::class, 'getStatistics']);
        Route::get('/delete', [AdminController::class, 'deleteUser']);

        Route::prefix('/specializations')->group(function () {
            Route::post('/add', [AdminController::class, 'addSpecialization']);
            Route::get('/view', [AdminController::class, 'viewSpecializations']);
            Route::post('/edit', [AdminController::class, 'editSpecialization']);
            Route::post('/delete', [AdminController::class, 'deleteSpecialization']);
        });
    });

    Route::get('/doctors', [DoctorController::class, 'getDoctors']);
    Route::get('/schedules', [ScheduleController::class, 'getAllSchedules']);
    Route::post('/schedules/cancel', [ScheduleController::class, 'cancel']);
    Route::post('/new-appointment', [ScheduleController::class, 'newAppointment']);
    Route::get('/view/availableVisit', [ScheduleController::class, 'getAvailableVisit']);
    Route::get('/view/availableAppointments', [ScheduleController::class, 'getAvailableAppointments']);


    Route::prefix('/patient')->group(function () {
        Route::post('/add', [PatientController::class, 'add']);
        Route::post('/edit', [PatientController::class, 'edit']);
        Route::post('/delete', [PatientController::class, 'delete']);
        Route::get('/view', [PatientController::class, 'view']);
        Route::post('/schedules', [PatientController::class, 'schedule']);
        Route::post('/{patientId}/diagnosis', [DiagnosisController::class, "addDiagnosis"]);
        Route::post('/{patientId}/medicines', [PatientMedicineController::class, "addMedicines"]);
        Route::get('/{search}/medicines', [BillController::class, "viewBill"]);
        Route::post('/{search}/bill', [BillController::class, "pay"]);
    });


    Route::prefix('/diagnosis')->group(function (){
        Route::post('/add',[DiagnosisController::class,'store']);
        Route::post('/edit',[DiagnosisController::class,'update']);
        Route::post('/delete/{id}',[DiagnosisController::class,'delete']);
        Route::get('/view/{id?}',[DiagnosisController::class,'view']);

    });

    Route::prefix('/patient_medicine')->group(function (){
        Route::post('/add',[PatientMedicineController::class,'store']);
        Route::post('/edit/{id}',[PatientMedicineController::class,'update']);//for doctor
        Route::post('/delete/{id}',[PatientMedicineController::class,'delete']);
        Route::get('/view/',[PatientMedicineController::class,'view']);
    });
   Route::prefix('/medicine')->group(function (){
        Route::post('/add',[MedicineController::class,'store']);
        Route::post('/edit/{id}',[MedicineController::class,'update']);
        Route::post('/delete/{id}',[MedicineController::class,'delete']);
        Route::get('/view/{id?}',[MedicineController::class,'view']);
    });



});

