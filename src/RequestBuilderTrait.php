<?php
/**
 * This file is part of doubler.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 */

namespace Doubler\OpenApiSdk;

use GuzzleHttp\Psr7\Request;

trait RequestBuilderTrait
{
    protected string $method = 'GET';

    protected array $queryParams = [];

    protected array $bodyParams = [];

    protected array $headers = [];

    public function build(): Request
    {
        $this->beforeBuild();;

        return new Request($this->method, $this->getUri(), $this->headers, $this->getBody());
    }

    protected function beforeBuild()
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

    /**
     * @return string
     */
    public function getBody(): string
    {
        return '';
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function withHeader($name, $value): static
    {
        $this->headers[$name] = $value;

        return $this;
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function addQueryParam($name, $value): static
    {
        $this->queryParams[$name] = $value;

        return $this;
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function addBodyParam($name, $value): static
    {
        $this->bodyParams[$name] = $value;

        return $this;
    }

    /**
     * @return string
     */
    protected function getQueryStr(): string
    {
        $segments = [];

        foreach ($this->queryParams as $name => $value) {
            $segments[] = sprintf('%s=%s', $name, rawurlencode($value));
        }

        return join('&', $segments);
    }

    abstract protected function getGatewayUri();

    abstract protected function getApiPath();
}