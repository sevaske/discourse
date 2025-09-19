<?php

namespace Sevaske\Discourse\Contracts;

interface DiscourseExceptionContract
{
    public function withContext(array $context);

    public function context(): array;
}