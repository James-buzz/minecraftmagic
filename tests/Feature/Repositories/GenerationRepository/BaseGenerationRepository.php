<?php

namespace Tests\Feature\Repositories\GenerationRepository;

use App\Repositories\GenerationRepository;
use Tests\Feature\FeatureTestCase;

class BaseGenerationRepository extends FeatureTestCase
{
    protected GenerationRepository $generationRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->generationRepository = new GenerationRepository;
    }
}
