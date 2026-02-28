<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;

Route::get('/', [ChatController::class, 'landing'])->name('landing');

Route::get('/chat/{session_id?}', [ChatController::class, 'chat'])->name('chat.page');
Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
Route::delete('/chat/{session_id}', [ChatController::class, 'delete'])->name('chat.delete');
Route::post('/chat/rename/{session_id}', [ChatController::class, 'rename'])
    ->name('chat.rename');