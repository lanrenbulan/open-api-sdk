<?php

namespace Doubler\OpenApiSdk\Encoder;

interface EncoderInterface
{
    public function decode(string $content, array $context = []): array;
}