<?php

namespace App\Models;

use Database\Factories\ArtStyleFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $prompt
 */
class ArtStyle extends Model
{
    /** @use HasFactory<ArtStyleFactory> */
    use HasFactory;

    use HasUlids;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'description',
        'resource_path',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var string[]
     */
    protected $hidden = [
        'prompt',
    ];

    /**
     * Belongs to Art Type
     *
     * @return BelongsTo<ArtType, self>
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(ArtType::class, 'art_type_id');
    }
}
