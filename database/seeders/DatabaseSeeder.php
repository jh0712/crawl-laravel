<?php

namespace Database\Seeders;

use App\Traits\HasSeeder;
use Illuminate\Database\Seeder;
use Modules\Document\Database\Seeders\CreateDocumentTypeSeedTableSeeder;

class DatabaseSeeder extends Seeder
{
    use HasSeeder;

    protected function getSeeders()
    {
        return [
            CreateDocumentTypeSeedTableSeeder::class,
            CreateAdminUser::class
        ];
    }
}
