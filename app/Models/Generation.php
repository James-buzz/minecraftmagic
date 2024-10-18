<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @mixin Builder
 */
class Generation extends Model
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use HasUlids;
    use LogsActivity;
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

    /**
     * The Log events to be recorded.
     *
     * @var string[]
     */
    protected static array $recordEvents = ['updated', 'deleted'];

    /**
     * @return BelongsTo<User, self>
     */
    public function user(): BelongsTo
    {
        /** @phpstan-ignore-next-line todo */
        return $this->belongsTo(User::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logExcept(['created_at', 'updated_at', 'deleted_at']);
    }
}
