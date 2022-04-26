<?php

namespace TorMorten\Firestore\Support;

class ServiceAccount
{
    protected static $serviceAccount;

    public function get()
    {
        if(!static::$serviceAccount) {
            static::$serviceAccount = json_decode(file_get_contents(base_path(env('FIREBASE_CREDENTIALS'))));
        }

        return static::$serviceAccount;
    }

    public function getParentId()
    {
        return join('/', [
            'projects',
            $this->get()->project_id,
            'databases',
            '(default)',
            'documents'
        ]);
    }
}
