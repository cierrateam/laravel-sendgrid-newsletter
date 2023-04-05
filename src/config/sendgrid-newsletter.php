<?php

return [
    'sendgrid' => [
        'api-key' => env('SENDGRID_API_KEY'),
        'newsletterListIds' => [],
        'supressionGroupIds' => [],
        'contactDataKeyMapping' => [
            'first_name' => 'firstName'
        ],
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
    'excluded-emails' => env('NEWSLETTER_EXCLUDED_EMAILS', 'test@example.com'), // use comma seperated addresses: NEWSLETTER_EXCLUDED_EMAILS="test@cierra.de,test@example.de"
    'mail' => [
        'from_adress' => env('MAIL_FROM_ADDRESS', 'test@example.com'),
        'from_name' => env('MAIL_FROM_NAME', 'Cierrateam Sendgrid Newsletter'),
    ],
];
