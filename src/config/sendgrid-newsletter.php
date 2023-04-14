<?php

return [
    'sendgrid' => [
        'api-key' => env('SENDGRID_API_KEY'),
        'newsletterListIds' => env('NEWSLETTER_SUBSCRIPTION_LIST_IDS'),
        'supressionGroupIds' => env('NEWSLETTER_SUPRESSION_GROUP_IDS'),
        'contactDataKeyMapping' => [
            'first_name' => 'firstName',
            'last_name' => 'lastName',
            'adress_line_1' => 'adressLine',
            'city' => 'city',
            'postal_code' => 'postalCode',
            'country' => 'country',
        ],
    ],
    'confirmation' => [
        'subject' => 'Email confirmation',
        'template_id' => env('SENDGRID_TEMPLATE_EMAIL_CONFIRM'),
        'redirect_url' => env('APP_URL'),
    ],
    'subscribed' => [
        'subject' => 'Subscribed',
        'template_id' => env('SENDGRID_TEMPLATE_NEWSLETTER_SUBSCRIBED'),
        'redirect_url' => env('APP_URL'),
    ],
    'unsubscribed' => [
        'subject' => 'Unsubscribed',
        'template_id' => env('SENDGRID_TEMPLATE_NEWSLETTER_UNSUBSCRIBED'),
        'redirect_url' => env('APP_URL')
    ],
    'excluded-emails' => env('NEWSLETTER_EXCLUDED_EMAILS', 'test@example.com'), // use comma seperated addresses: NEWSLETTER_EXCLUDED_EMAILS="test@cierra.de,test@example.de"
    'mail' => [
        'from_adress' => env('NESLETTER_MAIL_FROM_ADDRESS', 'test@example.com'),
        'from_name' => env('NESLETTER_MAIL_FROM_NAME', 'Cierrateam Sendgrid Newsletter'),
    ],
];
