<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home/Landing page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Marketing/Landing pages
Route::get('/features', function () {
    return view('features');
})->name('features');

Route::get('/pricing', function () {
    return view('pricing');
})->name('pricing');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// Additional landing pages (if you had them)
Route::get('/solutions', function () {
    return view('solutions');
})->name('solutions');

Route::get('/industries', function () {
    return view('industries');
})->name('industries');

Route::get('/resources', function () {
    return view('resources');
})->name('resources');

Route::get('/blog', function () {
    return view('blog');
})->name('blog');

Route::get('/help', function () {
    return view('help');
})->name('help');

Route::get('/demo', function () {
    return view('demo');
})->name('demo');

Route::get('/testimonials', function () {
    return view('testimonials');
})->name('testimonials');

Route::get('/case-studies', function () {
    return view('case-studies');
})->name('case-studies');

Route::get('/integrations', function () {
    return view('integrations');
})->name('integrations');

Route::get('/api', function () {
    return view('api');
})->name('api');

Route::get('/security', function () {
    return view('security');
})->name('security');

Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

Route::get('/terms', function () {
    return view('terms');
})->name('terms');

Route::get('/cookies', function () {
    return view('cookies');
})->name('cookies');

// Authentication required routes
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Include Breeze authentication routes
require __DIR__.'/auth.php';
