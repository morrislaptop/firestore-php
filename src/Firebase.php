<?php

namespace Morrislaptop\Firestore;

use Kreait\Firebase\Auth;
use Kreait\Firebase\Storage;
use Kreait\Firebase\Database;
use Kreait\Firebase\Messaging;
use Kreait\Firebase\RemoteConfig;
use Kreait\Firebase as BaseFirebase;

class Firebase extends BaseFirebase
{
    /**
     * @var Firestore
     */
    private $firestore;

    public function __construct(Database $database, Firestore $firestore, Auth $auth, Storage $storage, RemoteConfig $remoteConfig, Messaging $messaging)
    {
        $this->database = $database;
        $this->firestore = $firestore;
        $this->auth = $auth;
        $this->storage = $storage;
        $this->remoteConfig = $remoteConfig;
        $this->messaging = $messaging;
    }

    public function getFirestore(): Firestore
    {
        return $this->firestore;
    }
}
