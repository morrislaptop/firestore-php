<?php

namespace TorMorten\Firestore\References;

use Google\Service\Firestore\ListDocumentsResponse;

class Collection extends \Illuminate\Support\Collection
{
    public function __construct(ListDocumentsResponse $documents)
    {
        $this->mapToDocuments($documents);
    }

    protected function mapToDocuments($documents)
    {
        foreach($documents->getDocuments() as $document) {
            $this->items[] = new Document($document);
        }
    }
}
