<?php

use Illuminate\Support\Facades\Route;
use Xibeicity\Message\Controllers\MessageController;

Route::prefix('message')->group(function () {
    // 消息列表
    Route::get('list', [MessageController::class, 'index'])->name('message.list');
});