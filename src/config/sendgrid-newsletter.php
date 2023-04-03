<?php

return [
    'sendgrid' => [
        'api-key' => env('SENDGRID_API_KEY'),   
    ],
    'confirmation' => [
        'subject' => 'Email confirmation',
        'template_id' => env('SENDGRID_TEMPLATE_EMAIL_CONFIRM'),
        'default_action_url' => env('APP_URL') . '/sendgrid-newsletter/{token}/confirmation',
        'redirect' => '/',
    ],
    'subscribed' => [
        'subject' => 'Subscribed',
        'template_id' => env('SENDGRID_TEMPLATE_NEWSLETTER_SUBSCRIBED'),
        'redirect' => '/',
    ],
    'unsubscribed' => [
        'subject' => 'Unsubscribed',
        'template_id' => env('SENDGRID_TEMPLATE_NEWSLETTER_UNSUBSCRIBED'),
        'default_action_url' => env('APP_URL') . '/sendgrid-newsletter/{token}/unsubscribed',
        'redirect' => '/'
    ],
    'excluded-emails' => [
        'admin@cierra.de'
    ],
    'mail' => [
        'from_adress' => 'jan@cierra.de',
        'from_name' => 'App',
    ],
];
