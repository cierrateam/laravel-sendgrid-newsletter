<?php

namespace Cierra\LaravelSendgridNewsletter\Enums;

use Cierra\LaravelSendgridNewsletter\Traits\EnumToArray;

enum SubscriptionStatus: string
{
    use EnumToArray;

    case Not_Subscribed_Yet = 'Not_Subscribed_Yet';
    case Unsubscribed = 'Unsubscribed';
    case Pending = 'Pending';
    case Subscribed = 'Subscribed';
}