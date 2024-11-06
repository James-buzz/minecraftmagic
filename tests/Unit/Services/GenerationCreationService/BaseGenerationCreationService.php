<?php

namespace Tests\Unit\Services\GenerationCreationService;

use App\Contracts\ArtRepositoryInterface;
use App\Services\GenerationService;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Small;
use Tests\Feature\FeatureTestCase;

/**
 * @group GenerationCreationService
 */
#[Small] class BaseGenerationCreationService extends FeatureTestCase
{
    protected GenerationService $service;

    /** @var MockInterface&ArtRepositoryInterface */
    protected MockInterface $artRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->artRepository = $this->mock(ArtRepositoryInterface::class);

        $this->service = new GenerationService(
            $this->artRepository
        );
    }
}
