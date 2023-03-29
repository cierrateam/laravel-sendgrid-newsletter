<?php

return [
    'sendgrid' => [
        'api-key' => env('SENDGRID_API_KEY'),   
    ],
    'confirmation' => [
        'subject' => 'Email confirmation',
        'template_id' => env('SENDGRID_TEMPLATE_EMAIL_CONFIRM'),
        'dynamic_data' => [],
        'default_action_url' => true
    ],
    'subscribed' => [
        'subject' => 'Subscribed',
        'template_id' => env('SENDGRID_TEMPLATE_NEWSLETTER_SUBSCRIBED'),
        'dynamic_data' => [],
        'default_action_url' => true
    ],
    'unsubscribed' => [
        'subject' => 'Unsubscribed',
        'template_id' => env('SENDGRID_TEMPLATE_NEWSLETTER_UNSUBSCRIBED'),
        'dynamic_data' => [],
        'default_action_url' => true
    ],
    'excluded-emails' => [
        'admin@cierra.de'
    ],
    'mail' => [
        'from_adress' => 'jan@cierra.de',
        'from_name' => 'App',
    ],
    'redicrects' => [
        'after_confirmation' => '/',
        'after_unsubscribed' => '/'
    ]
];
