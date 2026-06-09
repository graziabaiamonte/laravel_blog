<?php

namespace App\Models;

use App\Enums\ArticleStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // libreria per le date di laravel
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

// use Spatie\MediaLibrary\MediaCollections\Models\Media;   // il modello Media, usato come type-hint in registerMediaCollections()

// /**
//  * @mixin IdeHelperArticle
//  */

class Article extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    // Nome della media collection per la foto principale dell'articolo
    public const MEDIA_COVER = 'cover';

    protected $fillable = [
        'title',
        'content',
        'category_id',
        // 'image',
    ];

    /**
     * Conversione automatica dei tipi.
     * Così $article->status restituisce un enum ArticleStatus (non una stringa)
     * e possiamo scrivere $article->status === ArticleStatus::Published.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ArticleStatus::class,
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::MEDIA_COVER)
            ->singleFile()
            ->acceptsMimeTypes(config('media.images.mime_types'));
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    // Laravel, quando accedi a $article->title, non legge direttamente la colonna ma chiama prima questo metodo perché il nome combacia con quello di una colonna del DB. (convenzione)
    protected function title(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Str::title($value), // Title Case
            set: fn (string $value) => trim($value),
        );
    }

    // ─── VECCHIO sistema (NON più usato) ───────────────
    // Accessor che permetteva di usare $article->image_url,
    // trasformando il percorso salvato (es. "articles/abc.jpg") nell'URL pubblico completo grazie al symlink.
    // Dipendeva dalla colonna `image`, ora rimpiazzata dalla Media Library.
    // protected function imageUrl(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn () => $this->image ? Storage::disk('public')->url($this->image) : null,
    //     );
    // }

    // ─── NUOVO sistema (Spatie Media Library) ─────────────────────────────────
    // nelle view $article->cover_url ritorna l'URL della cover
    // o stringa vuota
    protected function coverUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getFirstMediaUrl(self::MEDIA_COVER) ?: null,
        );
    }

    protected function excerpt(): Attribute
    {
        return Attribute::make(
            get: fn () => Str::limit(strip_tags($this->content), 120), // strip_tags(...) rimuove i tag HTML e lascia solo il testo.
        );
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->translatedFormat('d M Y'),
        );
    }

    #[Scope]
    protected function search(Builder $query, ?string $term): Builder
    {
        return $query->when($term, fn ($q, $term) => $q->where(fn ($q) => $q->where('title', 'like', "%{$term}%")
            ->orWhere('content', 'like', "%{$term}%")
        )
        );
    }

    #[Scope]
    protected function inCategory(Builder $query, ?int $categoryId): Builder
    {
        return $query->when($categoryId, fn ($q, $id) => $q->where('category_id', $id)
            // es. SELECT * FROM articles WHERE category_id = 5
        );
    }
    //
    // Builder $query: primo parametro obbligatorio per ogni scope. È l'istanza del query builder di Eloquent su cui si costruisce la query. Laravel lo passa automaticamente.
    //
    // when($categoryId, ...) alternativa a if: se il primo argomento ($categoryId) è truthy (non null, non 0, non stringa vuota), esegue la callback passata come secondo argomento.

    #[Scope]
    protected function withTag(Builder $query, ?int $tagId): Builder
    {
        return $query->when($tagId, fn ($q, $id) => $q->whereHas('tags', fn ($q) => $q->where('tags.id', $id))
        );
    }

    public function isDraft(): bool
    {
        return $this->status === ArticleStatus::Draft;
    }

    public function isPublished(): bool
    {
        return $this->status === ArticleStatus::Published;
    }

    #[Scope]
    protected function published(Builder $query): Builder
    {
        return $query->where('status', ArticleStatus::Published->value);
    }

    // Scope che filtra gli articoli di uno specifico utente.
    // A differenza degli scope sopra (che usano when() perché il filtro è opzionale),
    // qui l'utente è SEMPRE obbligatorio
    #[Scope]
    protected function ownedBy(Builder $query, User $user): Builder
    {
        return $query->where('user_id', $user->id);
    }
}

// whereHas(...) è un metodo Eloquent per filtrare in base a una relazione. Vuol dire: "prendi solo gli articoli che hanno almeno un record correlato tramite la relazione tags, e per cui la condizione interna è vera".
// 'tags' è il nome del metodo sopra con BelongsToMany. Trattandosi di una relazione many-to-many, Laravel sa che deve fare una JOIN attraverso la tabella pivot
