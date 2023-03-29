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

    private $config;
    private $template;
    private NewsletterSubscription $subscription;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(NewsletterSubscription $subscription, string $config)
    {
        $this->subscription = $subscription;
        $this->config = config('sendgrid-newsletter.' . $config);
        $this->template = $config;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        $dynamicData = $this->config['dynamic_data'];

        if($this->config['default_action_url']) {
          $dynamicData['action_url'] = env('APP_URL') . '/sendgrid-newsletter' . '/' . $this->subscription->token . '/' . $this->template;
        }
        self::sendSendGridEmail(
            $this->subscription->email,
            $this->config['template_id'],
            $this->config['subject'],
            $dynamicData
        );
    }
}