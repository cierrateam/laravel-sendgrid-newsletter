<?php

namespace Cierrateam\LaravelSendgridNewsletter\Http\Controller;

use Cierrateam\LaravelSendgridNewsletter\SendgridNewsletter;
class NewsletterSubscriptionController extends Controller
{

    public function confirmedSubscription(string $token)
    {
        $response = SendgridNewsletter::confirmSubscription($token);
        // if response status is 200, redirect to redirect_url else redirect to redirect_url with flash message and errors
        if($response['status'] == 200) {
            return redirect($response['redirect_url']);
        } else {
            return redirect($response['redirect_url']);
        }
    }
    public function unsubscribe(string $unsubscribe_token)
    {
        $response = SendgridNewsletter::unsubscribe($unsubscribe_token);
        if($response['status'] == 200) {
            return redirect($response['redirect_url']);
        } else {
            return redirect($response['redirect_url']);
        }
    }
}
