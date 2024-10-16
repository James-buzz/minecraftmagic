<?php

namespace Tests\Unit\Pipes\ProcessGenerationJob\RequestGeneration;

use App\Contracts\ArtServiceInterface;
use App\Pipes\ProcessGenerationJob\RequestGeneration;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Small;
use Tests\TestCase;

/**
 * @group RequestGeneration
 */
#[Small] class BaseRequestGeneration extends TestCase
{
    protected MockInterface|ArtServiceInterface $mockArtService;

    protected RequestGeneration $pipe;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockArtService = $this->mock(ArtServiceInterface::class);

        $this->pipe = new RequestGeneration($this->mockArtService);
    }
}
