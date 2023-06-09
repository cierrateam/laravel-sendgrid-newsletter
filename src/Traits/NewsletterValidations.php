<?php

namespace Cierrateam\LaravelSendgridNewsletter\Traits;

use Illuminate\Support\Facades\Validator;

trait NewsletterValidations
{
    protected static function validateConfirmEmail ($email, $user_id = null)
    {
        $rules = [
            'email' => 'required',
            'user_id' => 'nullable',
        ];

        $messages = [
            'required' => ':attribute muss angegeben werden.',
            'size' => 'The :attribute must be exactly :size.',
            'reqzuired' => ':attribute muss ausgefüllt werden.',
        ];

        return Validator::make([
            'email' => $email,
            'user_id' => $user_id,
        ], $rules, $messages);
    }

    protected static function validateToken($token)
    {
        $rules = [
            'token' => 'required',
        ];

        $messages = [
            'required' => ':attribute muss angegeben werden.',
        ];

        return Validator::make([
            'token' => $token,
        ], $rules, $messages);
    }
}
