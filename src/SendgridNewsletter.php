<?php

namespace Cierra\LaravelSendgridNewsletter;

use Cierra\LaravelSendgridNewsletter\Jobs\SendEmailWithTemplate;
use Cierra\LaravelSendgridNewsletter\Models\NewsletterSubscription;
use Cierra\LaravelSendgridNewsletter\Traits\IdentifierValidationRules;
use Cierra\LaravelSendgridNewsletter\Traits\NewsletterValidations;
use Cierra\LaravelSendgridNewsletter\Traits\SendgridEmail;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;
use Cierra\LaravelSendgridNewsletter\Enums\SubscriptionStatus;
use Cierra\LaravelSendgridNewsletter\Traits\ReturnValues;

class SendgridNewsletter
{
    use NewsletterValidations, SendGridEmail, ReturnValues;

    public static function sendSubscriptionLink($email)
    {
        $validator =  self::validateConfirmEmail($email);
        if($validator->fails()) {
            return self::returnValues(401, 'Confirmation email failes', null, $validator->errors());
        }
        if($validator->passes()) {
            $subscription = NewsletterSubscription::create([
                'email' => $email,
                'token' => Str::random(60),
                'status' => SubscriptionStatus::Pending
            ]);

            SendEmailWithTemplate::dispatch($subscription, 'confirmation');
            return self::returnValues(200, 'Confirmation email send.', $subscription, null);
        }
    }

    public static function subscribe($token)
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
    
            SendEmailWithTemplate::dispatch($subscription, 'subscribed');
            return self::returnValues(200, 'Subscription added', $subscription, null);
        }
    }

    public static function unsubscribe($token)
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
            ]);
    
            SendEmailWithTemplate::dispatch($subscription, 'unsubscribed');
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