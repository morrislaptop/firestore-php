# Firestore SDK for Laravel (without gRPC)

The library implements seamless with `kreait/laravel-firebase`, and also mocks some of their internal classes, so you
don't have to set up a client twice. To achieve this we're using reflection, so if you're shomehow uncomfortable with
this, then steer away.

I started this package with [morrislaptop/firestore-php](https://github.com/morrislaptop/firestore-php) as a foundation,
and then grew it to become a plug-and-play integration with the `kreait` package.

This package is not mature for general usage just yet. Use at your own risk.

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
$firestore = resolve(Factory::class);
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

### Sample usage:

```php

$collection = $firestore->collection('users');
$user = $collection->document('123456');

// Save a document
$user->set(['name' => 'tormjens', 'role' => 'developer']);

// Get a document
echo $user->name; // tormjens

// Delete a document
$user->delete();
```
