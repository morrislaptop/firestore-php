<?php

namespace Morrislaptop\Firestore;

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
        $value = $this->apiClient->get($this->uri . '?' . http_build_query($options));

        if (empty($value)) {
            return [];
        }

        $rows = [];
        foreach ($value['documents'] as $doc)
        {
            $path = '/v1beta1/' . $doc['name'];
            $ref = new DocumentReference($this->uri->withPath($path), $this->apiClient);

            $data = $this->valueMapper->decodeValues($doc['fields']);

            $rows[] = new DocumentSnapshot($ref, [], $data, true);
        }

        return new QuerySnapshot($this, $rows);
    }
}
