<?php

use App\Http\Controllers\Api\V1\StreamEventsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::as('api.v1')
    ->prefix('v1')
    ->middleware(['auth:api'])
    ->group(function () {
        Route::get('event-list', [StreamEventsController::class, 'getList'])
            ->name('.stream-events.getList');

        Route::post('update-event', [StreamEventsController::class, 'updateStatus'])
            ->name('.stream-events.updateStatus');
    });

