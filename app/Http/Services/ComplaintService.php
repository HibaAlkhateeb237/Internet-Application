<?php

namespace App\Http\Services;

use App\Http\Repositories\ComplaintRepository;
use App\Http\Repositories\GovernmentAgencyRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ComplaintService
{
    protected $complaintRepo;
    protected $agencyRepo;

    public function __construct(
        ComplaintRepository $complaintRepo,
        GovernmentAgencyRepository $agencyRepo
    ) {
        $this->complaintRepo = $complaintRepo;
        $this->agencyRepo = $agencyRepo;
    }

    public function listAgencies()
    {
        return $this->agencyRepo->getAll();
    }

    public function submitComplaint($user, $request)
    {
        DB::beginTransaction();

        try {
            // Generate unique reference number
            do {
                $ref = Str::upper(Str::random(10));
            } while ($this->complaintRepo->referenceExists($ref));

            // Save complaint
            $complaint = $this->complaintRepo->createComplaint([
                'user_id' => $user->id,
                'government_agency_id' => $request->government_agency_id,
                'reference_number' => $ref,
                'title' => $request->title,
                'description' => $request->description,
                'location' => $request->location,
            ]);

            // Save images
            if ($request->hasFile('images')) {
                $this->complaintRepo->saveImages($complaint->id, $request->file('images'));
            }

            DB::commit();
            return $complaint->load('images');

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
