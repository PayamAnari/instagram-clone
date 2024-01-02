<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::middleware('auth')->group(function () {

       Route::get('/', [HomeController::class, 'index'])->name('home.index');

       Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
       Route::post('/users', [UserController::class, 'update'])->name('users.update');

       Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
       Route::delete('posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');
   
});

require __DIR__.'/auth.php';
