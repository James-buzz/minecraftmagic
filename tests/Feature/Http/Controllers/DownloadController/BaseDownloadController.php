<?php

namespace Tests\Feature\Http\Controllers\DownloadController;

use Illuminate\Support\Facades\Storage;
use Tests\Feature\FeatureTestCase;

class BaseDownloadController extends FeatureTestCase
{
    protected string $route;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('s3');

        $this->route = 'download';
    }
}
