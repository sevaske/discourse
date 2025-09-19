<?php

namespace Sevaske\Discourse\Tests;

use PHPUnit\Framework\TestCase;
use Sevaske\Discourse\Services\Signer;

class SignerTest extends TestCase
{
    /**
     * Ensure that the signer generates a correct HMAC-SHA256 signature
     * for a given payload and secret.
     */
    public function test_sign_generation(): void
    {
        $signature = $this->signer()->sign('payload');

        $this->assertEquals('b82fcb791acec57859b989b430a826488ce2e479fdf92326bd0a2e8375a42ba4', $signature);
    }

    /**
     * Ensure that validate() returns true when a correct signature
     * is provided for a given payload.
     */
    public function test_valid(): void
    {
        $valid = $this->signer()->validate(
            'b82fcb791acec57859b989b430a826488ce2e479fdf92326bd0a2e8375a42ba4',
            'payload'
        );

        $this->assertTrue($valid);
    }

    /**
     * Ensure that validate() returns false when an incorrect signature
     * is provided for a given payload.
     */
    public function test_invalid(): void
    {
        $valid = $this->signer()->validate(
            'invalid-signature',
            'payload'
        );

        $this->assertFalse($valid);
    }

    /**
     * Helper method to instantiate a Signer with the test secret.
     */
    private function signer(): Signer
    {
        return new Signer('secret');
    }
}
