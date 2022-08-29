<?php

namespace Database\Seeders;

use App\Models\PermissionCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        PermissionCategory::truncate();

        $permissionCategoris =  [
            [
              'name' => 'Politician Category',
            ],
            [
              'name' => 'Politicians',
            ],
            [
              'name' => 'Issue Category',
            ],
            [
              'name' => 'Issues',
            ]
          ];

          PermissionCategory::insert($permissionCategoris);
    }
}
