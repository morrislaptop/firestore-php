<?php

namespace TorMorten\Firestore;

use Illuminate\Support\ServiceProvider;
use Kreait\Laravel\Firebase\Facades\Firebase;
use ReflectionObject;

class FirestoreServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Project::class, function () {
            return $this->cast(Project::class, Firebase::project());
        });

        $this->app->singleton(Factory::class, function ($app) {
            return $this->cast(Factory::class, $app[Project::class]->factory());
        });

        $this->app->singleton(Firestore::class, function ($app) {
            $project = $app->make(Project::class);
            $project->setFactory($app->make(Factory::class));
            return $project->factory()->firestore();
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
