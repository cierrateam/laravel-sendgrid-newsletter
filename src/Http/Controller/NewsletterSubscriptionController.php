<?php

namespace Cierrateam\LaravelSendgridNewsletter\Http\Controller;

use Cierrateam\LaravelSendgridNewsletter\SendgridNewsletter;
class NewsletterSubscriptionController extends Controller
{

    public function confirmedSubscription(string $token)
    {
        $response = SendgridNewsletter::confirmSubscription($token);
        return response()->json($response);
    }
    public function unsubscribe(string $unsubscribe_token)
    {
        $response = SendgridNewsletter::unsubscribe($unsubscribe_token);
        return response()->json($response);
    }
}
