<?php

namespace TorMorten\Firestore\References;

use TorMorten\Firestore\Collections\DocumentCollection;
use TorMorten\Firestore\Document;

/**
 * A Reference represents a specific location in your database and can be used
 * for reading or writing data to that database location.
 *
 * @see https://firebase.google.com/docs/reference/js/firebase.database.Reference
 */
class Query
{
    public function documents(array $options = [])
    {
        $query = http_build_query($options);
        $value = $this->apiClient->get($this->uri . ($query ? "?$query" : ''));
        $documents = new DocumentCollection();

        if (empty($value)) {
            return $documents;
        }

        foreach ($value['documents'] as $doc) {
            $documents->push(new Document(
                $doc['fields'],
                new DocumentReference($this->uri->withPath('/v1beta1/' . $doc['name']), $this->apiClient)
            ));
        }

        return $documents;
    }
}
