<?php

namespace App\Actions;

use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\Concerns\AsAction;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class DeleteLocal
{
    use AsAction;

    public function handle(string $filePath): void
    {
        $operation = Storage::disk('local')->delete($filePath);

        if (! $operation) {
            throw new FileException("Failed to delete file at path: $filePath");
        }
    }
}
