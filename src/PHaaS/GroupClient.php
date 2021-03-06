<?php

namespace UKFast\SDK\PHaaS;

use UKFast\SDK\Page;
use UKFast\SDK\Client as BaseClient;
use UKFast\SDK\PHaaS\Entities\Group;
use UKFast\SDK\PHaaS\Entities\User;

class GroupClient extends BaseClient
{
    protected $basePath = 'phaas/';

    /**
     * @param int $page
     * @param int $perPage
     * @param array $filters
     * @return Page
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getPage($page = 1, $perPage = 15, $filters = [])
    {
        $groups = $this->paginatedRequest('v1/groups', $page, $perPage, $filters);

        $groups->serializeWith(function ($item) {
            return new Group($item);
        });

        return $groups;
    }

    /**
     * Create a group
     *
     * @param $name
     * @param array $userIds
     * @return Group
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createGroup($name, $userIds = [])
    {
        $payload = json_encode([
            'name'     => $name,
            'user_ids' => $userIds
        ]);

        $response = $this->post(
            'v1/groups',
            $payload
        );

        $response = $this->decodeJson($response->getBody()->getContents());

        return new Group($response->data);
    }

    /**
     * Get a group by id
     *
     * @param $id
     * @return Group
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getById($id)
    {
        $request = $this->get('v1/groups/' . $id);

        $response = $this->decodeJson($request->getBody()->getContents());

        foreach ($response->data->users as &$user) {
            $user = new User($user);
        }

        return new Group($response->data);
    }

    /**
     * Update a group
     *
     * @param $id
     * @param $name
     * @param $userIds
     * @return Group
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function updateGroup($id, $name, $userIds)
    {
        $payload = json_encode([
            'id'       => $id,
            'name'     => $name,
            'user_ids' => $userIds
        ]);

        $response = $this->patch(
            'v1/groups/' . $id,
            $payload
        );

        $response = $this->decodeJson($response->getBody()->getContents());

        return new Group($response->data);
    }

    /**
     * Remove a group by id
     *
     * @param $id
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function removeGroup($id)
    {
        $response = $this->delete('v1/groups/' . $id);

        return $response;
    }
}
