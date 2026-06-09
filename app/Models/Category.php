<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
// use Illuminate\Support\Facades\Storage; // per generare l'URL pubblico del file (quando non usavo Spatie Media Library)
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @mixin IdeHelperCategory
 */
// Per usare la Media Library il modello deve "implements HasMedia" e "use InteractsWithMedia".
class Category extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    // Nome della media collection per la foto della categoria
    public const MEDIA_IMAGE = 'image';

    protected $fillable = [
        'name',
        // 'image', // VECCHIO sistema: la foto ora è gestita dalla tabella `media`
    ];

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    /**
     * Collection "image" per la foto della categoria
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::MEDIA_IMAGE)
            ->singleFile()
            ->acceptsMimeTypes(config('media.images.mime_types'));
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => Str::lower(trim($value)),
            get: fn (string $value) => Str::ucfirst($value),
        );
    }

    // ─── VECCHIO sistema (NON più usato) ───────────────
    // Accessor che permetteva $category->image_url leggendo dalla colonna `image`.
    // protected function imageUrl(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn () => $this->image ? Storage::disk('public')->url($this->image) : null,
    //     );
    // }

    // ─── NUOVO sistema (Spatie Media Library) ─────────────────────────────────
    // l'URL arriva dalla collection "image" della Media Library invece che dalla colonna
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getFirstMediaUrl(self::MEDIA_IMAGE) ?: null,
        );
    }
}
