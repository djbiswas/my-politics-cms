<?php

namespace App\Repositories;

use App\Models\Role;
/**
 * Class RankRepository.
 */
class RoleRepository
{
    /**
     * For selecting the data
     *
     * @param array $condition
     * @throws InvalidOtpException
     */
    public function fetchAllData(array $condition = [], $limit = "")
    {
        $data = Role::where($condition)
                ->orderBy('title');
        if($limit){
            $data->take($limit);
        }
        return $data->get();
    }

    /**
     * For Create / Updating record into ranks table
     */
    public function saveData($condition = [], $fields)
    {
        return Role::updateOrCreate($condition, $fields);
    }
}

?>
