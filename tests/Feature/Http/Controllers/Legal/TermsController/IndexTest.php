<?php

namespace Tests\Feature\Http\Controllers\Legal\TermsController;

class IndexTest extends BaseTermsController
{
    public function testWhenGetThenRoute(): void
    {
        // Action
        $actualResponse = $this->get(route($this->route));

        // Assert
        $actualResponse->assertOk();
        $actualResponse->assertInertia(
            fn ($assert) => $assert
                ->component('Legal/Terms')
        );
    }
}
