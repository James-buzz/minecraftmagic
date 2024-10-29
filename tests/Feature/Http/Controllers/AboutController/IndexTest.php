<?php

namespace Tests\Feature\Http\Controllers\AboutController;

class IndexTest extends BaseAboutController
{
    public function testWhenGetThenRoute(): void
    {
        // Action
        $actualResponse = $this->get(route($this->route));

        // Assert
        $actualResponse->assertOk();
        $actualResponse->assertInertia(
            fn ($assert) => $assert
                ->component('about')
        );
    }
}
