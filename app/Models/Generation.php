<?php

namespace App\Models;

use App\Concerns\HasFeedback;
use Database\Factories\GenerationFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin Builder
 *
 * @method static self create(array $attributes = [])
 * @method static self findOrFail($id, array $columns = ['*'])
 * @method static self find($id, array $columns = ['*'])
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder whereIn($column, $values, $boolean = 'and', $not = false)
 * @method static Builder orderBy($column, $direction = 'asc')
 * @method static Builder|self firstOrCreate(array $attributes = [], array $values = [])
 * @method static Builder|self updateOrCreate(array $attributes, array $values = [])
 * @method static Builder|self firstOrNew(array $attributes = [], array $values = [])
 * @method static \Illuminate\Pagination\LengthAwarePaginator paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
 *
 * @property string $id
 * @property int $user_id
 * @property string $status
 * @property string $art_type
 * @property string $art_style
 * @property array $metadata
 * @property string|null $file_path
 * @property string|null $thumbnail_file_path
 * @property string|null $failed_reason
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 */
class Generation extends Model
{
    /** @use HasFactory<GenerationFactory> */
    use HasFactory;

    use HasFeedback;
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
        'failed_reason',
    ];

    protected $casts = [
        'metadata' => 'array',
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
}
