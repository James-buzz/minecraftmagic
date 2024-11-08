<?php

namespace App\Models;

use Database\Factories\ArtTypeFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArtType extends Model
{
    /** @use HasFactory<ArtTypeFactory> */
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
     * Get the styles for the art type.
     */
    public function styles(): HasMany
    {
        return $this->hasMany(ArtStyle::class, 'art_type_id');
    }
}
