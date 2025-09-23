<?php

namespace Sevaske\Discourse\Api;

use Sevaske\Discourse\Contracts\DiscourseResponseContract;

class CategoriesApi extends ApiService
{
    public function list(?bool $includeSubcategories = null): DiscourseResponseContract
    {
        return $this->request('GET', '/categories.json', [
            'includeSubcategories' => $includeSubcategories
        ]);
    }

    public function get(int $id): DiscourseResponseContract
    {
        return $this->request('GET', "/c/{$id}/show.json");
    }

    public function update(int $id, string $name, array $extra = []): DiscourseResponseContract
    {
        return $this->request('PUT', "/categories/{$id}.json", [
            'name' => $name,
            ...$extra,
        ]);
    }

    public function create(string $name, array $extra = []): DiscourseResponseContract
    {
        return $this->request('POST', "/categories.json", [
            'name' => $name,
            ...$extra,
        ]);
    }
}
