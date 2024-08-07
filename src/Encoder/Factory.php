<?php

namespace Doubler\OpenApiSdk\Encoder;

class Factory
{
    private static array $map = [
        'application/json' => JsonEncoder::class,
        'application/xml' => XmlEncoder::class,
        'json' => JsonEncoder::class,
        'xml' => XmlEncoder::class,
    ];

    public static function make(string $key): EncoderInterface
    {
        if (!isset(self::$map[$key])) {
            throw new \InvalidArgumentException('Invalid key.');
        }

        return new self::$map[$key];
    }
}