<?php

namespace Sevaske\Discourse\Http;

use Psr\Http\Message\ResponseInterface;
use Sevaske\Discourse\Exceptions\DiscourseException;
use Sevaske\Discourse\Contracts\DiscourseResponseContract;
use Sevaske\Discourse\Traits\HasAttributes;

/**
 * Wrapper around PSR-7 response with convenient attribute access.
 */
class DiscourseResponse implements DiscourseResponseContract
{
    use HasAttributes;

    /**
     * @var ResponseInterface|array
     */
    protected $rawResponse;

    protected ?int $httpStatusCode = null;

    public function __construct($rawResponse, ?int $httpStatusCode = null)
    {
        $this->rawResponse = $rawResponse;
        $this->httpStatusCode = $httpStatusCode;

        if ($this->rawResponse instanceof ResponseInterface) {
            $this->attributes = self::parse($this->rawResponse);

            if ($this->httpStatusCode === null) {
                $this->httpStatusCode = $rawResponse->getStatusCode();
            }
        } else {
            $this->attributes = (array) $rawResponse;
        }
    }

    /**
     * @return array|ResponseInterface
     */
    public function raw()
    {
        return $this->rawResponse;
    }

    /**
     * Parses the PSR-7 response and returns an associative array of its JSON contents.
     *
     * @param  ResponseInterface  $response  The HTTP response to parse.
     * @return array The decoded JSON content as an array.
     *
     * @throws DiscourseException If JSON decoding fails.
     */
    public static function parse(ResponseInterface $response): array
    {
        $body = $response->getBody();

        if ($body->isSeekable()) {
            $body->rewind();
        }

        $content = $body->getContents();
        $parsed = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new DiscourseException(json_last_error_msg(), [
                'content' => $content,
                'status' => $response->getStatusCode(),
            ]);
        }

        return (array) $parsed;
    }

    public function getHttpStatusCode(): ?int
    {
        return $this->httpStatusCode;
    }
}
