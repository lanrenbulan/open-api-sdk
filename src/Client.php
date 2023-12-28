<?php
/**
 * This file is part of doubler.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 */

declare(strict_types=1);

namespace Doubler\OpenApiSdk;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Promise;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class Client
{
    /**
     * @var HttpClient
     */
    private HttpClient $http;

    public function __construct(array $httpConfig = [])
    {
        $httpConfig = array_merge([
            'http_errors' => false,
        ], $httpConfig);

        $this->http = new HttpClient($httpConfig);
    }

    /**
     * 发送请求
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws ApiException
     */
    public function send(RequestInterface $request): ResponseInterface
    {
        try {
            return $this->http->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            throw new ApiException($e->getMessage(), $e->getCode(), $e);
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
            throw new ApiException($e->getMessage(), $e->getCode(), $e);
        }
    }
}