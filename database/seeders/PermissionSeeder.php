<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Permission::truncate();

        $permissions =  [
            [
              'permission_category_id' => '1',
              'name' => 'Categories',
              'slug' => 'categories',
              'route_name' => 'categories.index',
            ],
            [
              'permission_category_id' => '1',
              'name' => 'Category',
              'slug' => 'category',
              'route_name' => 'category.get',
            ],
            [
              'permission_category_id' => '2',
              'name' => 'Politicians',
              'slug' => 'politicians',
              'route_name' => 'politicians.index',
            ],
            [
              'permission_category_id' => '2',
              'name' => 'Politician',
              'slug' => 'politician',
              'route_name' => 'politician.get',
            ],
            [
              'permission_category_id' => '3',
              'name' => 'Issue Categories',
              'slug' => 'issue-categories',
              'route_name' => 'issue.categories',
            ],
            [
              'permission_category_id' => '3',
              'name' => 'Issue Category',
              'slug' => 'issue-category',
              'route_name' => 'issue.category.get',
            ],
            [
              'permission_category_id' => '4',
              'name' => 'Issues',
              'slug' => 'issues',
              'route_name' => 'issue.categories',
            ],
            [
              'permission_category_id' => '4',
              'name' => 'issue',
              'slug' => 'issue',
              'route_name' => 'issue.get',
            ]

          ];

          Permission::insert($permissions);
    }
}
