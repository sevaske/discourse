<?php

namespace Sevaske\Discourse\Traits;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Sevaske\Discourse\Contracts\DiscourseResponseContract;
use Sevaske\Discourse\Exceptions\DiscourseException;
use Sevaske\Discourse\Exceptions\BadApiRequestException;
use Sevaske\Discourse\Http\DiscourseResponse;

trait Http
{
    protected ClientInterface $client;
    protected RequestFactoryInterface $requestFactory;
    protected StreamFactoryInterface $streamFactory;

    public function getClient(): ClientInterface
    {
        return $this->client;
    }

    /**
     * @throws DiscourseException
     */
    public function request(string $method, string $uri, array $options = []): DiscourseResponseContract
    {
        $options = $this->prepareOptions($options);
        $request = $this->requestFactory->createRequest($method, $uri);

        if (isset($options['json'])) {
            $body = $this->streamFactory->createStream(json_encode($options['json']));
            $request = $request
                ->withBody($body)
                ->withHeader('Content-Type', 'application/json');
        }

        if (isset($options['query'])) {
            $queryString = http_build_query($options['query']);
            $uriWithQuery = $request->getUri()->withQuery($queryString);
            $request = $request->withUri($uriWithQuery);
        }

        try {
            $response = $this->getClient()->sendRequest($request);

            return $this->decodeResponse($response);
        } catch (\Throwable $e) {
            throw new BadApiRequestException($e->getMessage(), [], $e->getCode(), $e);
        }
    }

    protected function prepareOptions(array $options): array
    {
        if (isset($options['json'])) {
            $options['json'] = $this->filterData($options['json']);
        }

        if (isset($options['query'])) {
            $options['query'] = $this->filterData($options['query']);
        }

        return $options;
    }

    protected function filterData(array $data): array
    {
        return array_filter(
            $data,
            static fn($value) => $value !== null
        );
    }

    protected function decodeResponse(ResponseInterface $response): DiscourseResponseContract
    {
        return new DiscourseResponse($response);
    }
}
