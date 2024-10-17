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

        $file = $this->writeTempFile($content);

        $options = new Options();
        $options->FIELD_DELIMITER = $context['delimiter'];
        $reader = new Reader($options);
        $reader->open($file);

        $rows = [];

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                $rows[] = $row->toArray();
            }
        }

        $reader->close();
        unlink($file);

        return $rows;
    }

    /**
     * @param string $content
     * @return string
     */
    private function writeTempFile(string $content): string
    {
        $file = tempnam(sys_get_temp_dir(), 'csv_');

        file_put_contents($file, $content);

        return $file;
    }
}