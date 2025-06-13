<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberController;

Route::get('/', function () {
    return redirect()->route('members.index');
});

Route::get('/members/report', [MemberController::class, 'report'])->name('members.report');

Route::resource('members', MemberController::class);



