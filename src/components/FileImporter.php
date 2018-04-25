<?php

namespace nullref\documents\components;


abstract class FileImporter extends BaseImporter
{
    public function getDocumentOptions()
    {
        return [
            'file_path' => [
                'type' => self::OPTION_TYPE_FILE,
            ],
        ];
    }

}