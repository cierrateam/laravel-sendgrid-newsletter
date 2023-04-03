<?php

namespace Cierra\LaravelSendgridNewsletter;

use Cierra\LaravelSendgridNewsletter\Jobs\SendEmailWithTemplate;
use Cierra\LaravelSendgridNewsletter\Models\NewsletterSubscription;
use Cierra\LaravelSendgridNewsletter\Traits\NewsletterValidations;
use Cierra\LaravelSendgridNewsletter\Traits\SendgridEmail;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;
use Cierra\LaravelSendgridNewsletter\Enums\SubscriptionStatus;
use Cierra\LaravelSendgridNewsletter\Traits\DefaultOptions;
use Cierra\LaravelSendgridNewsletter\Traits\ReturnValues;

class SendgridNewsletter
{
    use NewsletterValidations, SendGridEmail, ReturnValues, DefaultOptions;

    public static function sendSubscriptionLink($email, $options = null)
    {
        $validator =  self::validateConfirmEmail($email);
        if($validator->fails()) {
            return self::returnValues(401, 'Confirmation email failes', null, $validator->errors());
        }
        if($validator->passes()) {
            $options = empty($options) ? self::confirmEmailOptions() : $options;
            $subscription = NewsletterSubscription::create([
                'email' => $email,
                'token' => Str::random(60),
                'status' => SubscriptionStatus::Pending,
                'redirect_url' => $options['redirect'] ?: '/',
            ]);

            SendEmailWithTemplate::dispatch($subscription, $options);
            return self::returnValues(200, 'Confirmation email send.', $subscription, null);
        }
    }

    public static function subscribe($token, $options = null)
    {
        $validator =  self::validateToken($token);

        $subscription = NewsletterSubscription::where('token', $token)->first();
        if($validator->fails()) {
            return self::returnValues(401, 'Subscription failed', $subscription, $validator->errors());
        } else {
            $subscription->update([
                'status' => SubscriptionStatus::Subscribed,
                'unsubscribed_at' => null,
                'subscribed_at' => Carbon::now()->format('Y-m-d'),
            ]);

            $options = empty($options) ? self::subscribedOptions() : $options;
    
            SendEmailWithTemplate::dispatch($subscription, $options);
            return self::returnValues(200, 'Subscription added', $subscription, null);
        }
    }

    public static function unsubscribe($token, $options = null)
    {
        $validator =  self::validateToken($token);
        $subscription = NewsletterSubscription::where('token', $token)->first();
        if($validator->fails()) {
            return self::returnValues(401, 'Unsubscribing failed', $subscription, $validator->errors());
        } else {
            $subscription->update([
                'status' => SubscriptionStatus::Unsubscribed,
                'unsubscribed_at' => Carbon::now()->format('Y-m-d'),
                'subscribed_at' => null,
                'redirect_url' => $options['redirect'] ?: '/',
            ]);

            $options = empty($options) ? self::unsubscribeOptions() : $options;
    
            SendEmailWithTemplate::dispatch($subscription, $options);
            return self::returnValues(200, 'Unsubscribed', $subscription, null);
        }
    }

    /*
    / @params identifier 
    */
    public static function getSubscriptionStatus($token) 
    {
        $validator =  self::validateToken($token);

        $subscription = NewsletterSubscription::where('token', $token)->first();
        if($validator->fails()) {
            return self::returnValues(401, 'Couldnt get status', $subscription, $validator->errors());
        } else {
            return $subscription ? self::returnValues(200, 'Get status', $subscription, null) : self::returnValues(400, SubscriptionStatus::Not_Subscribed_Yet, null, null);;
        }
    }
}