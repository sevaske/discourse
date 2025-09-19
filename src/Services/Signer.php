<?php

namespace Sevaske\Discourse\Services;

/**
 * Class responsible for signing and verifying payloads
 */
class Signer
{
    private string $secret;

    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    /**
     * Create HMAC-SHA256 signature for given payload
     */
    public function sign(string $payload): string
    {
        return hash_hmac('sha256', $payload, $this->secret);
    }

    /**
     * Validate payload against given signature
     */
    public function validate(string $signature, string $payload, bool $payloadDecoded = false): bool
    {
        if (! $payloadDecoded) {
            $payload = urldecode($payload);
        }

        return hash_equals($this->sign($payload), $signature);
    }
}