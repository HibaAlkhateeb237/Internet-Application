<?php

namespace App\Http\Repositories;

use App\Models\GovernmentAgency;

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
        return GovernmentAgency::all();
    }
}
