<?php

namespace Modules\Document\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Document\Entities\DocumentType;

class CreateDocumentTypeSeedTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();

        try {
            DocumentType::firstOrCreate([
                'name'          => DocumentType::CREWLED_RESULT,
                'document_path' => 'upload/' . DocumentType::CREWLED_RESULT . '/'
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }
}
