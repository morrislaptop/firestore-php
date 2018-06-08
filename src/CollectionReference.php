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
class CollectionReference extends Query
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
    public function __construct(UriInterface $uri, ApiClient $apiClient, ValueMapper $valueMapper = null)
    {
        $this->uri = $uri;
        $this->apiClient = $apiClient;
        $this->valueMapper = $valueMapper ?? new ValueMapper(null, false);
    }

    /**
     * Gets a Reference for the location at the specified relative path.
     *
     * The relative path can either be a simple child name (for example, "ada")
     * or a deeper slash-separated path (for example, "ada/name/first").
     *
     * @see https://firebase.google.com/docs/reference/js/firebase.database.Reference#child
     *
     * @param string $path
     *
     * @throws InvalidArgumentException if the path is invalid
     *
     * @return Reference
     */
    public function document(string $path): DocumentReference
    {
        $childPath = sprintf('%s/%s', trim($this->uri->getPath(), '/'), trim($path, '/'));

        try {
            return new DocumentReference($this->uri->withPath($childPath), $this->apiClient, $this->valueMapper);
        } catch (\InvalidArgumentException $e) {
            throw new InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Remove the data at this database location.
     *
     * Any data at child locations will also be deleted.
     *
     * @see https://firebase.google.com/docs/reference/js/firebase.database.Reference#remove
     *
     * @throws ApiException if the API reported an error
     *
     * @return Reference A new instance for the now empty Reference
     */
    public function remove(): self
    {
        throw new \BadMethodCallException('Not implemented');
    }

}
