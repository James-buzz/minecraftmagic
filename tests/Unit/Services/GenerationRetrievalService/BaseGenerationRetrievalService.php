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
    protected MockInterface $generationRepository;

    /** @var MockInterface&ArtRepositoryInterface */
    protected MockInterface $artRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->generationRepository = $this->mock(GenerationRepositoryInterface::class);
        $this->artRepository = $this->mock(ArtRepositoryInterface::class);

        $this->service = new GenerationRetrievalService(
            $this->generationRepository,
            $this->artRepository
        );
    }
}
