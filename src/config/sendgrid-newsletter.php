<?php

return [
    'sendgrid' => [
        'api-key' => env('SENDGRID_API_KEY'),
        'newsletterListIds' => [],
        'supressionGroupIds' => [],
    ],
    'confirmation' => [
        'subject' => 'Email confirmation',
        'template_id' => env('SENDGRID_TEMPLATE_EMAIL_CONFIRM'),
        'default_action_url' => env('APP_URL') . '/sendgrid-newsletter/{token}/confirmation',
        'redirect_url' => '/',
    ],
    'subscribed' => [
        'subject' => 'Subscribed',
        'template_id' => env('SENDGRID_TEMPLATE_NEWSLETTER_SUBSCRIBED'),
        'redirect_url' => '/',
    ],
    'unsubscribed' => [
        'subject' => 'Unsubscribed',
        'template_id' => env('SENDGRID_TEMPLATE_NEWSLETTER_UNSUBSCRIBED'),
        'default_action_url' => env('APP_URL') . '/sendgrid-newsletter/{token}/resubscribe',
        'redirect_url' => '/'
    ],
    'excluded-emails' => [
        'admin@cierra.de'
    ],
    'mail' => [
        'from_adress' => 'jan@cierra.de',
        'from_name' => 'App',
    ],
];
