<?php

declare(strict_types=1);

namespace Doubler\OpenApiSdk;

use Doubler\OpenApiSdk\Encoder\EncoderInterface;
use Doubler\OpenApiSdk\Encoder\Factory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ApiResult
{
    private bool $success = true;

    private ?RequestInterface $request;

    private ?ResponseInterface $response;

    private ?EncoderInterface $encoder;

    private string $message = '';

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @param $success
     * @return $this
     */
    public function setSuccess($success): static
    {
        $this->success = $success;

        return $this;
    }

    /**
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    /**
     * @param RequestInterface $request
     * @return $this
     */
    public function setRequest(RequestInterface $request): static
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * @param ResponseInterface $response
     * @return $this
     */
    public function setResponse(ResponseInterface $response): static
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getData(): ?array
    {
        if (null === $this->response) {
            return null;
        }

        $content = (string)$this->getResponse()->getBody();

        return $this->getEncoder()->decode($content);
    }

    public function getEncoder(): EncoderInterface
    {
        if (null === $this->encoder) {
            $contentType = $this->getResponse()->getHeader('content-type')[0] ?? 'application/json';

            $this->encoder = Factory::make($contentType);
        }

        return $this->encoder;
    }

    /**
     * @param EncoderInterface $encoder
     * @return $this
     */
    public function setEncoder(EncoderInterface $encoder): static
    {
        $this->encoder = $encoder;

        return $this;
    }
}