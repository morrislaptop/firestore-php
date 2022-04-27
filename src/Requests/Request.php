<?php

namespace TorMorten\Firestore\Requests;

use Google\Service\Resource;

abstract class Request
{
    protected Resource $resource;
    protected mixed $parameters;

    public function __construct(Resource $resource, $parameters = null)
    {
        $this->resource = $resource;
        $this->parameters = $parameters;
    }

    public function getResource()
    {
        return $this->resource;
    }
}
