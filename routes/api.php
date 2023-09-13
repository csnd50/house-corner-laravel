<?php

use App\Http\Controllers\HouseController;
use App\Http\Controllers\userController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/images/{filename}', function ($filename) {
    $path = storage_path('app/public/images/' . $filename);

    if (file_exists($path)) {
        return response()->file($path);
    }

    abort(404);
})->name('api.images.show');

Route::post('/register', [userController::class, 'register']);
Route::post('/login', [userController::class, 'login']);
Route::get('/home', [HouseController::class, 'index']);
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [userController::class, 'logout']);
    Route::post('/home/add', [HouseController::class, 'store']);
    Route::get('profile', [userController::class, 'userInfo']);
    Route::post('/update', [userController::class, 'update']);
    Route::get('/home/search/{name}', [HouseController::class, 'search']);
    Route::post('/home/filter', [HouseController::class, 'filter']);
    Route::delete('/home/delete/{id}', [HouseController::class, 'destroy']);
    Route::get('home/{id}', [HouseController::class, 'show']);
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
