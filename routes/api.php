<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\{
    AuthController,
    CompanyController,
    ClientController,
    DashboardController,
};

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix'=>'admin','as'=>'admin.'], function(){

    Route::post('/login',  [AuthController::class, 'login'])->name('login');

    Route::group(['middleware' => 'auth:sanctum'], function() {
        //Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index']);

        //Company
        Route::get('/companies',            [CompanyController::class,'index']);
        Route::get('/company-clients/{id}', [CompanyController::class,'clients']);
        Route::get('/companies/{company}',  [CompanyController::class,'show'])->missing(function (){
            return response([
                    "message"=>'Not found' , 'data' => null]
                ,404);
        });
        Route::post('/company',             [CompanyController::class,'store']);
        Route::post('/company/{id}/update', [CompanyController::class,'update']);
        Route::delete('/company/{id}',      [CompanyController::class,'destroy']);

        //Client
        Route::get('/clients',               [ClientController::class,'index']);
        Route::get('/client-companies/{id}', [ClientController::class,'companies']);
        Route::get('/clients/{client}',      [ClientController::class,'show'])->missing(function (){
            return response([
                    "message"=>'Not found' , 'data' => null]
                ,404);
        });
        Route::post('/client',               [ClientController::class,'store']);
        Route::post('/client/{id}/update',   [ClientController::class,'update']);
        Route::delete('/client/{id}',        [ClientController::class,'destroy']);

        //User
        Route::get('/authUser', [AuthController::class, 'authUser'])->name('auth-user');
        Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');
    });

});
