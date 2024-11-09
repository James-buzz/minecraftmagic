<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\Concerns\AsAction;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class DownloadUrl
{
    use AsAction;

    public function handle(string $url, string $filePath): void
    {
        $action = Storage::put(
            $filePath,
            Http::get($url)->body()
        );

        if (! $action) {
            throw new FileException("Failed to download the file from the url: $url");
        }
    }
}
