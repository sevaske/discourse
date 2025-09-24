<?php

namespace Sevaske\Discourse\Contracts;

interface SignerContract
{
    public function sign(string $payload): string;

    public function validate(string $signature, string $payload): bool;
}