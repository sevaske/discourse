<?php

namespace Sevaske\Discourse\Exceptions;

use Sevaske\Discourse\Contracts\DiscourseExceptionContract;

class DiscourseException extends \Exception implements DiscourseExceptionContract
{
    protected array $context = [];

    public function __construct(string $message = '', array $context = [], int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
    }

    public function withContext(array $context): self
    {
        $this->context = array_merge($this->context, $context);

        return $this;
    }

    public function context(): array
    {
        return $this->context;
    }
}