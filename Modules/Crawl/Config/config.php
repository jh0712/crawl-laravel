<?php
use Modules\Crawl\Contracts\CrawlContract;
use Modules\Crawl\Repositories\CrawlRepository;

return [
    'name'     => 'Crawl',
    'bindings' => [
        CrawlContract::class => CrawlRepository::class,
    ],
];
