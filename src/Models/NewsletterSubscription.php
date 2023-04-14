<?php
namespace Cierrateam\LaravelSendgridNewsletter\Models;

use Cierrateam\LaravelSendgridNewsletter\Enums\SubscriptionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class NewsletterSubscription extends Model
{
  use HasFactory;

  // Disable Laravel's mass assignment protection
  protected $guarded = [];

  protected $casts = [
    'status' => SubscriptionStatus::class,
    'dynamic_template_data' => 'array',
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }
}
