<?php

namespace Doubler\OpenApiSdk\Encoder;

use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder as SymfonyJsonEncoder;

class JsonEncoder extends AbstractEncoder
{
    public function decode(ResponseInterface $response, array $context = []): mixed
    {
        $context = array_merge($this->defaultContext, $context);

        $encoder = new SymfonyJsonEncoder();

        $content = (string)$response->getBody();

        return $encoder->decode($content, SymfonyJsonEncoder::FORMAT, $context);
    }
}