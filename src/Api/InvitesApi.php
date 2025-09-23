<?php

namespace Sevaske\Discourse\Api;

use Sevaske\Discourse\Contracts\DiscourseResponseContract;

class InvitesApi extends ApiService
{
    public function create(
        string $email,
        $skipEmail = false,
        ?string $customMessage = null,
        ?int $maxRedemptionsAllowed = 1,
        ?int $topicId = null,
        ?string $groupIds = null,
        ?string $groupNames = null,
        ?string $expiresAt = null
    ): DiscourseResponseContract
    {
        return $this->request('POST', '/invites.json', [
            'email' => $email,
            'skip_email' => $skipEmail,
            'custom_message' => $customMessage,
            'max_redemptions_allowed' => $maxRedemptionsAllowed,
            'topic_id' => $topicId,
            'group_ids' => $groupIds,
            'group_names' => $groupNames,
            'expires_at' => $expiresAt
        ]);
    }

    public function basicInfo(): DiscourseResponseContract
    {
        return $this->request('GET', '/site/basic-info.json');
    }
}
