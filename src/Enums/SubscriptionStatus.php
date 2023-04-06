<?php

namespace Cierrateam\LaravelSendgridNewsletter\Enums;

use Cierrateam\LaravelSendgridNewsletter\Traits\EnumToArray;

enum SubscriptionStatus: string
{
    use EnumToArray;

    case NotSubscribedYet = 'NotSubscribedYet';
    case Unsubscribed = 'Unsubscribed';
    case Pending = 'Pending';
    case Subscribed = 'Subscribed';
}
