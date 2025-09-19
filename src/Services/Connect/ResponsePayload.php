<?php

namespace Sevaske\Discourse\Services\Connect;

use Sevaske\Discourse\Services\Signer;

/**
 * Class responsible for building sign-in response
 */
class ResponsePayload
{
    private Signer $signer;

    public function __construct(Signer $signer)
    {
        $this->signer = $signer;
    }

    /**
     * Create the full signed response for Discourse Connect
     */
    public function build(string $nonce, string $id, string $email, array $extra = []): string
    {
        $payload = $this->encodePayload($nonce, $id, $email, $extra);

        return http_build_query([
            'sso' => $payload,
            'sig' => $this->signer->sign($payload),
        ]);
    }

    /**
     * Encode user data into base64 payload
     */
    protected function encodePayload(string $nonce, string $id, string $email, array $extra = []): string
    {
        $data = array_merge([
            'nonce'       => $nonce,
            'external_id' => $id,
            'email'       => $email,
        ], $extra);

        return base64_encode(http_build_query($data));
    }
}