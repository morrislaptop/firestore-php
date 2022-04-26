<?php

namespace TorMorten\Firestore\References;

use Google\Service\Firestore\Document as GoogleDocument;
use Google\Service\Firestore\Value;
use Illuminate\Support\Str;

class Document
{
    protected GoogleDocument $document;

    public function __construct(GoogleDocument $document)
    {
        $this->document = $document;
    }

    public function __get(string $name)
    {
        $fields = $this->document->getFields();

        if(isset($fields[$name])) {
            return $this->parseValue($fields[$name]);
        }
    }

    public function parseValue(Value $value)
    {
        foreach(['boolean', 'bytes', 'double', 'integer', 'null', 'reference', 'string', 'timestamp'] as $type) {
            $typeName = $type . 'Value';

            if($value->{$typeName}) {
                return $value->{$typeName};
            }
        }
    }
}
