<?php

namespace Cierra\LaravelSendgridNewsletter\Traits;

trait DefaultOptions
{
    protected static function confirmEmailOptions ()
    {
        return [
            'subject' => config('sendgrid-newsletter.confirmation.subject'),
            'template_id' => config('sendgrid-newsletter.confirmation.template_id'),
            'dynamic_data' => [],
            'default_action_url' => config('sendgrid-newsletter.confirmation.default_action_url'),
            'redirect_url' => config('sendgrid-newsletter.confirmation.redirect'),
        ];
    }

    protected static function subscribedOptions()
    {

        return [
            'subject' => config('sendgrid-newsletter.subscribed.subject'),
            'template_id' => config('sendgrid-newsletter.subscribed.template_id'),
            'dynamic_data' => [],
            'redirect_url' => config('sendgrid-newsletter.subscribed.redirect'),
        ];
    }

    protected static function unsubscribeOptions()
    {

        return [
            'subject' => config('sendgrid-newsletter.unsubscribed.subject'),
            'template_id' => config('sendgrid-newsletter.unsubscribed.template_id'),
            'dynamic_data' => [],
            'default_action_url' => config('sendgrid-newsletter.unsubscribed.default_action_url'),
            'redirect_url' => config('sendgrid-newsletter.unsubscribed.redirect'),
        ];
    }
}