<?php

namespace Sevaske\Discourse\Api;

use Sevaske\Discourse\Contracts\DiscourseResponseContract;
use Sevaske\Discourse\Exceptions\DiscourseException;

class PostsApi extends ApiService
{
    public function latest(?int $before = null): DiscourseResponseContract
    {
        $params = $before === null ? [] : ['before' => $before];

        return $this->request('GET', '/posts.json', $params);
    }

    public function get($id): DiscourseResponseContract
    {
        return $this->request('GET', "/posts/{$id}.json");
    }

    public function update($id, string $raw, ?string $editReason = null): DiscourseResponseContract
    {
        return $this->request('PUT', "/posts/{$id}.json", [
            'post' => $this->filterData([
                'raw' => $raw,
                'edit_reason' => $editReason,
            ]),
        ]);
    }

    /**
     * @api https://docs.discourse.org/#tag/Posts/operation/createTopicPostPM
     *
     * @param array $data
     *
     * @return DiscourseResponseContract
     * @throws DiscourseException
     */
    public function create(array $data): DiscourseResponseContract
    {
        return $this->request('POST', '/posts.json', $data);
    }

    public function delete($id): DiscourseResponseContract
    {
        return $this->request('DELETE', "/posts/{$id}.json");
    }

    public function locked($id, bool $locked): DiscourseResponseContract
    {
        return $this->request('PUT', "/posts/{$id}/locked.json", [
            'locked' => $locked ? 'true' : 'false',
        ]);
    }

    public function lock($id): DiscourseResponseContract
    {
        return $this->locked($id, true);
    }

    public function unlock($id): DiscourseResponseContract
    {
        return $this->locked($id, false);
    }

    public function replies($id): DiscourseResponseContract
    {
        return $this->request('GET', "/posts/{$id}/replies.json");
    }

    public function action(int $postId, int $postActionTypeId, ?bool $flagTopic = null): DiscourseResponseContract
    {
        return $this->request('GET', '/post_actions.json', [
            'id' => $postId,
            'post_action_type_id' => $postActionTypeId,
            'flag_topic' => $flagTopic,
        ]);
    }
}
