<?php

namespace App\Models;

class ArtType
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
    ) {}
}
