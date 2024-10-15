<?php

namespace Tests\Feature\Repositories\ArtRepository;

use App\Repositories\ArtRepository;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\FeatureTestCase;

class BaseArtRepository extends FeatureTestCase
{
    protected ArtRepository $repository;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake();

        $this->repository = new ArtRepository();
    }
}
