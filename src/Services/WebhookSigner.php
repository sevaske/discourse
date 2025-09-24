<?php

namespace Sevaske\Discourse\Services;

use Sevaske\Discourse\Contracts\SignerContract;

class WebhookSigner implements SignerContract
{
    protected string $secret;

    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    /**
     * Generate a signature for a given payload.
     * Useful for testing or simulating Discourse requests.
     *
     * @param string $payload The payload to sign.
     *
     * @return string The signature in the format "sha256=..."
     */
    public function sign(string $payload): string
    {
        return 'sha256=' . hash_hmac('sha256', $payload, $this->secret);
    }

    /**
     * Validate the webhook signature against the payload.
     *
     * @param string $signature The "X-Discourse-Event-Signature" header value.
     * @param string $payload The raw JSON payload received from Discourse.
     *
     * @return bool True if the signature is valid, false otherwise.
     */
    public function validate(string $signature, string $payload): bool
    {
        // Discourse signatures are prefixed with "sha256="
        if (strpos($signature, 'sha256=') !== 0) {
            return false;
        }

        $expected = substr($signature, 7);
        $calculated = hash_hmac('sha256', $payload, $this->secret);

        return hash_equals($expected, $calculated);
    }
}