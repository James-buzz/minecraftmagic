<?php

namespace Tests\Feature\Http\Controllers\GenerateController;

use Tests\Feature\FeatureTestCase;

class BaseGenerateController extends FeatureTestCase
{
    protected string $indexRoute;
    protected string $storeRoute;

    public function setUp(): void
    {
        parent::setUp();

        $this->indexRoute = 'generate.index';
        $this->storeRoute = 'generate.store';
    }
}
