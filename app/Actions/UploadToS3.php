<?php

namespace App\Actions;

use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\Concerns\AsAction;

class UploadToS3
{
    use AsAction;

    public function handle(string $filePath): void
    {
        Storage::put(
            $filePath,
            Storage::disk('local')->get($filePath)
        );
    }
}
