<?php

Route::get('/login', function () {
    return redirect(frontend('/login'));
})->name('login');

Route::get('/forgot-password', function () {
    return redirect(frontend('/forgot-password'));
})->name('password.request');

Route::get('/reset-password/{token}', function ($token) {
    return redirect(frontend(sprintf('/reset-password/%s', $token)));
})->name('password.reset');
