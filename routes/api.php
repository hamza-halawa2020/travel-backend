<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\ContentController;

Route::get('/settings', [ContentController::class, 'siteSettings']);
Route::get('/home-content', [ContentController::class, 'home']);
Route::get('/journal-content', [ContentController::class, 'journal']);
Route::get('/journal-articles', [ContentController::class, 'articles']);
Route::get('/journal-articles/{slug}', [ContentController::class, 'article']);

Route::post('/contacts', ContactController::class);
