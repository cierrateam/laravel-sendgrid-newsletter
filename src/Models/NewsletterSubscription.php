<?php
namespace Cierrateam\LaravelSendgridNewsletter\Models;

use Cierrateam\LaravelSendgridNewsletter\Enums\SubscriptionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use App\Models\User;

class NewsletterSubscription extends Model
{
  use HasFactory;

  // Disable Laravel's mass assignment protection
  protected $guarded = [];

  protected $casts = [
    'status' => SubscriptionStatus::class,
    'subscribed_at' => 'date',
    'unsubscribed_at' => 'date',
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class, 'id', 'user_id');
  }
}