<?php

namespace Sevaske\Discourse\Api;

use Sevaske\Discourse\Contracts\DiscourseResponseContract;

class SiteApi extends ApiService
{
    public function info()
    {
        return $this->request('GET', '/site.json');
    }

    public function basicInfo(): DiscourseResponseContract
    {
        return $this->request('GET', '/site/basic-info.json');
    }
}
