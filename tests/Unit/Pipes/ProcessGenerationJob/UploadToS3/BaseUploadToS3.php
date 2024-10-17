<?php

namespace Tests\Unit\Pipes\ProcessGenerationJob\UploadToS3;

use App\Pipes\ProcessGenerationJob\UploadToS3;
use PHPUnit\Framework\Attributes\Small;
use Tests\TestCase;

/**
 * @group UploadToS3
 */
#[Small] class BaseUploadToS3 extends TestCase
{
    protected UploadToS3 $pipe;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pipe = new UploadToS3;
    }
}
