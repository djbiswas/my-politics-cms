<?php

namespace App\Repositories;

use App\Models\Politician;

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

    /**
     * For selecting the data
     *
     * @param Request $request
     */
    public function getPoliticians($request)
    {
        return [
            'count' => $this->countPoliticans($request),
            'records' => $this->fetchPoliticans($request),
        ];
    }

    /**
     * For counting the Politicans records
     *
     * @param Request $request
     */
    public function fetchPoliticans($request)
    {
        if($request->cat) {
            echo '<pre>'; print_r('There'); die;
        } else {
            return Politician::select('id', 'name', 'title', 'name_alias', 'image')->onlyActive()->paginate(config('constants.recordsPerPage'));
        }
    }

    /**
     * For counting the Politicans records
     *
     * @param Request $request
     */
    public function countPoliticans($request)
    {
        if($request->cat) {
            echo '<pre>'; print_r('Here'); die;
        } else {
            return Politician::select('*')->onlyActive()->count();    
        }
    }
}

?>
