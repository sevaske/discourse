<?php

namespace Sevaske\Discourse\Services\Connect;

use Sevaske\Discourse\Exceptions\DiscourseException;

/**
 * Class responsible for parsing incoming payload
 */
class RequestPayload
{
    private array $data;

    /**
     * @throws DiscourseException
     */
    public function __construct(string $payload)
    {
        $decoded = [];
        $rawPayload = base64_decode(urldecode($payload), true);

        if ($rawPayload === false) {
            throw new DiscourseException('Invalid base64 payload');
        }

        parse_str($rawPayload, $decoded);

        $this->data = $decoded;
    }

    public function all(): array
    {
        return $this->data;
    }

    /**
     * @throws DiscourseException
     */
    public function get(string $key)
    {
        $this->requireKey($key);

        return $this->data[$key];
    }

    /**
     * Get nonce value from payload
     *
     * @throws DiscourseException
     */
    public function getNonce(): string
    {
        return (string) $this->get('nonce');
    }

    /**
     * Get return SSO URL from payload
     *
     * @throws DiscourseException
     */
    public function getReturnUrl(): string
    {
        return (string) $this->get('return_sso_url');
    }

    /**
     * @throws DiscourseException
     */
    protected function requireKey(string $key): void
    {
        if (! array_key_exists($key, $this->data)) {
            throw new DiscourseException('The key "'.$key.'" not found in payload.');
        }
    }
}