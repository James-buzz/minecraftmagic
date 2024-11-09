<?php

namespace Tests\Unit\Actions\GenerateImage;

use App\Actions\GenerateImage;
use Tests\TestCase;

class BaseGenerateImage extends TestCase
{
    protected GenerateImage $action;

    public function setUp(): void
    {
        parent::setUp();

        $this->action = new GenerateImage;
    }
}
