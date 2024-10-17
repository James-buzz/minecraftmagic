<?php

namespace Tests\Feature\Http\Controllers\Legal\TermsController;

use Inertia\Testing\AssertableInertia as AssertInertia;

class IndexTest extends BaseTermsController
{
    public function testIndex(): void
    {
        // Given
        $givenRoute = 'terms';
        $givenInertiaAsset = 'legal/Terms';

        // Action
        $response = $this->get(route($givenRoute));

        // Assert
        $response->assertOk();
        $response->assertInertia(
            fn (AssertInertia $page) => $page
                ->component($givenInertiaAsset)
        );
    }
}
