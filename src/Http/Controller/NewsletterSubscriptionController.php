<?php

namespace Cierrateam\LaravelSendgridNewsletter\Http\Controller;

use Cierrateam\LaravelSendgridNewsletter\SendgridNewsletter;
class NewsletterSubscriptionController extends Controller
{

    public function confirmedSubscription(string $token)
    {
        $response = SendgridNewsletter::subscribe($token);
        return response()->json($response);
    }
    public function unsubscribe(string $token)
    {
        $response = SendgridNewsletter::unsubscribe($token);
        return response()->json($response);
    }
}