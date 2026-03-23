<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Event extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name',
        'slug',
        'date',
        'description',
        'logo_path',
        'banner_path',
        'is_active',
        'is_timed',
        'is_published',
    ];

    protected $casts = [
        'date' => 'datetime',
        'is_active' => 'boolean',
        'is_timed' => 'boolean',
        'is_published' => 'boolean',
    ];

    public function settings(): HasMany
    {
        return $this->hasMany(EventSetting::class);
    }

    public function brands(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Brand::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }

    public function judges(): HasMany
    {
        return $this->hasMany(EventJudge::class);
    }

    public function evaluationCategories(): HasMany
    {
        return $this->hasMany(EvaluationCategory::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }

    public function getSetting(string $key, mixed $default = null): mixed
    {
        $setting = $this->settings()->where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Get array of revealed positions (e.g. [3, 2, 1])
     */
    public function getRevealedPositions(): array
    {
        $raw = $this->getSetting('revealed_positions', '[]');
        return json_decode($raw, true) ?: [];
    }

    /**
     * Add a position to revealed set
     */
    public function revealPosition(int $pos): void
    {
        $current = $this->getRevealedPositions();
        if (!in_array($pos, $current)) {
            $current[] = $pos;
            $this->settings()->updateOrCreate(
                ['key' => 'revealed_positions'],
                ['value' => json_encode($current)]
            );
        }
    }

    /**
     * Add a position to revealed set
     */
    public function registerMediaConversions(?\Spatie\MediaLibrary\MediaCollections\Models\Media $media = null): void
    {
        $this->addMediaConversion('optimized')
            ->width(1920)
            ->height(1920)
            ->format('webp')
            ->quality(80)
            ->sharpen(10)
            ->performOnCollections('gallery_photos', 'gallery_brand_logos', 'gallery', 'logo', 'banner')
            ->nonQueued();

        $this->addMediaConversion('thumb')
            ->width(600)
            ->height(600)
            ->format('webp')
            ->quality(75)
            ->sharpen(10)
            ->performOnCollections('gallery_photos', 'logo')
            ->nonQueued();
    }

    /**
     * Reset all revealed positions
     */
    public function resetRevealedPositions(): void
    {
        $this->settings()->updateOrCreate(
            ['key' => 'revealed_positions'],
            ['value' => '[]']
        );
    }
}
