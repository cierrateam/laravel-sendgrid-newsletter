# Composer Package for Sendgrid newsletter subscription

## Overview:
<br>
The cierrateam/laravel-sendgrid-newsletter package is a Laravel package that provides a way to handle newsletter subscriptions using Sendgrid email service.

## Prerequisite:
<br>
Based on three templates which must be created in Sendgrid
1. 'confirmation' - Template: Will be send initially to confirm the Email (needs Button with 'action_url')
2. 'subscribed' - Template: Will be send if the user is successfully subscribed.
3. 'unsubscribed' - Template: Will be send if the user unsubscribes (needs Button with 'action_url')
<br>

## Installation: To install this package, you need to use Composer. Run the following command in your terminal:<br> `composer require cierrateam/laravel-sendgrid-newsletter`

## Configuration:
<br>
After installing the package, add the service provider to the providers array in the config/app.php file:
<br>
`'providers' => [ // Other service providers Cierra\LaravelSendgridNewsletter\LaravelSendgridNewsletterServiceProvider::class, ]`


### Then publish the package configuration file using the following command:
<br>
`php artisan vendor:publish --provider="CierraTeam\\LaravelSendgridNewsletter\\LaravelSendgridNewsletterProvider"`

### Migration command:
<br>
`php artisan migrate`

### Config:<br>
Add your sendgrid api-key.<br>
Add your template_ids from Sendgrid to template_id in config.

## Usage: 
To use the cierra/laravel-sendgrid-newsletter package, you can use the provided methods in your code:

Start the newsletter subscription, creates NewsletterSubscription recod, dispatches the job to send the email with the confirmation template.<br>
`SendgridNewsletter::sendSubscriptionLink($email)`

Updates the NewsletterSubscription based on the token. Created in sendSubscriptionLink<br>
`SendgridNewsletter::subscribe($token)`

Updates the NewsletterSubscription based on the token. Created in sendSubscriptionLink<br>
`SendgridNewsletter::unsubscribe($token)`

Receives the status from the NewsLetterSubscription<br>
`SendgridNewsletter::getSubscriptionStatus($token)`

### Routes:<br>
/sendgrid-newsletter/{token}/confirmation - will be triggered by the 'action_url' from the 'confirm' template in Sendgrid if default_action_url is true

/sendgrid-newsletter/{token}/unsubscribed - will be triggered by the 'action_url' from the 'unsubscribed' Sendgrid template if default_action_url is true

Changelog: 1.0.0:

Initial release
License: This package is licensed under the MIT License.