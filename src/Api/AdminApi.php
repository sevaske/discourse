<?php

namespace Sevaske\Discourse\Api;

use Sevaske\Discourse\Contracts\DiscourseResponseContract;

class AdminApi extends ApiService
{
    public function user(int $id): DiscourseResponseContract
    {
        return $this->request('GET', "/admin/users/{$id}.json");
    }

    public function deleteUser(
        int $id,
        ?bool $deletePosts = null,
        ?bool $blockEmail = null,
        ?bool $blockUrls = null,
        ?bool $blockIp = null
    ): DiscourseResponseContract {
        return $this->request('DELETE', "/admin/users/{$id}.json", [
            'delete_posts' => $deletePosts,
            'block_email' => $blockEmail,
            'block_urls' => $blockUrls,
            'block_ip' => $blockIp,
        ]);
    }

    public function activateUser(int $id): DiscourseResponseContract
    {
        return $this->request('PUT', "/admin/users/{$id}/activate.json");
    }

    public function deactivateUser(int $id): DiscourseResponseContract
    {
        return $this->request('PUT', "/admin/users/{$id}/deactivate.json");
    }

    public function logoutUser($id): DiscourseResponseContract
    {
        return $this->request('POST', "/admin/users/{$id}/log_out.json");
    }
}
