<?php

namespace Doubler\OpenApiSdk\Encoder;

use OpenSpout\Reader\CSV\Reader;
use OpenSpout\Reader\CSV\Options;

class CsvEncoder extends AbstractEncoder
{
    public function __construct(array $defaultContext = [])
    {
        $context = array_merge([
            'delimiter' => ',',
        ], $defaultContext);

        parent::__construct($context);
    }

    public function decode(string $content, array $context = []): array
    {
        $context = array_merge($this->defaultContext, $context);

        $options = new Options();
        $options->FIELD_DELIMITER = $context['delimiter'];

        $stream = fopen('php://temp', 'r+');
        fwrite($stream, $content);
        rewind($stream);

        $reader = new Reader($options);

        $reader->open($stream);

        $rows = [];

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                $rows[] = $row->toArray();
            }
        }

        $reader->close();
        fclose($stream);

        return $rows;
    }
}