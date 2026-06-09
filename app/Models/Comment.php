<?php

namespace App\Models;

use App\Enums\CommentStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'body',
    ];

    protected function casts(): array
    {
        return [
            'status' => CommentStatus::class,
        ];
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isPending(): bool
    {
        return $this->status === CommentStatus::Pending;
    }

    public function isApproved(): bool
    {
        return $this->status === CommentStatus::Approved;
    }

    #[Scope]
    protected function approved(Builder $query): Builder
    {
        return $query->where('status', CommentStatus::Approved->value);
    }

    #[Scope]
    protected function pending(Builder $query): Builder
    {
        return $query->where('status', CommentStatus::Pending->value);
    }

    // ─── Accessor ───────────────────────────────────────────────
    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->translatedFormat('d M Y H:i'),
        );
    }
}
