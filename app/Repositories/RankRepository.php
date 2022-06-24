<?php

namespace App\Repositories;

use App\Models\Rank;

/**
 * Class RankRepository.
 */
class RankRepository
{
    /**
     * For selecting the data
     *
     * @param array $condition
     * @throws InvalidOtpException
     */
    public function fetchAllData(array $condition = [], $limit = 8)
    {
        return Rank::where($condition)
                ->orderBy('name')
                ->take($limit)
                ->get();
    }

    /**
     * For Create / Updating record into ranks table
     */
    public function saveData($condition = [], $fields)
    {
        if(!empty($condition)){
            return Rank::updateOrCreate($condition, $fields);
        }else{
            return Rank::create($fields);
        }
    }
}

?>
