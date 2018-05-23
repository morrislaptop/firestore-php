<?php

namespace Morrislaptop\Firestore;

use Kreait\Firebase\Util\JSON;
use Psr\Http\Message\ResponseInterface;
use Kreait\Firebase\Exception\ApiException as BaseApiException;

class ApiException extends BaseApiException
{
    protected static function getPreciseMessage(ResponseInterface $response = null, string $default = ''): string
    {
        $message = $default;

        if ($response && JSON::isValid($responseBody = (string) $response->getBody())) {
            $message = JSON::decode($responseBody, true)['error'] ?? null;
        }

        if (is_array($message)) {
            $message = $message['message'];
        }

        return $message;
    }
}
