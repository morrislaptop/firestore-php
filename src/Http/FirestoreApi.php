<?php

namespace TorMorten\Firestore\Http;

use Google\Client;
use Google\Service\Firestore;
use Illuminate\Support\Str;
use TorMorten\Firestore\Requests\Collection;

class FirestoreApi
{
    protected Firestore $client;

    public function __construct()
    {
        $this->buildGoogleClient();
    }

    protected function buildGoogleClient()
    {
        $client = new Client();
        $client->setAuthConfig(base_path(env('FIREBASE_CREDENTIALS')));

        $client->setApplicationName("TorMorten\\Firestore");
        $client->setScopes(['https://www.googleapis.com/auth/datastore']);

        $this->client = new Firestore($client);
    }

    public function collection($path)
    {
        return new Collection($this->client->projects_databases_documents, $path);
    }

}
