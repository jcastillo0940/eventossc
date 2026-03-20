<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Criterion extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'max_score', 'weight', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'max_score' => 'float',
        'weight' => 'float',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(EvaluationCategory::class, 'category_id');
    }
}
