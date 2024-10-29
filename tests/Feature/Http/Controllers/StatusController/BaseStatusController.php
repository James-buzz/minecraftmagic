<?php

namespace Tests\Feature\Http\Controllers\StatusController;

use Tests\Feature\FeatureTestCase;

class BaseStatusController extends FeatureTestCase
{
    protected string $route;

    protected function setUp(): void
    {
        parent::setUp();

        $this->route = 'status';
    }
}
