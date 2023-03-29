<?php

namespace Cierra\LaravelSendgridNewsletter\Http\Controller;

use Cierra\LaravelSendgridNewsletter\SendgridNewsletter;
use Illuminate\Support\Facades\Response;

class NewsletterSubscriptionController extends Controller
{

    public function confirmSubscription(string $token)
    {
        $response = SendgridNewsletter::subscribe($token);
        return response()->json($response);
    }
    public function unsubscribe(string $token)
    {
        $response = SendgridNewsletter::unsubscribe($token);
        return response()->json($response);
    }
    public function subscribe(string $token)
    {
        $response = SendgridNewsletter::subscribe($token);
        return response()->json($response);
    }
}