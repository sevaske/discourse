<?php

namespace Sevaske\Discourse\Api;

use Sevaske\Discourse\Contracts\DiscourseResponseContract;

class NotificationsApi extends ApiService
{
    public function list(): DiscourseResponseContract
    {
        return $this->request('GET', '/notifications.json');
    }

    public function read(?int $id): DiscourseResponseContract
    {
        return $this->request('PUT', '/notifications/mark-read.json', [
            'id' => $id,
        ]);
    }
}
