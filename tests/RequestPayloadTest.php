<?php

namespace Sevaske\Discourse\Tests;

use PHPUnit\Framework\TestCase;
use Sevaske\Discourse\Exceptions\DiscourseException;
use Sevaske\Discourse\Services\Connect\RequestPayload;

class RequestPayloadTest extends TestCase
{
    public function test_it_decodes_valid_payload(): void
    {
        $params = [
            'nonce' => 'qwerty',
            'return_sso_url' => 'https://example.com/sso',
        ];

        $payload = base64_encode(http_build_query($params));

        $instance = new RequestPayload($payload);

        $this->assertSame($params, $instance->all());
        $this->assertSame($params['nonce'], $instance->nonce());
        $this->assertSame($params['return_sso_url'], $instance->returnUrl());
    }

    public function test_it_throws_exception_on_invalid_base64(): void
    {
        $this->expectException(DiscourseException::class);
        new RequestPayload('not-base64');
    }

    public function test_it_throws_exception_when_key_missing(): void
    {
        $payload = base64_encode('nonce=123');
        $instance = new RequestPayload($payload);

        $this->expectException(DiscourseException::class);
        $instance->get('missing_key');
    }

    public function test_it_returns_nonce_and_return_url_as_string(): void
    {
        $payload = base64_encode(http_build_query([
            'nonce' => 'qwerty',
            'return_sso_url' => 'https://example.com/sso',
        ]));

        $instance = new RequestPayload($payload);

        $this->assertSame('qwerty', $instance->nonce());
        $this->assertSame('https://example.com/sso', $instance->returnUrl());
    }
}
