<?php

namespace Cierrateam\LaravelSendgridNewsletter\Traits;

trait DefaultOptions
{
    protected static function confirmEmailOptions ($options = null)
    {
        $options = empty($options) ? [] : $options;
        $confirmation_config = config('sendgrid-newsletter.confirmation');
        $dynamicData = [
            'dynamic_data' => [
                'default_action_url' => url('/sendgrid-newsletter/{token}/confirmation'),
                'default_unsubscribe_action_url' => url('/sendgrid-newsletter/{token}/unsubscribe/'),
            ]
        ];
        return array_merge($confirmation_config, $options, $dynamicData);
    }

    protected static function subscribedOptions($options = null)
    {
        $options = empty($options) ? [] : $options;
        $subscribed_config = config('sendgrid-newsletter.subscribed');
        $dynamicData = [
            'dynamic_data' => [
                'default_action_url' => url('/sendgrid-newsletter/{token}/unsubscribe/'),
                'default_unsubscribe_action_url' => url('/sendgrid-newsletter/{token}/unsubscribe/'),
            ]
        ];
        return array_merge($subscribed_config, $dynamicData, $options);
    }

    protected static function unsubscribeOptions($options = null)
    {
        $options = empty($options) ? [] : $options;
        $unsubscribed_config = config('sendgrid-newsletter.unsubscribed');
        $dynamicData = [
            'dynamic_data' => [
                'default_action_url' => url('/sendgrid-newsletter/{token}/resubscribe'),
                'default_unsubscribe_action_url' => url('/sendgrid-newsletter/{token}/unsubscribe/'),
            ]
        ];
        return array_merge($unsubscribed_config, $dynamicData, $options);
    }
}
