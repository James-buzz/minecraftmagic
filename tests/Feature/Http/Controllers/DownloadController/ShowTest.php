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
        $givenOriginalFilePath = 'original_file.jpg';
        $givenOriginalFileContents = 'some_random_contents';

        $givenGenerationStatus = 'completed';
        $givenGenerationFilePath = 'some_random_file_path';

        // Precondition
        Storage::disk('s3')->put($givenOriginalFilePath, $givenOriginalFileContents);
        Storage::disk('s3')->exists($givenOriginalFilePath);

        $preconditionUser = User::factory()->create();

        $preconditionGeneration = Generation::factory()->create([
            'user_id' => $preconditionUser->id,
            'status' => $givenGenerationStatus,
            'file_path' => $givenGenerationFilePath,
        ]);

        // Action
        $actualJson = $this->actingAs($preconditionUser)
            ->getJson(route($this->route, ['generation' => $preconditionGeneration->id]));

        // Assert
        $actualJson->assertOk();
        $actualJson->assertJsonStructure([
            'url',
        ]);
    }
}
