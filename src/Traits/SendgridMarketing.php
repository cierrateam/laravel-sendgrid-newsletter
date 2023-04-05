<?php

namespace Cierrateam\LaravelSendgridNewsletter\Traits;

use Cierrateam\LaravelSendgridNewsletter\Models\NewsletterSubscription;
use Illuminate\Support\Facades\Log;


trait SendgridMarketing
{

  public static function upsertContact($email, $contactData)
  {
    $excluded_emails = explode(',', config('sendgrid-newsletter.excluded-emails'));
    if(in_array($email, $excluded_emails)) return;
    self::removeContactFromSupressionGroups($email);
    $sg = new (\SendGrid::class)(config('sendgrid-newsletter.sendgrid.api-key'));
    $requestBody =
      [
        "list_ids" => self::getNewsletterListIds(),
        "contacts" => [
          $contactData
        ]
      ];
    $requestBody = json_decode(json_encode($requestBody));

    try {
      $response = $sg->client->marketing()->contacts()->put($requestBody);
      if ($response->statusCode() >= 200 && $response->statusCode() <= 400) {
        Log::info('status', [
          'status' => $response->statusCode(),
        ]);
        return true;
      }
      return false;
    } catch (\Exception $e) {
      Log::info(500, 'Caught exception: ' .  $e->getMessage());
    }
  }

  public static function moveContactToSupressionGroups($email)
  {
    $excluded_emails = explode(',', config('sendgrid-newsletter.excluded-emails'));
    if(in_array($email, $excluded_emails)) return;
    $contactId = self::getSendGridContactId($email);
    $sg = new (\SendGrid::class)(config('sendgrid-newsletter.sendgrid.api-key'));
    if ($contactId) {
      self::removeContactFromSendGridList($contactId);
    }
    $requestBody = [ "recipient_emails" => [$email]];
    $group_ids = self::getSupressionGroupIds();

    $requestBody = json_decode(json_encode($requestBody));

    try {
      foreach($group_ids as $group_id) {
        $response = $sg->client->asm()->groups()->_($group_id)->suppressions()->post($requestBody);
      }
    } catch (\Exception $e) {
      Log::info(500, 'Caught exception: ' .  $e->getMessage());
    }
  }

  public static function removeContactFromSupressionGroups($email)
  {
    $sg = new (\SendGrid::class)(config('sendgrid-newsletter.sendgrid.api-key'));
    $group_ids = self::getSupressionGroupIds();

    try {
      foreach($group_ids as $group_id) {
        $response = $sg->client->asm()->groups()->_($group_id)->suppressions()->_($email)->delete();
        Log::info("message:" . $response->statusCode());
      }
    } catch (\Exception $e) {
      Log::info(500, 'Caught exception: ' .  $e->getMessage());
    }
  }

  public static function getSendGridContactId($email)
  {
    $contactId = null;
    $sg = new (\SendGrid::class)(config('sendgrid-newsletter.sendgrid.api-key'));
    $requestBody =
      [
        "emails" => [
          $email
        ]
      ];

    $requestBody = json_decode(json_encode($requestBody));

    try {
      $response = $sg->client->marketing()->contacts()->search()->emails()->post($requestBody);
      if ($response->statusCode() >= 200 && $response->statusCode() <= 400) {
        $body = json_decode($response->body());
        $contactId = $body?->result?->$email?->contact?->id;
      }
      return $contactId;
    } catch (\Exception $e) {
      Log::info(500, 'Caught exception: ' .  $e->getMessage());
    }
  }

  public static function removeContactFromSendGridList($contactId)
  {
    $sg = new (\SendGrid::class)(config('sendgrid-newsletter.sendgrid.api-key'));

    $queryParams =
      [
        "contact_ids" => $contactId
      ];

    $queryParams = json_decode(json_encode($queryParams));

    $listIds = self::getNewsletterListIds();

    try {
      foreach ($listIds as $listId) {
        $sg->client->marketing()->lists()->_($listId)->contacts()->delete(null, $queryParams);
      }
    } catch (\Exception $e) {
      Log::info(500, 'Caught exception: ' .  $e->getMessage());
    }
  }
  public static function getNewsletterListIds()
  {
    return config('sendgrid-newsletter.sendgrid.newsletterListIds');
  }
  public static function getSupressionGroupIds()
  {
    return config('sendgrid-newsletter.sendgrid.supressionGroupIds');
  }
}
