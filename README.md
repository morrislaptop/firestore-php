# Firestore SDK for Laravel (without gRPC)

Leveraging the Google PHP API Client for communication.

## Installation

This package is installed via Composer.

    composer require tormjens/firestore

Due to Laravel's auto-discovery capabilities, the service provider is registerered automatically.

## Usage

This package aims to create a fluent experience, preserving the feel of the Laravel framework.

### Getting started

You first resolve Firestore out of the container.

```php 
use TorMorten\Firestore\Firestore;
$firestore = resolve(Firestore::class);
```

You can also resolve using dependency injection.

```php 
public function __construct(Factory $firestore)
{
    $this->firestore = $firestore;
}
```

You can now start grabbing stuff from Firestore. First you'll need to define the collection your looking into.

```php 
$collection = $firestore->collection('users');
```

You'll now have the collection at hand, and can either select all documents in that collection:

```php 
$documents = $collection->documents();
```

Or you can grab a single document:

```php 
$document = $collection->document('1234');
```

Be aware that the last one simply creates an instance of a document. If you want to fetch the document from firebase 
you'll have to add `->fetch()` to that call.

### Sample usage:

```php

$collection = $firestore->collection('users');
$user = $collection->document('123456');

// Fetches the document from Firebase
$user->fetch();

// Create/update a document
$user->update(['name' => 'tormjens', 'role' => 'developer']);

// Get a document
echo $user->name; // tormjens

// Delete a document
$user->delete();
```
