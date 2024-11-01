<?php

namespace Tests\Feature\Http\Controllers\Legal\TermsController;

use Tests\Feature\FeatureTestCase;

class BaseTermsController extends FeatureTestCase
{
    protected string $route;

    public function setUp(): void
    {
        parent::setUp();

        $this->route = 'terms';
    }
}
