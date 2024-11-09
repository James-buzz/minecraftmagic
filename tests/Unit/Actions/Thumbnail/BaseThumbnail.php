<?php

namespace Tests\Unit\Actions\Thumbnail;

use App\Actions\Thumbnail;
use Tests\TestCase;

class BaseThumbnail extends TestCase
{
    protected Thumbnail $action;

    public function setUp(): void
    {
        parent::setUp();

        $this->action = new Thumbnail;
    }
}
