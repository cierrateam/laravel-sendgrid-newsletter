<?php

namespace Cierrateam\LaravelSendgridNewsletter\Traits;

trait ReturnValues
{
    protected static function returnValues ($status = null, $message = null, $subscription = null, $errors = null, $redirect_url = null)
    {
        return [
            'status' => $status,
            'message' => $message,
            'subscription' => $subscription,
            'errors' => $errors,
            'redirect_url' => $redirect_url
        ];
    }
}