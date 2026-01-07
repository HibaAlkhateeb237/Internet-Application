<?php

namespace App\Http\Repositories;

use App\Models\GovernmentAgency;
use Illuminate\Support\Facades\Cache;

class GovernmentAgencyRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }


    public function getAll()
    {
        return Cache::remember('agencies_list', 60 * 60, function () {
            return GovernmentAgency::all();
        });
    }
}
