<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @mixin IdeHelperCategory
 */
class Category extends Model
{
    protected $fillable = [
        'name',
    ];

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

  
    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => Str::lower(trim($value)),
            get: fn (string $value) => Str::ucfirst($value),
        );
    }
}
