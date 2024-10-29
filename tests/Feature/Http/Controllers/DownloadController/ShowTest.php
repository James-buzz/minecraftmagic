<?php

namespace Tests\Feature\Http\Controllers\DownloadController;

use App\Models\Generation;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ShowTest extends BaseDownloadController
{
    public function testWhenGenerationThenDownloadUrl(): void
    {
        // Given
        $givenUserId = 101;

        $givenOriginalFilePath = 'original_file.jpg';
        $givenOriginalFileContents = 'some_random_contents';

        $givenGenerationId = 99;
        $givenGenerationStatus = 'completed';
        $givenGenerationFilePath = 'some_random_file_path';

        // Precondition
        Storage::disk('s3')->put($givenOriginalFilePath, $givenOriginalFileContents);
        Storage::disk('s3')->exists($givenOriginalFilePath);

        $preconditionUser = User::factory()->create([
            'id' => $givenUserId,
        ]);

        Generation::factory()->create([
            'id' => $givenGenerationId,
            'user_id' => $givenUserId,
            'status' => $givenGenerationStatus,
            'file_path' => $givenGenerationFilePath,
        ]);

        // Action
        $actualJson = $this->actingAs($preconditionUser)
            ->getJson(route($this->route, ['id' => $givenGenerationId]));

        // Assert
        $actualJson->assertOk();
        $actualJson->assertJsonStructure([
            'url',
        ]);
    }
}
