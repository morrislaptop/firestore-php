<?php

namespace Morrislaptop\Firestore;

use Psr\Http\Message\UriInterface;
use Kreait\Firebase\Exception\ApiException;
use Kreait\Firebase\Database\Reference\Validator;
use Kreait\Firebase\Exception\OutOfRangeException;
use Kreait\Firebase\Exception\InvalidArgumentException;

/**
 * A Reference represents a specific location in your database and can be used
 * for reading or writing data to that database location.
 *
 * @see https://firebase.google.com/docs/reference/js/firebase.database.Reference
 */
class DocumentReference
{
    /**
     * @var UriInterface
     */
    protected $uri;

    /**
     * @var ApiClient
     */
    protected $apiClient;

    /**
     * @var ValueMapper
     */
    protected $valueMapper;

    /**
     * Creates a new Reference instance for the given URI which is accessed by
     * the given API client and validated by the Validator (obviously).
     *
     * @param UriInterface $uri
     * @param ApiClient $apiClient
     * @param Validator|null $validator
     *
     * @throws InvalidArgumentException if the reference URI is invalid
     */
    public function __construct(UriInterface $uri, ApiClient $apiClient = null, ValueMapper $valueMapper = null)
    {
        $this->uri = $uri;
        $this->apiClient = $apiClient;
        $this->valueMapper = $valueMapper ?? new ValueMapper(null, false);
    }

    /**
     * Write data to this database location.
     *
     * This will overwrite any data at this location and all child locations.
     *
     * Passing null for the new value is equivalent to calling {@see remove()}:
     * all data at this location or any child location will be deleted.
     *
     * @param mixed $value
     *
     * @throws ApiException if the API reported an error
     *
     * @return Reference
     */
    public function set($value, $merge = false): self
    {
        $payload = [
            'name' => basename($this->uri->getPath()),
            'fields' => $this->valueMapper->encodeValues($value),
        ];

        if ($merge) {
            $paths = $this->valueMapper->encodeFieldPaths($value);
            $prefix = '&updateMask.fieldPaths=';
            $query = $prefix . implode($prefix, $paths);
            $uri = $this->uri->withQuery("updateMask.fieldPaths=message$query");
        }
        else {
            $uri = $this->uri;
        }

        $this->apiClient->patch($uri, $payload);

        return $this;
    }

    /**
     * Returns a data snapshot of the current location.
     *
     * @throws ApiException if the API reported an error
     *
     * @return Snapshot
     */
    public function snapshot(): DocumentSnapshot
    {
        $value = $this->apiClient->get($this->uri);

        $data = $this->valueMapper->decodeValues($value['fields']);

        return new DocumentSnapshot($this, [], $data, true);
    }

    public function delete()
    {
        $this->apiClient->delete($this->uri);
    }
}
