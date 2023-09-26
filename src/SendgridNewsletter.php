<?php

namespace Cierrateam\LaravelSendgridNewsletter;

use Cierrateam\LaravelSendgridNewsletter\Jobs\SendEmailWithTemplate;
use Cierrateam\LaravelSendgridNewsletter\Models\NewsletterSubscription;
use Cierrateam\LaravelSendgridNewsletter\Traits\NewsletterValidations;
use Cierrateam\LaravelSendgridNewsletter\Traits\SendgridEmail;
use Cierrateam\LaravelSendgridNewsletter\Traits\SendgridMarketing;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Cierrateam\LaravelSendgridNewsletter\Enums\SubscriptionStatus;
use Cierrateam\LaravelSendgridNewsletter\Traits\DefaultOptions;
use Cierrateam\LaravelSendgridNewsletter\Traits\ReturnValues;
use Illuminate\Support\Facades\Log;

class SendgridNewsletter
{
    use NewsletterValidations, SendGridEmail, ReturnValues, DefaultOptions, SendgridMarketing;

    public static function sendSubscriptionLink(string $email, array $emailOptions = null, $user_id = null)
    {
        $validator =  self::validateConfirmEmail($email);
        if($validator->fails()) {
            return self::returnValues(401, 'Confirmation email failes', null, $validator->errors());
        }
        if($validator->passes()) {
            $subscription = NewsletterSubscription::where('email', $email)->first();
            if(!$subscription) {
                $subscription = NewsletterSubscription::create([
                    'email' => $email,
                    'token' => Str::random(60),
                    'unsubscribe_token' => Str::random(60),
                    'status' => SubscriptionStatus::Pending,
                    'user_id' => $user_id,
                    'dynamic_template_data' => $emailOptions['dynamic_data'] ?: null,
                ]);
            }

            $emailOptions = self::confirmEmailOptions($emailOptions);

            if(!empty($subscription->dynamic_template_data)) {
                $emailOptions['dynamic_data'] = array_merge($subscription->dynamic_template_data, $emailOptions['dynamic_data']);
            }

            SendEmailWithTemplate::dispatch($subscription, $emailOptions);

            return self::returnValues(200, 'Confirmation email send.', $subscription, null, $emailOptions['redirect_url']);
        }
    }

    public static function confirmSubscription(string $token, array $emailOptions = null)
    {
        $validator =  self::validateToken($token);
        $subscription = NewsletterSubscription::where('token', $token)->first();
        if($subscription->status == SubscriptionStatus::Subscribed) {
            return self::returnValues(401, 'User already subscribed', $subscription, $validator->errors());
        }
        if($validator->fails()) {
            return self::returnValues(401, 'Subscription failed', $subscription, $validator->errors());
        } else {
            $emailOptions = self::subscribedOptions($emailOptions);
            $subscription->update([
                'status' => SubscriptionStatus::Subscribed,
                'unsubscribed_at' => null,
                'subscribed_at' => Carbon::now()->format('Y-m-d h:m:s'),
            ]);
            if(!empty($subscription->dynamic_template_data)) {
                $emailOptions['dynamic_data'] = array_merge($subscription->dynamic_template_data, $emailOptions['dynamic_data']);
            }
            SendEmailWithTemplate::dispatch($subscription, $emailOptions);
            $contactData = [
                'email' => $subscription->email,
                'unique_name' => $subscription->unsubscribe_token,
            ];

            // merge dynamic_template_data with contactData
            if(!empty($subscription->dynamic_template_data)) {
                $contactData = array_merge($contactData, $subscription->dynamic_template_data);
            }

            if($subscription->user) {
                $contactDataKeyMapping = config('sendgrid-newsletter.sendgrid.contactDataKeyMapping');
                foreach($contactDataKeyMapping as $sendgridKey => $modelKey) {
                    $contactData[$sendgridKey] = $subscription->user->$modelKey;
                }
            }

            self::upsertContact($subscription->email, $contactData);
            return self::returnValues(200, 'Subscription added', $subscription, null, $emailOptions['redirect_url']);
        }
    }

    public static function unsubscribe(string $unsubscribe_token, array $emailOptions = null)
    {
        $subscription = NewsletterSubscription::where('unsubscribe_token', $unsubscribe_token)->first();
        $validator =  self::validateToken($subscription->token);
        if($validator->fails()) {
            return self::returnValues(401, 'Unsubscribing failed', $subscription, $validator->errors());
        } else {
            $emailOptions = self::unsubscribeOptions($emailOptions);
            $subscription->update([
                'status' => SubscriptionStatus::Unsubscribed,
                'unsubscribed_at' => Carbon::now()->format('Y-m-d h:m:s'),
                'subscribed_at' => null,
            ]);
            if(!empty($subscription->dynamic_template_data)) {
                $emailOptions['dynamic_data'] = array_merge($subscription->dynamic_template_data, $emailOptions['dynamic_data']);
            }
            SendEmailWithTemplate::dispatch($subscription, $emailOptions);
            self::moveContactToSupressionGroups($subscription->email);
            return self::returnValues(200, 'Unsubscribed', $subscription, null, $emailOptions['redirect_url']);
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
            return $subscription ? self::returnValues(200, 'Get status', $subscription, null) : self::returnValues(400, SubscriptionStatus::NotSubscribedYet, null, null);
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
            return self::returnValues(401, 'updateSubscription failed.', $subscription, $validator->errors());
        } else {
            $subscription->update($data);
            self::returnValues(200, 'updateSubscription success', $subscription, null);
        }
    }
    /*
    / @params identifier 
    */
    public static function updateSendgridContact(string $token, array $contactData) 
    {
        $validator =  self::validateToken($token);

        $subscription = NewsletterSubscription::where('token', $token)->first();
        if($validator->fails()) {
            return self::returnValues(401, 'updateSubscription failed.', $subscription, $validator->errors());
        } else {
            self::upsertContact($subscription, $contactData);
            self::returnValues(200, 'Update userid', $subscription, null);
        }
    }
}
