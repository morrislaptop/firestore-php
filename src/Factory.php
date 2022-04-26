<?php

namespace TorMorten\Firestore;

use Kreait\Firebase as BaseFirebase;
use Psr\Http\Message\UriInterface;
use Kreait\Firebase\ServiceAccount;
use TorMorten\Firestore\Http\ApiClient;
use Kreait\Firebase\Factory as BaseFactory;
use GuzzleHttp\Psr7\Utils;

class Factory
{
    protected BaseFactory $factory;

    public function __construct(BaseFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @var UriInterface
     */
    private $firestoreUri;

    private static $firestoreUriPattern = 'https://firestore.googleapis.com/v1beta1/projects/%s/databases/(default)/documents/';

    public function getFactory()
    {
    }

    public function firestore()
    {
        $http = $this->factory->createApiClient();

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

    private function getServiceAccount(): ?ServiceAccount
    {
        if ($this->serviceAccount !== null) {
            return $this->serviceAccount;
        }

        if ($credentials = Util::getenv('GOOGLE_APPLICATION_CREDENTIALS')) {
            try {
                return $this->serviceAccount = ServiceAccount::fromValue($credentials);
            } catch (InvalidArgumentException $e) {
                // Do nothing, continue trying
            }
        }

        if ($this->discoveryIsDisabled) {
            return null;
        }

        // @codeCoverageIgnoreStart
        // We can't reliably test this without re-implementing it ourselves
        if ($credentials = CredentialsLoader::fromWellKnownFile()) {
            try {
                return $this->serviceAccount = ServiceAccount::fromValue($credentials);
            } catch (InvalidArgumentException $e) {
                // Do nothing, continue trying
            }
        }
        // @codeCoverageIgnoreEnd

        // ... or don't
        return null;
    }

    public function __call(string $name, array $arguments)
    {
        return $this->factory->{$name}(...$arguments);
    }

}
