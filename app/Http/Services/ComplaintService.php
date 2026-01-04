<?php

namespace App\Http\Services;

use App\Http\Repositories\ComplaintRepository;
use App\Http\Repositories\GovernmentAgencyRepository;
use App\Models\Complaint;
use App\Models\ComplaintImage;
use App\Models\ComplaintStatusHistory;
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

            do {
                $ref = Str::upper(Str::random(10));
            } while ($this->complaintRepo->referenceExists($ref));


            $complaint = $this->complaintRepo->createComplaint([
                'user_id' => $user->id,
                'government_agency_id' => $request->government_agency_id,
                'reference_number' => $ref,
                'title' => $request->title,
                'description' => $request->description,
                'location' => $request->location,
            ]);


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

    //-----------------------------------------------------------



    public function getComplaintsByStatusForUser($user, string $status)
    {
        return $this->complaintRepo->getByStatusForUser($user->id, $status);
    }

    //----------------------------------------------------------------------




    public function updateComplaintByUser(
        Complaint $complaint,
        array $data,
        array $images = []
    ) {
        if (in_array($complaint->status, ['resolved', 'rejected'])) {
            throw new \Exception('لا يمكن تعديل شكوى منجزة أو مرفوضة');
        }

        DB::beginTransaction();

        try {
            foreach ($data as $field => $newValue) {

                if ($field === 'images') continue;
                $oldValue = $complaint->$field;

                if ($oldValue != $newValue) {
                    ComplaintStatusHistory::create([
                        'complaint_id' => $complaint->id,
                        'status'       => $complaint->status,
                        'action_type'  => 'user_update',
                        'old_value'    => $oldValue,
                        'new_value'    => $newValue,
                    ]);
                }
            }

            $complaint->update($data);

            if (!empty($images)) {
                foreach ($images as $image) {
                    $path = $image->store('complaint_images', 'public');

                    ComplaintImage::create([
                        'complaint_id' => $complaint->id,
                        'file_path'    => $path,
                    ]);

                    ComplaintStatusHistory::create([
                        'complaint_id' => $complaint->id,
                        'status'       => $complaint->status,
                        'action_type'  => 'image_added',
                        'new_value'    => $path,
                    ]);
                }
            }

            DB::commit();

            return $complaint->fresh();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    //----------------------------------------------------------------------------













}
