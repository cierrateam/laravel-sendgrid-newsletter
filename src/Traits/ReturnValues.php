<?php

namespace Cierra\LaravelSendgridNewsletter\Traits;

trait ReturnValues
{
    protected static function returnValues ($status = null, $message = null, $subscription = null, $errors = null,)
    {
        return [
            'status' => $status,
            'message' => $message,
            'subscription' => $subscription,
            'errors' => $errors,
        ];
    }
}