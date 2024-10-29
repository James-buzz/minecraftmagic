<?php

namespace Tests\Feature\Http\Controllers\GenerateController;

use App\Jobs\ProcessGenerationJob;
use App\Models\User;
use Illuminate\Support\Facades\Bus;

class StoreTest extends BaseGenerateController
{
    public function testWhenIndexThenResolve(): void
    {
        // Given
        $givenUserId = 101;

        $givenArtType = 'server_logo';
        $givenArtStyle = 'dragons-lair';
        $givenMetadata = [
            'image_size' => '512x512',
            'image_quality' => 'hd',
        ];

        // Precondition
        Bus::fake();

        $preconditionUser = User::factory()->create([
            'id' => $givenUserId,
        ]);

        // Expected
        $expectedUserId = $givenUserId;
        $expectedArtType = $givenArtType;
        $expectedArtStyle = $givenArtStyle;

        // Action
        $actualResponse = $this->actingAs($preconditionUser)
            ->post(route($this->storeRoute), [
                'art_type' => $givenArtType,
                'art_style' => $givenArtStyle,
                'metadata' => $givenMetadata,
            ]);

        // Assert
        $this->assertDatabaseHas('generations', [
            'user_id' => $expectedUserId,
            'art_type' => $expectedArtType,
            'art_style' => $expectedArtStyle,
        ]);
        Bus::assertDispatched(ProcessGenerationJob::class);
    }
}
