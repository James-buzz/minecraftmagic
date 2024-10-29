<?php

namespace Tests\Unit\Repositories\GenerationRepository;

use App\Repositories\GenerationRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BaseGenerationRepository extends TestCase
{
    use RefreshDatabase;

    protected GenerationRepository $generationRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->generationRepository = new GenerationRepository;
    }
}
