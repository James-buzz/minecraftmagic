<?php

namespace Tests\Unit\Services\GenerationRetrievalService;

use App\Contracts\ArtRepositoryInterface;
use App\Contracts\GenerationRepositoryInterface;
use App\Services\GenerationRetrievalService;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Small;
use Tests\Feature\FeatureTestCase;

/**
 * @group GenerationRetrievalService
 */
#[Small] class BaseGenerationRetrievalService extends FeatureTestCase
{
    protected GenerationRetrievalService $service;

    /** @var MockInterface&GenerationRepositoryInterface */
    protected MockInterface $mockGenerationRepository;

    /** @var MockInterface&ArtRepositoryInterface */
    protected MockInterface $mockArtRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockGenerationRepository = $this->mock(GenerationRepositoryInterface::class);
        $this->mockArtRepository = $this->mock(ArtRepositoryInterface::class);

        $this->service = new GenerationRetrievalService(
            $this->mockGenerationRepository,
            $this->mockArtRepository
        );
    }
}
