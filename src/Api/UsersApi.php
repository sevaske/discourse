<?php

namespace Sevaske\Discourse\Api;

use Sevaske\Discourse\Contracts\DiscourseResponseContract;

class UsersApi extends ApiService
{
    public function list(
        ?string $flag = null, // "active" "new" "staff" "suspended" "blocked" "suspect"
        ?string $order = null,
        $asc = null,
        ?int $page = null,
        ?bool $showEmails = null,
        ?bool $stats = null,
        ?string $email = null,
        ?string $ip = null
    ): DiscourseResponseContract
    {
        if (is_bool($asc)) {
            $asc = $asc ? 'true' : 'false';
        }

        $uri = $flag ? "/admin/users/list/{$flag}.json" : '/admin/users.json';

        return $this->request('GET', $uri, [
            'order' => $order,
            'asc' => $asc,
            'page' => $page,
            'show_emails' => $showEmails,
            'stats' => $stats,
            'email' => $email,
            'ip' => $ip
        ]);
    }

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

    public function changePassword(string $token, string $username, string $password): DiscourseResponseContract
    {
        return $this->request('PUT', "/users/password-reset/{$token}.json", [
            'username' => $username,
            'password' => $password,
        ]);
    }

    public function sendPasswordResetEmail(string $login)
    {
        return $this->request('POST', '/session/forgot_password.json', [
            'login' => $login,
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
