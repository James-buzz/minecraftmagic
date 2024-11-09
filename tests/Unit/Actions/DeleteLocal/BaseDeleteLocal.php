<?php

namespace Tests\Unit\Actions\DeleteLocal;

use App\Actions\DeleteLocal;
use Tests\TestCase;

class BaseDeleteLocal extends TestCase
{
    protected DeleteLocal $action;

    public function setUp(): void
    {
        parent::setUp();

        $this->action = new DeleteLocal;
    }
}
