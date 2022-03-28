<?php

namespace TorMorten\Firestore;

use Kreait\Firebase as BaseFirebase;
use Psr\Http\Message\UriInterface;
use Kreait\Firebase\ServiceAccount;
use TorMorten\Firestore\Http\ApiClient;
use Kreait\Firebase\Factory as BaseFactory;
use GuzzleHttp\Psr7\Utils;

class Factory extends BaseFactory
{
    /**
     * @var UriInterface
     */
    private $firestoreUri;

    private static $firestoreUriPattern = 'https://firestore.googleapis.com/v1beta1/projects/%s/databases/(default)/documents/';

    public function firestore()
    {
        $http = $this->createApiClient();

        return new Firestore($this->getFirestoreUri(), new ApiClient($http));
    }

    private function getFirestoreUri(): UriInterface
    {
        return $this->firestoreUri ?: $this->getFirestoreUriFromServiceAccount($this->getServiceAccount());
    }

    private function getFirestoreUriFromServiceAccount(ServiceAccount $serviceAccount): UriInterface
    {
        return Utils::uriFor(sprintf(self::$firestoreUriPattern, $serviceAccount->getProjectId()));
    }

}
