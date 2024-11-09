<?php

namespace Tests\Unit\Actions\SignS3Url;

use App\Actions\SignS3URL;
use Tests\TestCase;

class BaseSignS3Url extends TestCase
{
    protected SignS3URL $action;

    public function setUp(): void
    {
        parent::setUp();

        $this->action = new SignS3URL;
    }
}
