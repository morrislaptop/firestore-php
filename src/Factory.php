<?php

namespace Morrislaptop\Firestore;

use Kreait\Firebase as BaseFirebase;
use Psr\Http\Message\UriInterface;
use Kreait\Firebase\ServiceAccount;
use function GuzzleHttp\Psr7\uri_for;
use Kreait\Firebase\Factory as BaseFactory;

class Factory extends BaseFactory
{
    /**
     * @var UriInterface
     */
    private $firestoreUri;

    private static $firestoreUriPattern = 'https://firestore.googleapis.com/v1beta1/projects/%s/databases/(default)/documents/';

    public function createFirestore() : Firestore
    {
        $http = $this->createApiClient();

        if ($this->uid) {
            $authOverride = new Http\Auth\CustomToken($this->uid, $this->claims);

            $handler = $http->getConfig('handler');
            $handler->push(Middleware::overrideAuth($authOverride), 'auth_override');
        }

        return new Firestore($this->getFirestoreUri(), new ApiClient($http));
    }

    private function getFirestoreUri(): UriInterface
    {
        return $this->firestoreUri ?: $this->getFirestoreUriFromServiceAccount($this->getServiceAccount());
    }

    private function getFirestoreUriFromServiceAccount(ServiceAccount $serviceAccount): UriInterface
    {
        return uri_for(sprintf(self::$firestoreUriPattern, $serviceAccount->getProjectId()));
    }

}
