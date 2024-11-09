<?php

namespace App\Actions;

use DateTimeInterface;
use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\Concerns\AsAction;

class SignS3URL
{
    use AsAction;

    public function handle(string $filePath, DateTimeInterface $expiryDateTime): string
    {
        return Storage::disk('s3')->temporaryUrl($filePath, $expiryDateTime);
    }
}
