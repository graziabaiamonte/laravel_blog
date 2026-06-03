<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage; // per generare l'URL pubblico del file
use Illuminate\Support\Str;
use App\Models\IdeHelperCategory;

/**
 * @mixin IdeHelperCategory
 */
class Category extends Model
{
    protected $fillable = [
        'name',
        'image',
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

    // Accessor che permette di usare $category->image_url nelle view,
    // trasformando il percorso salvato (es. "categories/abc.jpg") nell'URL pubblico completo
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->image ? Storage::disk('public')->url($this->image) : null,
        );
    }
}
