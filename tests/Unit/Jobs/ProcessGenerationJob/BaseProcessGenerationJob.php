<?php

namespace Tests\Unit\Jobs\ProcessGenerationJob;

use App\Jobs\ProcessGenerationJob;
use Tests\TestCase;

class BaseProcessGenerationJob extends TestCase
{
    protected ProcessGenerationJob $job;

    protected string $userId;

    protected string $generationId;

    public function setUp(): void
    {
        parent::setUp();

        $this->userId = 'user-id';
        $this->generationId = 'generation-id';

        $this->job = new ProcessGenerationJob(
            $this->userId,
            $this->generationId
        );
    }
}