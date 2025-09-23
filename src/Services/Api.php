<?php

namespace Sevaske\Discourse\Services;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Sevaske\Discourse\Api\BadgesApi;
use Sevaske\Discourse\Api\CategoriesApi;
use Sevaske\Discourse\Api\GroupsApi;
use Sevaske\Discourse\Api\InvitesApi;
use Sevaske\Discourse\Api\NotificationsApi;
use Sevaske\Discourse\Traits\Macroable;
use Sevaske\Discourse\Api\ApiService;
use Sevaske\Discourse\Api\PostsApi;
use Sevaske\Discourse\Api\SiteApi;
use Sevaske\Discourse\Api\UsersApi;
use Sevaske\Discourse\Traits\Http;

class Api
{
    use Http;
    use Macroable;

    /** @var array<string, ApiService> */
    protected array $apiServices = [];

    public function __construct(
        ClientInterface $client,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory
    ) {
        $this->client = $client;
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
    }

    public function badges(): BadgesApi
    {
        return $this->resolveApiService(BadgesApi::class);
    }

    public function categories(): CategoriesApi
    {
        return $this->resolveApiService(CategoriesApi::class);
    }

    public function groups(): GroupsApi
    {
        return $this->resolveApiService(GroupsApi::class);
    }

    public function invites(): InvitesApi
    {
        return $this->resolveApiService(InvitesApi::class);
    }

    public function notifications(): NotificationsApi
    {
        return $this->resolveApiService(NotificationsApi::class);
    }

    public function posts(): PostsApi
    {
        return $this->resolveApiService(PostsApi::class);
    }

    public function site(): SiteApi
    {
        return $this->resolveApiService(SiteApi::class);
    }

    public function users(): UsersApi
    {
        return $this->resolveApiService(UsersApi::class);
    }

    protected function resolveApiService(string $class)
    {
        if (! isset($this->apiServices[$class])) {
            $this->apiServices[$class] = new $class($this->client, $this->requestFactory, $this->streamFactory);
        }

        return $this->apiServices[$class];
    }
}
