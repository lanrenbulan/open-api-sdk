<?php

declare(strict_types=1);

namespace Doubler\OpenApiSdk;

use Exception;
use Psr\Http\Message\RequestInterface;

class ApiException extends Exception
{
    /**
     * @var RequestInterface[]
     */
    private array $requests;

    public function getRequest(): RequestInterface
    {
        return $this->requests[0];
    }

    /**
     * @return RequestInterface[]
     */
    public function getRequests(): array
    {
        return $this->requests;
    }

    /**
     * @param RequestInterface[] $requests
     * @return $this
     */
    public function setRequests(array $requests): static
    {
        $this->requests = $requests;

        return $this;
    }

    /**
     * @param RequestInterface $request
     * @return $this
     */
    public function setRequest(RequestInterface $request): static
    {
        $this->requests[] = $request;

        return $this;
    }
}