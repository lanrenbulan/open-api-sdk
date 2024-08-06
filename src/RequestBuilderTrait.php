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

use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;

trait RequestBuilderTrait
{
    /**
     * @var string
     */
    protected string $method = 'GET';

    /**
     * @var array
     */
    protected array $queryParams = [];

    /**
     * @var array
     */
    protected array $pathParams = [];

    /**
     * @var array
     */
    protected array $formParams = [];

    /**
     * @var array
     */
    protected array $bodyParams = [];

    /**
     * @var array
     */
    protected array $multipart = [];

    /**
     * @var array
     */
    protected array $headers = [];

    public function getRequest(): RequestInterface
    {
        $this->beforeBuildRequest();;

        return new Request($this->method, $this->getUri(), $this->headers, $this->getBody());
    }

    /**
     * @return void
     */
    protected function beforeBuildRequest(): void
    {

    }

    protected function getUri(): string
    {
        $queryStr = $this->getQueryStr();

        return sprintf(
            '%s%s%s',
            $this->getGatewayUri(),
            $this->getApiPath(),
            $queryStr ? '?' . $queryStr : ''
        );
    }

    protected function getQueryStr(): string
    {
        return $this->encodeParams($this->queryParams);
    }

    /**
     * @return string|StreamInterface|null
     */
    protected function getBody(): string|StreamInterface|null
    {
        if ($this->formParams) {
            if (!isset($this->headers['Content-Type'])) {
                $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
            }

            return $this->encodeParams($this->formParams);
        } else if ($this->multipart) {
            return new MultipartStream($this->multipart);
        } else if (!$this->bodyParams) {
            return null;
        }

        if ('application/json' === $this->headers['Content-Type']) {
            return $this->jsonEncode($this->bodyParams);
        }

        if (!isset($this->headers['Content-Type'])) {
            $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        }

        return $this->encodeParams($this->bodyParams);
    }

    /**
     * @param array $data
     * @return string
     */
    protected function jsonEncode(array $data): string
    {
        return json_encode($this->bodyParams, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * @param array $params
     * @return string
     */
    protected function encodeParams(array $params): string
    {
        return http_build_query($params, '', '&');
    }

    abstract protected function getGatewayUri();

    abstract protected function getApiPath();
}