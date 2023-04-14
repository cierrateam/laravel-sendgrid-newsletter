<img src="https://cierra.de/img/logo/cierra-dark.png" alt="cierra Logo" width="40%"></img>

# Laravel Sendgrid Newsletter Subscription

## Overview:
<br>
The cierrateam/laravel-sendgrid-newsletter package is a Laravel package that provides a way to handle newsletter subscriptions using Sendgrid email service.

## Requires: 

This packages requires [swiftmade/laravel-sendgrid-notification-channel](https://github.com/swiftmade/laravel-sendgrid-notification-channel) please see the the docs for settings.
## Prerequisite:
<br>
Based on three templates which must be created in Sendgrid

1. 'confirmation' - Template: Will be send initially to confirm the Email (needs Button with 'action_url')
2. 'subscribed' - Template: Will be send if the user is successfully subscribed.
3. 'unsubscribed' - Template: Will be send if the user unsubscribes (needs Button with 'action_url')
4. Create one or more subscription lists.
5. Create a unsubscribe group (supressions).
<br>

## Installation:
<br>
To install this package, you need to use Composer. Run the following command in your terminal:

```composer require cierrateam/laravel-sendgrid-newsletter```

## Configuration:
<br>
After installing the package, add the service provider to the providers array in the config/app.php file:
<br>

```
'providers' => [ // Other service providers CierraTeam\LaravelSendgridNewsletter\LaravelSendgridNewsletterServiceProvider::class, ]
```

### Then publish the package configuration file using the following command:
<br>

```
php artisan vendor:publish --provider="CierraTeam\\LaravelSendgridNewsletter\\LaravelSendgridNewsletterProvider"

```

### Migration command:
```
php artisan migrate
```

### Config:
```newsletterListIds``` <br>
``supressionGroupIds`` <br>
``excluded-emails`` <br>
If more than one added separate them by a comma in the .env like `` NEWSLETTER_SUBSCRIPTION_LIST_IDS="id-11111,id-2222" ``.


## Default usage
Add the sendgrid ``api-key``, ``template_ids`` and ``subject`` to the three templates, the ```newsletterListIds``` and the ``supressionGroupIds`` to the config. 
<br>

#### Subscribe
To subscribe use `SendgridNewsletter::sendSubscriptionLink(string $email, $user_id = null, array $options = null)` in your app. This will automatically start the verification and subscription process. To set dynamic template data like `` {{ first_name }} `` the `` $options `` should contain an array with: <br>

```
dynamic_data => [
    'first_name' => $user->first_name,
    'last_name' => $user->last_name,
    'salutation' => $user->salutation,
] 

```
<br>

#### Unsubscribe
To unsubscribe call this route and provide the unsubscribe_token. This token will be stored also in Sendgrid in the reserved field ``unique_name``.
<code>/sendgrid-newsletter/{unsubscribe_token}/unsubscribe</code>

## Custom usage:
To use the cierra/laravel-sendgrid-newsletter package, you can use the provided methods in your code:

### Options:

As long as no options are set the default options will be taken from the config. ```template_id``` and ```subject```  are required. `dynamic_data` is optional. If the ``default_action_url``, ``default_unsubscribe_action_url`` false or overwritten in the config you need to provide an ``action_url``, ``unsubscribe_action_url`` e.g:<br> 
```
$myOptions = [
    'dynamic_data' => [
        'action_url' => '/route/to/custom',
        'unsubscribe_action_url' => '/route/to/custom',
        'default_action_url' => false
        'default_unsubscribe_action_url' => false
    ]
];

SendgridNewsletter::sendSubscriptionLink('test@cierra.de', $myOptions);

```

### `SendgridNewsletter::sendSubscriptionLink(string $email, $user_id = null, array $options = null)`
Start the newsletter subscription, creates NewsletterSubscription record, dispatches the job to send the email with the confirmation template. Upserts the contact based on the <br>
#### Default options:

```
    return [
        'subject' => config('sendgrid-newsletter.confirmation.subject'),
        'template_id' => config('sendgrid-newsletter.confirmation.template_id'),
        'dynamic_data' => [],
        'default_action_url' => config('sendgrid-newsletter.confirmation.default_action_url'),
        'redirect_url' => config('sendgrid-newsletter.confirmation.redirect'),
    ];
```
### `SendgridNewsletter::subscribe(string $token, array $options = null)`
Updates the NewsletterSubscription based on the token. Created in sendSubscriptionLink.<br>

#### Default options:

```
    return [
            'subject' => config('sendgrid-newsletter.subscribed.subject'),
            'template_id' => config('sendgrid-newsletter.subscribed.template_id'),
            'dynamic_data' => [],
            'redirect_url' => config('sendgrid-newsletter.subscribed.redirect'),
        ];
```

### `SendgridNewsletter::unsubscribe(string $unsubscribe_token, array $options = null)`
Updates the NewsletterSubscription based on the token. Created in sendSubscriptionLink.<br>

#### Default ptions:

```
    return [
            'subject' => config('sendgrid-newsletter.unsubscribed.subject'),
            'template_id' => config('sendgrid-newsletter.unsubscribed.template_id'),
            'dynamic_data' => [],
            'default_action_url' => config('sendgrid-newsletter.unsubscribed.default_action_url'),
            'redirect_url' => config('sendgrid-newsletter.unsubscribed.redirect'),
        ];
```
### `SendgridNewsletter::getSubscriptionStatus($token)`
Receives the status from the NewsLetterSubscription.<br>

### `SendgridNewsletter::updateSubscription(string $token, array $data)`
Updates the Subscription identified by the token.<br>
### `SendgridNewsletter::updateSendgridContact(string $token, array $contactData)`
Updates the created Contact in Sendgrid by the token.<br>


### Routes:<br>
<code>/sendgrid-newsletter/{token}/confirmation</code> - will be triggered by the 'action_url' from the 'confirm' template in Sendgrid if default_action_url is true.

<code>/sendgrid-newsletter/{unsubscribe_token}/unsubscribe</code> - can be called to unsubscribe.

<code>/sendgrid-newsletter/{token}/resubscribe</code> - will be triggered by the 'action_url' from the 'unsubscribed' Sendgrid template if default_action_url is true.

Changelog: v0.1:

Initial release
License: This package is licensed under the MIT License.