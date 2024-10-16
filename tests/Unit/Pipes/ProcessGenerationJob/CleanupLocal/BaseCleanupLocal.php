<?php

namespace Tests\Unit\Pipes\ProcessGenerationJob\CleanupLocal;

use App\Pipes\ProcessGenerationJob\CleanupLocal;
use PHPUnit\Framework\Attributes\Small;
use Tests\TestCase;

/**
 * @group CleanupLocal
 */
#[Small] class BaseCleanupLocal extends TestCase
{
    protected CleanupLocal $pipe;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pipe = new CleanupLocal;
    }
}
