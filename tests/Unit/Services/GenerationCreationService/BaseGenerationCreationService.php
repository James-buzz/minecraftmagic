<?php

namespace Tests\Unit\Services\GenerationCreationService;

use App\Contracts\ArtRepositoryInterface;
use App\Contracts\GenerationRepositoryInterface;
use App\Services\GenerationCreationService;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Small;
use Tests\Feature\FeatureTestCase;

/**
 * @group GenerationCreationService
 */
#[Small] class BaseGenerationCreationService extends FeatureTestCase
{
    protected GenerationCreationService $service;

    /** @var MockInterface&GenerationRepositoryInterface */
    protected MockInterface $generationRepository;

    /** @var MockInterface&ArtRepositoryInterface */
    protected MockInterface $artRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->generationRepository = $this->mock(GenerationRepositoryInterface::class);
        $this->artRepository = $this->mock(ArtRepositoryInterface::class);

        $this->service = new GenerationCreationService(
            $this->generationRepository,
            $this->artRepository
        );
    }
}
