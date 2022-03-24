<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\UploadAssetController;

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

Route::post('/Asset',[AssetController::class,'store']);
Route::post('/verify',[AssetController::class,'verifyUser']);

Route::get('/createOtp',[AssetController::class,'generateNumericOTP']);
Route::post('/sendOtp',[AssetController::class,'equneceApiCall']);


Route::post('multiple-image-upload', [UploadAssetController::class, 'store']);
Route::post('downloadAsset', [UploadAssetController::class, 'downloadAsset']);


//Route::post('/test',[AssetController::class,'test']);
//Route::post('/generateNumericOTP',[AssetController::class,'generateNumericOTP']);



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
