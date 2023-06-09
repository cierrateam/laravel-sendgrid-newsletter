<?php

namespace Cierrateam\LaravelSendgridNewsletter\Facades;

use Illuminate\Support\Facades\Facade;

class SendgridNewsletterFacade extends Facade
{

    /**
    * Get the registered name of the component.
    *
    * @return string
    */
    protected static function getFacadeAccessor() { return 'sendgrid-newsletter'; }
}
