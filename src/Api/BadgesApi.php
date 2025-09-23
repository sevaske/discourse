<?php

namespace Sevaske\Discourse\Api;

use Sevaske\Discourse\Contracts\DiscourseResponseContract;

class BadgesApi extends ApiService
{
    public function list(): DiscourseResponseContract
    {
        return $this->request('GET', '/admin/badges.json');
    }

    public function create(string $name, int $badgeTypeId): DiscourseResponseContract
    {
        return $this->request('POST', '/admin/badges.json', [
            'name' => $name,
            'badge_type_id' => $badgeTypeId
        ]);
    }

    public function update(int $id, string $name, int $badgeTypeId): DiscourseResponseContract
    {
        return $this->request('PUT', "/admin/badges/{$id}.json", [
            'name' => $name,
            'badge_type_id' => $badgeTypeId
        ]);
    }

    public function delete(int $id): DiscourseResponseContract
    {
        return $this->request('DELETE', "/admin/badges/{$id}.json");
    }
}