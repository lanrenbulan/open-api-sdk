<?php

declare(strict_types=1);

namespace Doubler\OpenApiSdk\Encoder;

class Factory
{
    private static array $map = [
        'json' => JsonEncoder::class,
        'xml' => XmlEncoder::class,
        'csv' => CsvEncoder::class,
    ];

    public static function make(string $key): EncoderInterface
    {
        if (!isset(self::$map[$key])) {
            throw new \InvalidArgumentException('Unsupported ' . $key);
        }

        return new self::$map[$key];
    }

    /**
     * @param string $contentType
     * @return EncoderInterface
     */
    public static function makeFromContentType(string $contentType): EncoderInterface
    {
        if (stripos('xml', $contentType) !== false) {
            $key = 'xml';
        } else if (stripos('json', $contentType) !== false) {
            $key = 'json';
        } else if (stripos('csv', $contentType) !== false) {
            $key = 'csv';
        } else {
            throw new \InvalidArgumentException('Unsupported: ' . $contentType);
        }

        return static::make($key);
    }
}