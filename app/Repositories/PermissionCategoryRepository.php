<?php

namespace App\Repositories;

use App\Models\PermissionCategory;
use Illuminate\Http\Request;

/**
 * Class IssueCategoryRepository.
 */
class PermissionCategoryRepository
{
    /**
     * For selecting the data
     *
     */
    public function getPermissionCategories()
    {
        return PermissionCategory::select('id', 'name')->get();
    }

    /**
     * For Create / Updating record into PermissionCategory table
     */
    public function saveData($condition = [], $fields)
    {

        $modelObj = PermissionCategory::updateOrCreate($condition, $fields);
        return $modelObj;
    }

    /**
     * For selecting the data
     *
     * @param array $condition
     * @throws InvalidOtpException
     */
    public function fetchAllData(array $condition = [], $limit = "")
    {
        $data = PermissionCategory::where($condition)
                ->orderBy('name');
        if($limit){
            $data->take($limit);
        }
        return $data->get();
    }


}


?>
