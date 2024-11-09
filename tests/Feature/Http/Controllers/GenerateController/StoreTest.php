<?php

namespace Tests\Feature\Http\Controllers\GenerateController;

use App\Jobs\ProcessGenerationJob;
use App\Models\ArtStyle;
use App\Models\ArtType;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Str;

class StoreTest extends BaseGenerateController
{
    public function testWhenIndexThenResolve(): void
    {
        // Given
        $givenArtStyleId = Str::ulid();
        $givenArtTypeId = Str::ulid();
        $givenMetadata = [
            'image_size' => '512x512',
            'image_quality' => 'hd',
        ];

        // Precondition
        Bus::fake();

        $preconditionUser = User::factory()->create();

        ArtType::factory()->create([
            'id' => $givenArtTypeId,
        ]);

        ArtStyle::factory()->create([
            'id' => $givenArtStyleId,
            'art_type_id' => $givenArtTypeId,
        ]);

        // Expected
        $expectedUserId = $preconditionUser->id;

        // Action
        $actualResponse = $this->actingAs($preconditionUser)
            ->post(route($this->storeRoute), [
                'art_style' => (string) $givenArtStyleId,
                'art_type' => (string) $givenArtTypeId,
                'metadata' => $givenMetadata,
            ]);

        // Assert
        $this->assertDatabaseHas('generations', [
            'user_id' => $expectedUserId,
            'art_style_id' => $givenArtStyleId,
        ]);
        Bus::assertDispatched(ProcessGenerationJob::class);
    }
}
