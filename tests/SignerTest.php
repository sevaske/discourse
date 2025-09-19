<?php

namespace Sevaske\Discourse\Tests;

use PHPUnit\Framework\TestCase;
use Sevaske\Discourse\Services\Signer;

class SignerTest extends TestCase
{
    private Signer $signer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->signer = new Signer('secret');
    }

    /**
     * It generates the expected HMAC-SHA256 signature for a payload.
     */
    public function test_generates_expected_signature(): void
    {
        $signature = $this->signer->sign('payload');

        $this->assertEquals('b82fcb791acec57859b989b430a826488ce2e479fdf92326bd0a2e8375a42ba4', $signature);
    }

    /**
     * It validates a payload when the correct signature is provided.
     */
    public function test_validates_correct_signature(): void
    {
        $valid = $this->signer->validate(
            'b82fcb791acec57859b989b430a826488ce2e479fdf92326bd0a2e8375a42ba4',
            'payload'
        );

        $this->assertTrue($valid);
    }

    /**
     * It fails validation when an incorrect signature is provided.
     */
    public function test_rejects_incorrect_signature(): void
    {
        $valid = $this->signer->validate(
            'invalid-signature',
            'payload'
        );

        $this->assertFalse($valid);
    }
}
