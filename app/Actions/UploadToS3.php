<?php

namespace App\Actions;

use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\Concerns\AsAction;

class UploadToS3
{
    use AsAction;

    public function handle(string $filePath): void
    {
        Storage::disk('s3')->put(
            $filePath,
            Storage::disk('local')->get($filePath)
        );
    }
}
