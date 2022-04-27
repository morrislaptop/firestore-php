<?php

namespace TorMorten\Firestore;

use TorMorten\Firestore\Http\FirestoreApi;

/**
 * The Firebase Realtime Database.
 *
 * @see https://firebase.google.com/docs/reference/js/firebase.database.Database
 */
class Firestore
{
    /**
     * @var FirestoreApi
     */
    private FirestoreApi $client;

    /**
     * Creates a new database instance for the given database URI
     * which is accessed by the given API client.
     *
     * @param FirestoreApi $client
     */
    public function __construct(FirestoreApi $client)
    {
        $this->client = $client;
    }

    public function __call(string $name, array $arguments)
    {
        return $this->client->{$name}(...$arguments);
    }
}
