<?php

namespace Doubler\OpenApiSdk\Encoder;

use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Serializer\Encoder\XmlEncoder as SymfonyXmlEncoder;

class XmlEncoder extends AbstractEncoder
{
    public function decode(ResponseInterface $response, array $context = []): mixed
    {
        $context = array_merge($this->defaultContext, $context);

        $encoder = new SymfonyXmlEncoder();

        $content = (string)$response->getBody();

        return $encoder->decode($content, SymfonyXmlEncoder::FORMAT, $context);
    }
}