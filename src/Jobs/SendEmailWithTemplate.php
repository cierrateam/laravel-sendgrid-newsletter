<?php

namespace Cierrateam\LaravelSendgridNewsletter\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Cierrateam\LaravelSendgridNewsletter\Models\NewsletterSubscription;
use Cierrateam\LaravelSendgridNewsletter\Traits\SendGridEmail;

class SendEmailWithTemplate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, SendGridEmail;

    private $options;
    private $subscription;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($subscription, array $options)
    {
        $this->subscription = $subscription;
        $this->options = $options;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $dynamicData = $this->options['dynamic_data'];
        if(array_key_exists('default_action_url', $dynamicData) || $dynamicData['default_action_url']) {
            $dynamicData['action_url'] = str_replace('{token}', $this->subscription->token, $dynamicData['default_action_url']);
        }
        if(array_key_exists('default_unsubscribe_action_url', $dynamicData) || $dynamicData['default_unsubscribe_action_url']) {
            $dynamicData['unsubscribe_action_url'] = str_replace('{token}', $this->subscription->unsubscribe_token, $dynamicData['default_action_url']);
        }

        \Log::info(print_r($this->options, true));

        self::sendSendGridEmail(
            $this->subscription->email,
            $this->options['template_id'],
            $this->options['subject'],
            $dynamicData
        );
    }
}
