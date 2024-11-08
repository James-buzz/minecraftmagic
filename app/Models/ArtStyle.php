<?php

namespace App\Models;

use Database\Factories\ArtStyleFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'prompt',
    ];

    /**
     * Belongs to Art Type
     *
     * @return BelongsTo<ArtType, self>
     */
    public function artType(): BelongsTo
    {
        return $this->belongsTo(ArtType::class);
    }
}
