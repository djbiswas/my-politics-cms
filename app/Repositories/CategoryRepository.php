<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Http\Request;

/**
 * Class CategoryRepository.
 */
class CategoryRepository
{
    /**
     * For selecting the data
     *
     * 
     */
    public function getPoliticianCategories()
    {
        return Category::select('id', 'name', 'icon')->get();
    }

    /**
     * For Create / Updating record into category table
     */
    public function saveData($condition = [], $fields)
    {
        return Category::updateOrCreate($condition, $fields);
    }
}


?>
