<?php

namespace TorMorten\Firestore\Requests;

use Illuminate\Support\Str;
use TorMorten\Firestore\References\Collection as CollectionReference;
use TorMorten\Firestore\References\Document as DocumentReference;
use TorMorten\Firestore\Support\ServiceAccount;

class Collection extends Request
{
    public function buildPath()
    {
        $parentId = Str::replace('/' . $this->getCollectionId(), '', $this->parameters);

        return Str::finish(resolve(ServiceAccount::class)->getParentId(), '/') . $parentId;
    }

    public function getCollectionId()
    {
        return Str::afterLast($this->parameters, '/');
    }

    public function documents()
    {
        return new CollectionReference($this->resource->listDocuments($this->buildPath(), $this->getCollectionId()), $this);
    }

    public function document($id)
    {
        return new DocumentReference($id, null, $this);
    }
}
