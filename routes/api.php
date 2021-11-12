<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\UserController;



Route::prefix('v1')->group(function (){
    Route::group(['prefix'=>'users'],function(){
        Route::get('/',[UserController::class,'index']);
        Route::post('/',[UserController::class,'store']);
        Route::get('/{user}',[UserController::class,'show']);
        Route::patch('/{user}',[UserController::class,'update']);
        Route::delete('/{user}',[UserController::class,'destroy']);
    });
});
Route::fallback(function (){
    return error_message(__('message.failed'));
});
