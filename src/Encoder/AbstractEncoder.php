<?php

namespace Doubler\OpenApiSdk\Encoder;

abstract class AbstractEncoder implements EncoderInterface
{
    protected array $defaultContext = [];

    public function __construct(array $defaultContext = [])
    {
        $this->defaultContext = array_merge($this->defaultContext, $defaultContext);
    }
}