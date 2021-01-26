<?php

namespace TorMorten\Firestore;

use Illuminate\Support\ServiceProvider;

class FirestoreServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(\Kreait\Firebase\Firestore::class, function () {
            dd('hei');
        });
    }
}
