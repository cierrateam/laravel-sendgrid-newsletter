<?php

namespace Cierrateam\LaravelSendgridNewsletter;

use Cierrateam\LaravelSendgridNewsletter\Jobs\SendEmailWithTemplate;
use Cierrateam\LaravelSendgridNewsletter\Models\NewsletterSubscription;
use Cierrateam\LaravelSendgridNewsletter\Traits\NewsletterValidations;
use Cierrateam\LaravelSendgridNewsletter\Traits\SendgridEmail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Cierrateam\LaravelSendgridNewsletter\Enums\SubscriptionStatus;
use Cierrateam\LaravelSendgridNewsletter\Traits\DefaultOptions;
use Cierrateam\LaravelSendgridNewsletter\Traits\ReturnValues;

class SendgridNewsletter
{
    use NewsletterValidations, SendGridEmail, ReturnValues, DefaultOptions;

    public static function sendSubscriptionLink(string $email, $user_id = null, array $options = null)
    {
        $validator =  self::validateConfirmEmail($email);
        if($validator->fails()) {
            return self::returnValues(401, 'Confirmation email failes', null, $validator->errors());
        }
        if($validator->passes()) {
            $options = self::confirmEmailOptions($options);
            $subscription = NewsletterSubscription::create([
                'email' => $email,
                'token' => Str::random(60),
                'status' => SubscriptionStatus::Pending,
                'user_id' => $user_id
            ]);

            SendEmailWithTemplate::dispatch($subscription, $options);
            return self::returnValues(200, 'Confirmation email send.', $subscription, null, $options['redirect_url']);
        }
    }

    public static function subscribe(string $token, array $options = null)
    {
        $validator =  self::validateToken($token);

        $subscription = NewsletterSubscription::where('token', $token)->first();
        if($validator->fails()) {
            return self::returnValues(401, 'Subscription failed', $subscription, $validator->errors());
        } else {
            $options = self::subscribedOptions($options);
            $subscription->update([
                'status' => SubscriptionStatus::Subscribed,
                'unsubscribed_at' => null,
                'subscribed_at' => Carbon::now()->format('Y-m-d'),
            ]);
    
            SendEmailWithTemplate::dispatch($subscription, $options);
            return self::returnValues(200, 'Subscription added', $subscription, null);
        }
    }

    public static function unsubscribe(string $token, array $options = null)
    {
        $validator =  self::validateToken($token);
        $subscription = NewsletterSubscription::where('token', $token)->first();
        if($validator->fails()) {
            return self::returnValues(401, 'Unsubscribing failed', $subscription, $validator->errors());
        } else {
            $options = self::unsubscribeOptions($options);
            $subscription->update([
                'status' => SubscriptionStatus::Unsubscribed,
                'unsubscribed_at' => Carbon::now()->format('Y-m-d'),
                'subscribed_at' => null,
            ]);
    
            SendEmailWithTemplate::dispatch($subscription, $options);
            return self::returnValues(200, 'Unsubscribed', $subscription, null, $options['redirect_url']);
        }
    }

    /*
    / @params identifier 
    */
    public static function getSubscriptionStatus(string $token) 
    {
        $validator =  self::validateToken($token);

        $subscription = NewsletterSubscription::where('token', $token)->first();
        if($validator->fails()) {
            return self::returnValues(401, 'Couldnt get status', $subscription, $validator->errors());
        } else {
            return $subscription ? self::returnValues(200, 'Get status', $subscription, null) : self::returnValues(400, SubscriptionStatus::Not_Subscribed_Yet, null, null);
        }
    }

    /*
    / @params identifier 
    */
    public static function updateSubscription(string $token, array $data) 
    {
        $validator =  self::validateToken($token);

        $subscription = NewsletterSubscription::where('token', $token)->first();
        if($validator->fails()) {
            return self::returnValues(401, 'Update user_id failed.', $subscription, $validator->errors());
        } else {
            $subscription->update($data);
            self::returnValues(200, 'Update userid', $subscription, null);
        }
    }
}