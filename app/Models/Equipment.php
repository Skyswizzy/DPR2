<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Equipment extends Model
{
    /**
     * "equipment" is uncountable, so the table name is set explicitly.
     */
    protected $table = 'equipment';

    /**
     * Single source of truth for the allowed values.
     * The validation rules, form dropdowns and report filters all read from these.
     */
    public const CATEGORIES = [
        'paddles' => 'Paddles',
        'balls' => 'Balls',
        'nets' => 'Nets',
        'accessories' => 'Accessories',
    ];

    public const STATUSES = [
        'available' => 'Available',
        'unavailable' => 'Unavailable',
    ];

    /**
     * user_id is intentionally NOT fillable. It is always assigned on the server
     * from the authenticated user, so a form can never set it.
     */
    protected $fillable = [
        'equipment_name',
        'category',
        'unit_price',
        'description',
        'availability_status',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
    ];

    /**
     * An equipment record belongs to the user who created it.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Reports use this scope. Both filters are optional and are combined with AND,
     * so category + availability work together. A null filter means "no restriction".
     */
    public function scopeFilter(Builder $query, ?string $category, ?string $status): Builder
    {
        return $query
            ->when($category, fn (Builder $q) => $q->where('category', $category))
            ->when($status, fn (Builder $q) => $q->where('availability_status', $status));
    }

    /** Human-readable category, e.g. "paddles" -> "Paddles". */
    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category] ?? ucfirst($this->category);
    }

    /** Human-readable availability, e.g. "unavailable" -> "Unavailable". */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->availability_status] ?? ucfirst($this->availability_status);
    }
}
