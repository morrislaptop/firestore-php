<?php

namespace Morrislaptop\Firestore;

use GuzzleHttp\Psr7\Request;
use Kreait\Firebase\Util\JSON;
use Psr\Http\Message\ResponseInterface;
use Kreait\Firebase\Database\ApiClient as BaseApiClient;

class ApiClient extends BaseApiClient
{
    public function set($uri, $value)
    {
        $response = $this->request('PATCH', $uri, ['json' => $value]);

        return JSON::decode((string) $response->getBody(), true);
    }

    protected function request(string $method, $uri, array $options = []): ResponseInterface
    {
        $request = new Request($method, $uri);

        try {
            return $this->httpClient->send($request, $options);
        } catch (RequestException $e) {
            throw ApiException::wrapRequestException($e);
        } catch (\Throwable $e) {
            throw new ApiException($request, $e->getMessage(), $e->getCode(), $e);
        }
    }
}
