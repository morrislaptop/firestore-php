<?php

namespace TorMorten\Firestore;

use Illuminate\Support\ServiceProvider;
use Kreait\Laravel\Firebase\Facades\Firebase;
use ReflectionObject;

class FirestoreServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Firestore::class, function () {
            $castAway = $this->cast(Factory::class, Firebase::project()->factory());
            return $castAway->firestore();
        });
    }

    /**
     * Super-fancy method of casting an object in to a different class
     * @param $destination
     * @param $sourceObject
     * @return mixed
     */
    protected function cast($destination, $sourceObject)
    {
        if (is_string($destination)) {
            $destination = new $destination();
        }
        $sourceReflection = new ReflectionObject($sourceObject);
        $destinationReflection = new ReflectionObject($destination);
        $sourceProperties = $sourceReflection->getProperties();
        foreach ($sourceProperties as $sourceProperty) {
            $sourceProperty->setAccessible(true);
            $name = $sourceProperty->getName();
            $value = $sourceProperty->getValue($sourceObject);
            if ($destinationReflection->hasProperty($name)) {
                $propDest = $destinationReflection->getProperty($name);
                $propDest->setAccessible(true);
                $propDest->setValue($destination, $value);
            } else {
                $destination->$name = $value;
            }
        }
        return $destination;
    }
}
