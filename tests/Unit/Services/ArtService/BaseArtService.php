<?php

namespace Tests\Unit\Services\ArtService;

use App\Contracts\ArtRepositoryInterface;
use App\Services\ArtService;
use Mockery\MockInterface;
use Tests\Feature\FeatureTestCase;

class BaseArtService extends FeatureTestCase
{
    protected ArtService $service;

    /** @var MockInterface&ArtRepositoryInterface */
    protected MockInterface $repository;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->mock(ArtRepositoryInterface::class);

        $this->service = new ArtService($this->repository);
    }
}
