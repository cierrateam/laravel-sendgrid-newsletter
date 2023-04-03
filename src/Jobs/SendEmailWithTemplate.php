<?php

namespace Cierra\LaravelSendgridNewsletter\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use Cierra\LaravelSendgridNewsletter\Models\NewsletterSubscription;
use Illuminate\Support\Facades\Config;
use Cierra\LaravelSendgridNewsletter\Traits\SendGridEmail;

class SendEmailWithTemplate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, SendGridEmail;

    private $options;
    private NewsletterSubscription $subscription;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(NewsletterSubscription $subscription, array $options)
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
        $dynamicData['action_url'] = '/';
        if(array_key_exists('default_action_url', $this->options)) {
            $dynamicData['action_url'] = str_replace('{token}', $this->subscription->token, $this->options['default_action_url']);
        }

        self::sendSendGridEmail(
            $this->subscription->email,
            $this->options['template_id'],
            $this->options['subject'],
            $dynamicData
        );
    }
}