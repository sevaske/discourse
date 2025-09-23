<?php

namespace Sevaske\Discourse\Api;

use Sevaske\Discourse\Contracts\DiscourseResponseContract;

class UsersApi extends ApiService
{
    public function getByUsername(string $username): DiscourseResponseContract
    {
        return $this->request('GET', "/u/{$username}.json");
    }

    public function getByExternalId(string $externalId): DiscourseResponseContract
    {
        return $this->request('GET', "/u/by-external/{$externalId}.json");
    }

    public function getById(int $id): DiscourseResponseContract
    {
        return $this->request('GET', "/admin/users/{$id}.json");
    }

    public function create(string $name, string $email, string $password, string $username, array $extra = []): DiscourseResponseContract
    {
        return $this->request('POST', '/users.json', [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'username' => $username,
            ...$extra,
        ]);
    }

    public function update(string $username, string $name, array $extra): DiscourseResponseContract
    {
        return $this->request('PUT', '/u/'.$username.'.json', [
            'name' => $name,
            ...$extra,
        ]);
    }

    public function delete(
        int $id,
        ?bool $deletePosts = null,
        ?bool $blockEmail = null,
        ?bool $blockUrls = null,
        ?bool $blockIp = null
    ): DiscourseResponseContract
    {
        return $this->request('DELETE', "/admin/users/{$id}.json", [
            'delete_posts' => $deletePosts,
            'block_email' => $blockEmail,
            'block_urls' => $blockUrls,
            'block_ip' => $blockIp,
        ]);
    }

    public function activate(int $id): DiscourseResponseContract
    {
        return $this->request('PUT', "/admin/users/{$id}/activate.json");
    }

    public function deactivate(int $id): DiscourseResponseContract
    {
        return $this->request('PUT', "/admin/users/{$id}/deactivate.json");
    }

    public function logoutUser($id): DiscourseResponseContract
    {
        return $this->request('POST', "/admin/users/{$id}/log_out.json");
    }

    public function badges(string $username): DiscourseResponseContract
    {
        return $this->request('GET', "/user-badges/{$username}.json");
    }
}
