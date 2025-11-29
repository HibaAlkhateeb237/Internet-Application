<?php

namespace App\Http\Repositories;

use App\Models\Complaint;

class ComplaintAgencyRepository
{
    public function getByAgency($agencyId)
    {
        return Complaint::where('government_agency_id', $agencyId)
            ->with(['images', 'history.admin'])
            ->latest()
            ->paginate(20);
    }

    public function lockForUpdate($id)
    {
        return Complaint::where('id', $id)->lockForUpdate()->firstOrFail();
    }

    public function findByAgency($id, $agencyId)
    {
        return Complaint::where('government_agency_id', $agencyId)->findOrFail($id);
    }

    public function find($id)
    {
        return Complaint::findOrFail($id);
    }

    public function update($complaint, array $data)
    {
        return $complaint->update($data);
    }
}
