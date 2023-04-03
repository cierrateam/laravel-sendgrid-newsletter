<?php

namespace Cierrateam\LaravelSendgridNewsletter\Traits;
use Illuminate\Support\Facades\Log;


trait SendgridEmail
{

  public static function sendSendGridEmail(string $targetEmail, string $templateId, string $subject = "", array $dynamicData = [])
  {
    
    if(in_array($targetEmail, config('sendgrid-newsletter.excluded-emails'))) return;
    $mail = new \SendGrid\Mail\Mail();
    $personalization = new \SendGrid\Mail\Personalization();
    $personalization->addTo(new \SendGrid\Mail\To($targetEmail, ''));

    foreach ($dynamicData as $key => $value) {
      $personalization->addDynamicTemplateData($key, $value);
    }

    $mail->addPersonalization($personalization);
    $mail->setTemplateId($templateId);
    $mail->setFrom(config('sendgrid-newsletter.mail.from_adress'), config('sendgrid-newsletter.mail.from_name'));
    $mail->setSubject($subject);
    $sg = new (\SendGrid::class)(config('sendgrid-newsletter.sendgrid.api-key'));
    try {
      $response = $sg->send($mail);
      Log::info($response->statusCode());
      Log::info($response->headers());
      Log::info($response->body());
    } catch (\Exception $e) {
      Log::info('Caught exception: ' . $e->getMessage());
    }
  }

}
