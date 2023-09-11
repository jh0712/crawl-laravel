<?php
use Modules\Document\Repositories\DocumentRepository;
use Modules\Document\Contracts\DocumentContract;
return [
    'name' => 'Document',
    'bindings' => [
        DocumentContract::class => DocumentRepository::class,
    ],
];
