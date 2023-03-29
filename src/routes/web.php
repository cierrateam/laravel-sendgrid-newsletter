<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Cierra\LaravelSendgridNewsletter\Http\Controller\NewsletterSubscriptionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/sendgrid-newsletter/{token}/confirmation', [NewsletterSubscriptionController::class, 'confirmSubscription']);
Route::get('/sendgrid-newsletter/{token}/unsubscribed', [NewsletterSubscriptionController::class, 'subscribe']);