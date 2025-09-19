<?php

namespace Sevaske\Discourse\Tests;

use PHPUnit\Framework\TestCase;
use Sevaske\Discourse\Services\Connect\ResponsePayload;
use Sevaske\Discourse\Services\Signer;

class ResponsePayloadTest extends TestCase
{
    private Signer $signer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->signer = new Signer('secret');
    }

    public function test_build_creates_valid_signed_payload(): void
    {
        $builder = new ResponsePayload($this->signer);

        $result = $builder->build('nonce123', 'user-1', 'user@example.com');

        parse_str($result, $query);

        $this->assertArrayHasKey('sso', $query);
        $this->assertArrayHasKey('sig', $query);

        $decoded = base64_decode($query['sso']);
        parse_str($decoded, $payload);

        $this->assertEquals('nonce123', $payload['nonce']);
        $this->assertEquals('user-1', $payload['external_id']);
        $this->assertEquals('user@example.com', $payload['email']);

        $this->assertTrue(
            $this->signer->validate($query['sig'], $query['sso']),
            'Signature should be valid for the payload'
        );
    }

    public function test_build_with_extra_parameters(): void
    {
        $builder = new ResponsePayload($this->signer);

        $result = $builder->build('nonce456', 'user-2', 'test@example.com', [
            'username' => 'testuser',
            'name' => 'Test User',
        ]);

        parse_str($result, $query);

        $decoded = base64_decode($query['sso']);
        parse_str($decoded, $payload);

        $this->assertEquals('testuser', $payload['username']);
        $this->assertEquals('Test User', $payload['name']);
    }

    public function test_signature_changes_with_different_secret(): void
    {
        $builder1 = new ResponsePayload(new Signer('secret-key-1'));
        $builder2 = new ResponsePayload(new Signer('secret-key-2'));

        $result1 = $builder1->build('nonce', 'id', 'email@example.com');
        $result2 = $builder2->build('nonce', 'id', 'email@example.com');

        parse_str($result1, $query1);
        parse_str($result2, $query2);

        $this->assertNotEquals($query1['sig'], $query2['sig'], 'Signatures should differ with different secrets');
    }
}
