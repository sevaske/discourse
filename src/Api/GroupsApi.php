<?php

namespace Sevaske\Discourse\Api;

use Sevaske\Discourse\Contracts\DiscourseResponseContract;

class GroupsApi extends ApiService
{
    public function list(): DiscourseResponseContract
    {
        return $this->request('GET', '/groups.json');
    }

    public function get($nameOrId, bool $byId = true): DiscourseResponseContract
    {
        if ($byId) {
            return $this->request('GET', "/groups/by-id/{$nameOrId}.json");
        }

        return $this->request('GET', "/groups/{$nameOrId}.json");
    }

    public function create(string $name, array $extra = []): DiscourseResponseContract
    {
        return $this->request('POST', '/admin/groups.json', [
            'group' => [
                'name' => $name,
                ...$extra,
            ],
        ]);
    }

    public function update(int $id, string $name, array $extra = []): DiscourseResponseContract
    {
        return $this->request('POST', "/groups/{$id}.json", [
            'group' => [
                'name' => $name,
                ...$extra,
            ],
        ]);
    }

    public function delete(int $id)
    {
        return $this->request('DELETE', "/admin/groups/{$id}.json");
    }

    public function getMembers(int $groupId): DiscourseResponseContract
    {
        return $this->request('GET', "/groups/{$groupId}/members.json");
    }

    public function addMembers(int $groupId, array $usernames): DiscourseResponseContract
    {
        return $this->request('PUT', "/groups/{$groupId}/members.json", [
            'usernames' => $usernames,
        ]);
    }

    public function removeMembers(int $groupId, array $usernames): DiscourseResponseContract
    {
        return $this->request('DELETE', "/groups/{$groupId}/members.json", [
            'usernames' => $usernames,
        ]);
    }
}
