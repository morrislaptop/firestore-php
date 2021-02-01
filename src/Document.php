<?php

namespace TorMorten\Firestore;

use TorMorten\Firestore\References\DocumentReference;
use TorMorten\Firestore\Support\MapValues;

class Document
{
    protected array $fields;
    /**
     * @var DocumentReference
     */
    protected DocumentReference $reference;

    public function __construct(array $fields, DocumentReference $reference)
    {
        $this->update($fields);
        $this->reference = $reference;
    }

    public function update($fields)
    {
        $this->fields = resolve(MapValues::class)->map($fields);
    }

    public function __get(string $name)
    {
        if (isset($this->fields[$name])) {
            return $this->fields[$name];
        }
    }

    public function __call(string $name, array $arguments)
    {
        $this->reference->setDocument($this);

        return $this->reference->{$name}(...$arguments);
    }
}
