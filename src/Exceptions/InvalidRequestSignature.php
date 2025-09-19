<?php

namespace Sevaske\Discourse\Exceptions;

class InvalidRequestSignature extends DiscourseException
{
    public function __construct(string $message = 'Invalid request signature.', array $context = [], int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $context, $code, $previous);
    }
}