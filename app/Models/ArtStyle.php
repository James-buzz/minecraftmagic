<?php

namespace App\Models;

class ArtStyle
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $description,
        public readonly string $prompt,
    ) {}
}
