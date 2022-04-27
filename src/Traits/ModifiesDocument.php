<?php

namespace TorMorten\Firestore\Traits;

use TorMorten\Firestore\Support\ReverseMapValues;

trait ModifiesDocument
{
    public function fetch()
    {
        $this->document = $this->collection->getResource()->get(
            join('/', [
                $this->collection->buildPath(),
                $this->collection->getCollectionId(),
                $this->id
            ])
        );

        return $this;
    }

    public function update($params)
    {
        $this->document->setFields(
            resolve(ReverseMapValues::class)->map(
                array_merge($this->getAttributes(), $params, [
                    'id' => $this->id
                ])
            )
        );

        $this->document = $this->collection->getResource()->patch(
            join('/', [
                $this->collection->buildPath(),
                $this->collection->getCollectionId(),
                $this->id
            ]),
            $this->document
        );

        return $this;
    }

    public function delete()
    {
        $this->document = $this->collection->getResource()->delete(
            join('/', [
                $this->collection->buildPath(),
                $this->collection->getCollectionId(),
                $this->id
            ])
        );

        return true;
    }
}
