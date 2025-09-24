<?php

namespace Sevaske\Discourse\Tests;

use PHPUnit\Framework\TestCase;
use Sevaske\Discourse\Services\WebhookSigner;

class WebhookSignerTest extends TestCase
{
    private WebhookSigner $signer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->signer = new WebhookSigner('secret');
    }

    /**
     * It generates the expected X-Discourse-Event-Signature header for a payload.
     */
    public function test_generates_expected_signature(): void
    {
        $payload = '{"foo":"bar"}';
        $expected = 'sha256=' . hash_hmac('sha256', $payload, 'secret');

        $signature = $this->signer->sign($payload);

        $this->assertEquals($expected, $signature);
    }

    /**
     * It validates a payload when the correct signature header is provided.
     */
    public function test_validates_correct_signature(): void
    {
        $payload = '{"foo":"bar"}';
        $signature = 'sha256=' . hash_hmac('sha256', $payload, 'secret');

        $valid = $this->signer->validate($signature, $payload);

        $this->assertTrue($valid);
    }

    /**
     * It fails validation when an incorrect signature is provided.
     */
    public function test_rejects_incorrect_signature(): void
    {
        $payload = '{"foo":"bar"}';
        $invalidSignature = 'sha256=invalid';

        $valid = $this->signer->validate($invalidSignature, $payload);

        $this->assertFalse($valid);
    }

    /**
     * It fails validation when the header does not start with sha256=.
     */
    public function test_rejects_signature_with_wrong_prefix(): void
    {
        $payload = '{"foo":"bar"}';
        $signature = hash_hmac('sha256', $payload, 'secret'); // missing sha256=

        $valid = $this->signer->validate($signature, $payload);

        $this->assertFalse($valid);
    }
}
