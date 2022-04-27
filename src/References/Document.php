<?php

namespace TorMorten\Firestore\References;

use Google\Service\Firestore\Document as GoogleDocument;
use Google\Service\Firestore\Value;
use Illuminate\Support\Str;
use TorMorten\Firestore\Requests\Collection as CollectionRequest;
use TorMorten\Firestore\Traits\ModifiesDocument;

class Document
{
    use ModifiesDocument;

    public mixed $id;
    public mixed $document;
    protected mixed $collection;

    public function __construct($id, GoogleDocument $document = null, CollectionRequest $collection = null)
    {
        $this->id = $id;
        $this->document = $document ?? new GoogleDocument();
        $this->collection = $collection;
    }

    public function __get(string $name)
    {
        $fields = $this->getAttributes();

        if(isset($fields[$name])) {
            return $fields[$name];
        }
    }

    public function getAttributes()
    {
        $attributes = [];

        foreach($this->document->getFields() as $name => $field) {
            $attributes[$name] = $this->parseValue($field);
        }

        return $attributes;
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
