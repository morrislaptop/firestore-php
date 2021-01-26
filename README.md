# Firestore SDK for PHP (without gRPC)

## @todo

- [x] Get
- [x] Set
- [ ] Delete
- [ ] Add
- [ ] Transactions (beginTransaction, commit, rollback)
- [ ] Reference value support
- [ ] Batch Get
- [ ] List Documents
- [ ] Query
- [ ] Order
- [ ] Limit
- [ ] Indexes (create, delete, list, get)

## Installation

The recommended way to install is with Composer.

    composer require tormjens/firestore

## Usage

The library aims to replicate the API signature
of [Google's PHP API](https://googlecloudplatform.github.io/google-cloud-php/#/docs/cloud-firestore/v0.11.0/firestore/readme)
.

Sample usage:

```php

use TorMorten\Firestore\Factory;
use Kreait\Firebase\ServiceAccount;

// This assumes that you have placed the Firebase credentials in the same directory
// as this PHP file.
$serviceAccount = ServiceAccount::fromJsonFile(__DIR__ . '/google-service-account.json');

$firestore = (new Factory)
    ->withServiceAccount($serviceAccount)
    ->createFirestore();

$collection = $firestore->collection('users');
$user = $collection->document('123456');

// Save a document
$user->set(['name' => 'morrislaptop', 'role' => 'developer']);

// Get a document
$snap = $user->snapshot();
echo $snap['name']; // morrislaptop

```
