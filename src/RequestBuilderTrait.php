<?php

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
    protected array $bodyParams = [];

    /**
     * @var array
     */
    protected array $headers = [];

    /**
     * @param array $params
     * @return $this
     */
    public function setQueryParams(array $params): static
    {
        $this->queryParams = $params;

        return $this;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function setBodyParams(array $params): static
    {
        $this->bodyParams = $params;

        return $this;
    }

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

    /**
     * @return string
     */
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

    /**
     * @return string
     */
    protected function getQueryStr(): string
    {
        return $this->encodeParams($this->queryParams);
    }

    /**
     * @return string|StreamInterface|null
     */
    protected function getBody(): string|StreamInterface|null
    {
        $contentType = $this->headers['Content-Type'] ?? null;

        if ($contentType === 'multipart/form-data') {
            return new MultipartStream($this->bodyParams);
        } else if ($contentType === 'application/json') {
            return $this->jsonEncode($this->bodyParams);
        } else if (!$this->bodyParams) {
            return null;
        }

        return $this->encodeParams($this->bodyParams);
    }

    /**
     * @param array $data
     * @return string
     */
    protected function jsonEncode(array $data): string
    {
        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * @param array $params
     * @return string
     */
    protected function encodeParams(array $params): string
    {
        return http_build_query($params, '', '&');
    }

    /**
     * @return string
     */
    abstract protected function getGatewayUri(): string;

    /**
     * @return string
     */
    abstract protected function getApiPath(): string;
}