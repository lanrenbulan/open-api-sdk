<?php

namespace Doubler\OpenApiSdk\Encoder;

use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Serializer\Encoder\XmlEncoder as SymfonyXmlEncoder;

class XmlEncoder extends AbstractEncoder
{
    public function decode(string $content, array $context = []): array
    {
        $context = array_merge($this->defaultContext, $context);

        $encoder = new SymfonyXmlEncoder();

        return $encoder->decode($content, SymfonyXmlEncoder::FORMAT, $context);
    }
}