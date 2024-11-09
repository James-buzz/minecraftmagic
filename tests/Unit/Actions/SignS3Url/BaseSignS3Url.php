<?php

namespace Tests\Unit\Actions\SignS3Url;

use App\Actions\SignS3Url;
use Tests\TestCase;

class BaseSignS3Url extends TestCase
{
    protected SignS3Url $action;

    public function setUp(): void
    {
        parent::setUp();

        $this->action = new SignS3Url;
    }
}
