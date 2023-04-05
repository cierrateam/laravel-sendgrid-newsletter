<?php

namespace Cierrateam\LaravelSendgridNewsletter\Http\Controller;

use Cierrateam\LaravelSendgridNewsletter\Traits\NewsletterValidations;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, NewsletterValidations;
}
