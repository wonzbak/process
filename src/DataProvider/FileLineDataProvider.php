<?php

namespace App\DataProvider;

class FileLineDataProvider extends DataProviderInterface
{
    public function provide(mixed $filename): iterable
    {
        try {
            $file = new \SplFileObject($filename);

        } catch (\Exception $e) {
            throw new DataProviderException("Failed to open file $filename", null, $e);

        }
        $file->setFlags(\SplFileObject::DROP_NEW_LINE);

        if (!$file->isReadable()) {
            throw new DataProviderException("File $filename is not readable");
        }

        while (!$file->eof()) {
            yield $file->fgets();
        }

        $file = null;
    }
}