<?php

namespace Tests\Unit\Pipes\ProcessGenerationJob\DownloadLocal;

use App\Contracts\GenerationServiceInterface;
use App\Pipes\ProcessGenerationJob\DownloadLocal;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Small;
use Tests\TestCase;

/**
 * @group DownloadLocal
 */
#[Small] class BaseDownloadLocal extends TestCase
{
    protected MockInterface|GenerationServiceInterface $mockCreationService;

    protected DownloadLocal $pipe;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockCreationService = $this->mock(GenerationServiceInterface::class);

        $this->pipe = new DownloadLocal($this->mockCreationService);
    }
}
