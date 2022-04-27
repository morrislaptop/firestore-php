<?php

namespace TorMorten\Firestore\References;

use Google\Service\Firestore\ListDocumentsResponse;
use TorMorten\Firestore\Requests\Collection as CollectionRequest;

class Collection extends \Illuminate\Support\Collection
{
    protected CollectionRequest $collection;

    public function __construct(ListDocumentsResponse $documents, CollectionRequest $collection)
    {
        $this->mapToDocuments($documents);
        $this->collection = $collection;
    }

    protected function mapToDocuments($documents)
    {
        foreach($documents->getDocuments() as $document) {
            $this->items[] = new Document($document);
        }
    }
}
