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

    private $email;
    private $token;
    private $options;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $token, array $options)
    {
        $this->email = $email;
        $this->options = $options;
        $this->token = $token;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $dynamicData = $this->options['dynamic_data'];
        $dynamicData['action_url'] = '/';
        if(array_key_exists('default_action_url', $this->options) && $this->options['default_action_url']) {
            $dynamicData['action_url'] = str_replace('{token}', $this->token, $this->options['default_action_url']);
        }

        self::sendSendGridEmail(
            $this->email,
            $this->options['template_id'],
            $this->options['subject'],
            $dynamicData
        );
    }
}
