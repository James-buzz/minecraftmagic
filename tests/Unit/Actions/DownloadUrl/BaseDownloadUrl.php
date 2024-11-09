<?php

namespace Tests\Unit\Actions\DownloadUrl;

use App\Actions\DownloadUrl;
use Tests\TestCase;

class BaseDownloadUrl extends TestCase
{
    protected DownloadUrl $action;

    public function setUp(): void
    {
        parent::setUp();

        $this->action = new DownloadUrl;
    }
}
