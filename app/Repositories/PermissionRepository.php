<?php

namespace App\Repositories;

use App\Models\Permission;

/**
 * Class PermissionRepository.
 */
class PermissionRepository
{
    /**
     * For selecting the data
     *
     * @param array $condition
     * @throws InvalidOtpException
     */
    public function fetchAllData(array $condition = [], $limit = 8)
    {
        return Permission::where($condition)
                ->with('permission_category')
                ->orderBy('id')
                ->take($limit)
                ->get();
    }

    /**
     * For Create / Updating record into pages table
     */
    public function saveData($condition = [], $fields)
    {
        return Permission::updateOrCreate($condition, $fields);
    }
}

?>
