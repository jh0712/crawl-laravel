<?php

namespace Modules\Crawl\Contracts;

use App\Contracts\CrudContract;
use App\Contracts\SlugContract;

interface CrawlContract extends CrudContract, SlugContract
{
    public function getData($user_id = null);

    public function crawlData($array, $user_id = null);
}
