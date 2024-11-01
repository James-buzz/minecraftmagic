<?php

namespace Tests\Feature\Http\Controllers\AboutController;

use Tests\Feature\FeatureTestCase;

class BaseAboutController extends FeatureTestCase
{
    protected string $route;

    public function setUp(): void
    {
        parent::setUp();

        $this->route = 'about';
    }
}
