<?php

namespace Tests\Unit\Pipes\ProcessGenerationJob\DownloadLocal;

use App\Contracts\GenerationCreationServiceInterface;
use App\Pipes\ProcessGenerationJob\DownloadLocal;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Small;
use Tests\TestCase;

/**
 * @group DownloadLocal
 */
#[Small] class BaseDownloadLocal extends TestCase
{
    protected MockInterface|GenerationCreationServiceInterface $mockCreationService;

    protected DownloadLocal $pipe;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockCreationService = $this->mock(GenerationCreationServiceInterface::class);

        $this->pipe = new DownloadLocal($this->mockCreationService);
    }
}
