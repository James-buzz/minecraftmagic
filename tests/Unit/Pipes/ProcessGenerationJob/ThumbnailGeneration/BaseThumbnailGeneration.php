<?php

namespace Tests\Unit\Pipes\ProcessGenerationJob\ThumbnailGeneration;

use App\Contracts\GenerationServiceInterface;
use App\Pipes\ProcessGenerationJob\ThumbnailGeneration;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Small;
use Tests\TestCase;

/**
 * @group ThumbnailGeneration
 */
#[Small] class BaseThumbnailGeneration extends TestCase
{
    protected MockInterface|GenerationServiceInterface $mockCreationService;

    protected ThumbnailGeneration $pipe;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockCreationService = $this->mock(GenerationServiceInterface::class);

        $this->pipe = new ThumbnailGeneration($this->mockCreationService);
    }
}
