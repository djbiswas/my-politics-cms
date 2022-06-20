<?php

namespace App\Repositories;

use App\Exceptions\InvalidCredentialsException;
use App\Models\Politician;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class PoliticianRepository.
 */
class PoliticianRepository
{
    /**
     * For selecting the data
     *
     * @param array $condition
     * @throws InvalidOtpException
     */
    public function fetchAllData(array $condition = [], $limit = 8)
    {
        return Politician::where($condition)
                ->orderBy('name')
                ->take($limit)
                ->get();
    }
}

?>
