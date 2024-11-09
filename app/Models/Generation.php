<?php

namespace App\Models;

use App\Concerns\HasFeedback;
use Database\Factories\GenerationFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

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
 * @method static LengthAwarePaginator paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
 *
 * @property string $id
 * @property int $user_id
 * @property string $status
 * @property string $art_type_id
 * @property string $art_style_id
 * @property array $metadata
 * @property string|null $file_path
 * @property string|null $thumbnail_file_path
 * @property string|null $failed_reason
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property User $user
 * @property ArtStyle $style
 */
class Generation extends Model
{
    /** @use HasFactory<GenerationFactory> */
    use HasFactory;

    /** @use HasFeedback<Generation> */
    use HasFeedback;

    use HasUlids;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'status',
        'art_type_id',
        'art_style_id',
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

    public function markAsFailed(?string $failedMessage): void
    {
        $modelData = ['status' => 'failed'];

        if ($failedMessage !== null) {
            $modelData['failed_reason'] = $failedMessage;
        }

        $this->update($modelData);
    }

    public function markAsCompleted(string $filePath, string $thumbnailFilePath): void
    {
        $this->update([
            'status' => 'completed',
            'file_path' => $filePath,
            'thumbnail_file_path' => $thumbnailFilePath,
        ]);
    }

    public function markAsProcessing(): void
    {
        $this->update(['status' => 'processing']);
    }

    /**
     * Get a temporary URL for the thumbnail
     *
     * @return Attribute<string|null, never>
     */
    protected function thumbnailUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->thumbnail_file_path
                ? Storage::disk('s3')->temporaryUrl($this->thumbnail_file_path, now()->addMinutes(5))
                : null
        );
    }

    /**
     * Scope to include only completed
     *
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 'completed');
    }

    /**
     * Get the art style
     *
     * @return BelongsTo<ArtStyle, $this>
     */
    public function style(): BelongsTo
    {
        return $this->belongsTo(ArtStyle::class, 'art_style_id');
    }

    /**
     * Belongs to User
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
