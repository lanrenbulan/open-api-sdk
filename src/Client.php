<?php

declare(strict_types=1);

namespace Doubler\OpenApiSdk;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Promise;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class Client
{
    /**
     * @var HttpClient
     */
    private HttpClient $http;

    public function __construct(array $config = [])
    {
        $config = array_merge([
            'http_errors' => false,
            'connect_timeout' => 30,
            'timeout' => 180,
        ], $config);

        $this->http = new HttpClient($config);
    }

    /**
     * Send request
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws ApiException
     */
    public function send(RequestInterface $request): ResponseInterface
    {
        try {
            return $this->http->sendRequest($request);
        } catch (Throwable $e) {
            $exception = new ApiException($e->getMessage(), $e->getCode(), $e);
            $exception->setRequests([$request]);
            throw $exception;
        }
    }

    /**
     * 批量发送多个请求
     *
     * @param RequestInterface[] $requests
     * @return ResponseInterface[]
     * @throws ApiException
     */
    public function sendRequests(array $requests): array
    {
        try {
            $promises = [];
            foreach ($requests as $request) {
                $promises[] = $this->http->sendAsync($request);
            }

            return Promise\Utils::unwrap($promises);
        } catch (Throwable $e) {
            $exception = new ApiException($e->getMessage(), $e->getCode(), $e);
            $exception->setRequests($requests);
            throw $exception;
        }
    }
}