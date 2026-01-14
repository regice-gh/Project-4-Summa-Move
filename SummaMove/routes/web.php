<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\AdminDashboard;
use App\Livewire\StudentDashboard;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\IsAdmin;


Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth'])->group(function () {
    
    Route::get('/student', StudentDashboard::class)->name('student.dashboard');

    Route::middleware([IsAdmin::class])->group(function () {
        Route::get('/admin', AdminDashboard::class)->name('admin.dashboard');
    });

});

Route::get('/dashboard', function () {

    $user = Auth::user();

    
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } 
    
    if ($user->role === 'student') {
        return redirect()->route('student.dashboard');
    }

    
    return abort(403, 'No role assigned');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/settings.php';
