<?php

namespace Doubler\OpenApiSdk\Encoder;

use Symfony\Component\Serializer\Encoder\JsonEncoder as SymfonyJsonEncoder;

class JsonEncoder extends AbstractEncoder
{
    public function decode(string $content, array $context = []): array
    {
        $context = array_merge($this->defaultContext, $context);

        $encoder = new SymfonyJsonEncoder();

        return $encoder->decode($content, SymfonyJsonEncoder::FORMAT, $context);
    }
}