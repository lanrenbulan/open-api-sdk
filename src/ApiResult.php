<?php

declare(strict_types=1);

namespace Doubler\OpenApiSdk;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class ApiResult
{
    private bool $success = true;

    private RequestInterface $request;

    private ?ResponseInterface $response;

    private ?array $data = null;

    private string $message = '';

    private ?Throwable $exception = null;

    public static function success(array $data = [], string $message = 'ok'): static
    {
        $result = new static();

        $result->setSuccess(true)
            ->setData($data)
            ->setMessage($message)
        ;

        return $result;
    }

    public static function fail(string $message, ?array $data = null): static
    {
        $result = new static();

        $result->setSuccess(false)
            ->setMessage($message);

        if (null !== $data) {
            $result->setData($data);
        }

        return $result;
    }

    public static function fromException(Throwable $e): static
    {
        $result = static::fail($e->getMessage());

        $result->setException($e);

        return $result;
    }

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
        return $this->data;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getException(): ?Throwable
    {
        return $this->exception;
    }

    /**
     * @param Throwable $exception
     * @return $this
     */
    public function setException(Throwable $exception): static
    {
        $this->exception = $exception;

        return $this;
    }
}