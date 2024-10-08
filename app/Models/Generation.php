<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static create(array $data)
 * @method static findOrFail(string $id)
 * @method static select(string[] $array)
 * @method static where(string $string, mixed $mixed)
 */
class Generation extends Model
{
    use HasFactory;
    use HasUlids;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'status',
        'art_type',
        'art_style',
        'metadata',
        'file_path',
        'thumbnail_file_path',
    ];

    protected $hidden = [
        'user_id',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }
}
