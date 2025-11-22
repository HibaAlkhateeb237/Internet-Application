<?php

namespace App\Http\Repositories;
use App\Models\Complaint;
use App\Models\ComplaintImage;

class ComplaintRepository
{

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }


    public function referenceExists($ref)
    {
        return Complaint::where('reference_number', $ref)->exists();
    }

    public function createComplaint($data)
    {
        return Complaint::create($data);
    }

    public function saveImages($complaintId, $images)
    {
        foreach ($images as $image) {
            $path = $image->store('complaint_images', 'public');

            ComplaintImage::create([
                'complaint_id' => $complaintId,
                'file_path' => $path,
            ]);
        }
    }
}
