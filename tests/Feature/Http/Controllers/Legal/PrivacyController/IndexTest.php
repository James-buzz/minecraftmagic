<?php

namespace Tests\Feature\Http\Controllers\Legal\PrivacyController;

class IndexTest extends BasePrivacyController
{
    public function testWhenGetThenRoute(): void
    {
        // Action
        $actualResponse = $this->get(route($this->route));

        // Assert
        $actualResponse->assertOk();
        $actualResponse->assertInertia(
            fn ($assert) => $assert
                ->component('Legal/Privacy')
        );
    }
}
