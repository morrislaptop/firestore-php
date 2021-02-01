# Firestore SDK for Laravel (without gRPC)

The library implements seamless with `kreait/laravel-firebase`, and also mocks some of their internal classes, so you
don't have to set up a client twice. To achieve this we're using reflection, so if you're shomehow uncomfortable with
this, then steer away.

## Installation

This package is installed via Composer.

    composer require tormjens/firestore

Due to Laravel's auto-discovery capabilities, the service provider is registerered automatically.

## Usage

The library aims to replicate the API signature
of [Google's PHP API](https://googlecloudplatform.github.io/google-cloud-php/#/docs/cloud-firestore/v0.11.0/firestore/readme)
.

### Sample usage:

```php
use TorMorten\Firestore\Firestore;
$firestore = resolve(Factory::class);

$collection = $firestore->collection('users');
$user = $collection->document('123456');

// Save a document
$user->set(['name' => 'tormjens', 'role' => 'developer']);

// Get a document
$snap = $user->snapshot();
echo $snap['name']; // tormjens
```

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
