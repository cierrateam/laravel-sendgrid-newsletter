<?php

namespace Cierrateam\LaravelSendgridNewsletter;

use Illuminate\Support\Facades\Log;

class SendgridNewsletterLogger
{
  public static function log(...$args)
  {
    $enableDebugging = config('sendgrid-newsletter.enableDebugging', false);
    
    if (!$enableDebugging)
      return;

    Log::info(...$args);
  }
}
