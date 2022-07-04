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
        return (!empty($condition)) ? Category::updateOrCreate($condition, $fields) : Category::updateOrCreate($fields);
    }

    /**
     * For selecting the data
     *
     * @param array $condition
     * @throws InvalidOtpException
     */
    public function fetchAllData(array $condition = [], $limit = "")
    {
        $data = Category::where($condition)
                ->orderBy('name');
        if($limit){
            $data->take($limit);
        }
        return $data->get();
    }
}


?>
