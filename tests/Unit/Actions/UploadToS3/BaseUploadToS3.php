<?php

namespace Tests\Unit\Actions\UploadToS3;

use App\Actions\UploadToS3;
use Tests\TestCase;

class BaseUploadToS3 extends TestCase
{
    protected UploadToS3 $action;

    public function setUp(): void
    {
        parent::setUp();

        $this->action = new UploadToS3;
    }
}
