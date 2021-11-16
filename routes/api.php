<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\BooksController;


Route::post('v1/auth/login',[AuthController::class,'login']);
Route::prefix('v1')->group(function (){
    Route::group(['prefix'=>'users'],function(){
        Route::get('/',[AuthController::class,'index']);
        Route::post('/',[AuthController::class,'store']);
        Route::get('/{user}',[AuthController::class,'show']);
        Route::patch('/{user}',[AuthController::class,'update']);
        Route::delete('/{user}',[AuthController::class,'destroy']);
    });

    Route::apiResources([
      'books'=> BooksController::class,
    ]);
    Route::post('auth/logout',[AuthController::class,'logout']);
});
Route::fallback(function (){
    return error_message(__('message.failed'));
});
//->middleware('auth:api')
