<?php

namespace Morrislaptop\Firestore;

use Psr\Http\Message\UriInterface;
use GuzzleHttp\Psr7\Uri;

/**
 * The Firebase Realtime Database.
 *
 * @see https://firebase.google.com/docs/reference/js/firebase.database.Database
 */
class Firestore
{
    /**
     * @var ApiClient
     */
    private $client;

    /**
     * @var UriInterface
     */
    private $uri;

    /**
     * Creates a new database instance for the given database URI
     * which is accessed by the given API client.
     *
     * @param UriInterface $uri
     * @param ApiClient $client
     */
    public function __construct(UriInterface $uri, ApiClient $client)
    {
        $this->uri = $uri;
        $this->client = $client;
    }

    /**
     * Returns a collection.
     *
     * @param string $name
     *
     * @throws InvalidArgumentException
     *
     * @return Reference
     */
    public function collection(string $path = ''): CollectionReference
    {
        try {
            return new CollectionReference(Uri::resolve($this->uri, $path), $this->client);
        } catch (\InvalidArgumentException $e) {
            throw new InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Returns the root collections.
     */
    public function collections()
    {
        $uri = $this->uri->withPath($this->uri->getPath() . ':listCollectionIds');
        $value = $this->client->post($uri, null);
        $collections = [];

        foreach ($value['collectionIds'] as $id) {
            $collections[] = $this->collection($id);
        }

        return $collections;
    }
}
