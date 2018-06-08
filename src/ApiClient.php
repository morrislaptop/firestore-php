<?php

namespace Morrislaptop\Firestore;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use Kreait\Firebase\Util\JSON;
use Psr\Http\Message\ResponseInterface;

class ApiClient
{
    /**
     * @var ClientInterface
     */
    protected $httpClient;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function get($uri)
    {
        $response = $this->request('GET', $uri);

        return JSON::decode((string) $response->getBody(), true);
    }

    public function patch($uri, $value)
    {
        $response = $this->request('PATCH', $uri, ['json' => $value]);

        return JSON::decode((string) $response->getBody(), true);
    }

    public function post($uri, $value)
    {
        $response = $this->request('POST', $uri, ['json' => $value]);

        return JSON::decode((string) $response->getBody(), true);
    }

    public function delete($uri)
    {
        $this->request('DELETE', $uri);
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
