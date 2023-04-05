<?php

use Illuminate\Support\Facades\Route;
use Cierrateam\LaravelSendgridNewsletter\Http\Controller\NewsletterSubscriptionController;

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


Route::get('/sendgrid-newsletter/{token}/confirmation', [NewsletterSubscriptionController::class, 'confirmedSubscription']);
Route::get('/sendgrid-newsletter/{unsubscribe_token}/unsubscribe', [NewsletterSubscriptionController::class, 'unsubscribe']);
Route::get('/sendgrid-newsletter/{token}/resubscribe', [NewsletterSubscriptionController::class, 'confirmedSubscription']);
