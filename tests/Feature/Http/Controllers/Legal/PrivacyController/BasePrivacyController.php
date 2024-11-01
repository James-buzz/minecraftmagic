<?php

namespace Tests\Feature\Http\Controllers\Legal\PrivacyController;

use Tests\Feature\FeatureTestCase;

class BasePrivacyController extends FeatureTestCase
{
    protected string $route;

    public function setUp(): void
    {
        parent::setUp();

        $this->route = 'privacy';
    }
}
