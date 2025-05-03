<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\VotingPeriodController;
use App\Http\Controllers\AuthController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/characters', [CharacterController::class, 'index']);
Route::get('/characters/{character}', [CharacterController::class, 'show']);

Route::post('/votes', [VoteController::class, 'store']);
Route::get('/characters/{character}/votes', [VoteController::class, 'getVotesByCharacter']);
Route::get('/votes/results', [VoteController::class, 'getResults']);

Route::get('/voting-period', [VotingPeriodController::class, 'getCurrent']);
Route::get('/voting-periods', [VotingPeriodController::class, 'index']);
Route::get('/voting-periods/{votingPeriod}', [VotingPeriodController::class, 'show']);
Route::get('/voting-periods/{votingPeriod}/results', [VotingPeriodController::class, 'getResults']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/voting-periods', [VotingPeriodController::class, 'store']);
    Route::put('/voting-periods/{votingPeriod}', [VotingPeriodController::class, 'update']);
    Route::delete('/voting-periods/{votingPeriod}', [VotingPeriodController::class, 'destroy']);
});
