<?php

namespace UKFast\SDK\PHaaS;

use UKFast\SDK\Page;
use UKFast\SDK\Client as BaseClient;
use UKFast\SDK\PHaaS\Entities\Template;

class TemplateClient extends BaseClient
{
    protected $basePath = 'phaas/';

    /**
     * Get a paginated list of domains
     *
     * @param int $page
     * @param int $perPage
     * @param array $filters
     * @return Page
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getPage($page = 1, $perPage = 15, $filters = [])
    {
        $templates = $this->paginatedRequest('v1/templates', $page, $perPage, $filters);

        $templates->serializeWith(function ($item) {
            return new Template($item);
        });

        return $templates;
    }
}
