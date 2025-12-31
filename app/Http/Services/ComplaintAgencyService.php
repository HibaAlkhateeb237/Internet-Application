<?php

namespace App\Http\Services;

use App\Http\Repositories\ComplaintAgencyRepository;
use App\Models\ComplaintStatusHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ComplaintAgencyService
{
    protected $repo;

    public function __construct(ComplaintAgencyRepository $repo)
    {
        $this->repo = $repo;
    }

    public function listForAgency()
    {
        $agencyId = Auth::guard('admin')->user()->government_agency_id;
        return $this->repo->getByAgency($agencyId);
    }

    public function lock($id)
    {
        $admin = Auth::guard('admin')->user();

        return DB::transaction(function () use ($id, $admin) {

            $complaint = $this->repo->lockForUpdate($id);

            if ($complaint->locked_by_admin_id && $complaint->locked_by_admin_id != $admin->id) {
                if ($complaint->locked_at && now()->diffInMinutes($complaint->locked_at) < 1) {
                    return ['error' => true, 'message' => 'الشكوى مقفلة من موظف آخر', 'status' => 423];
                }
            }

            $this->repo->update($complaint, [
                'locked_by_admin_id' => $admin->id,
                'locked_at' => now(),
            ]);

            return ['success' => true];
        });
    }

    public function unlock($id)
    {
        $admin = Auth::guard('admin')->user();
        $complaint = $this->repo->find($id);

        if ($complaint->locked_by_admin_id != $admin->id) {
            return ['error' => true, 'message' => 'غير مسموح', 'status' => 403];
        }

        $this->repo->update($complaint, [
            'locked_by_admin_id' => null,
            'locked_at' => null,
        ]);

        return ['success' => true];
    }

    public function updateStatus($id, $status, $note)
    {
        $admin = Auth::guard('admin')->user();
        $complaint = $this->repo->findByAgency($id, $admin->government_agency_id);

        if ($complaint->locked_by_admin_id != $admin->id) {
            return ['error' => true, 'message' => 'لا يمكنك تعديل شكوى ليست محجوزة لك', 'status' => 423];
        }
//        if (!auth('admin')->user()->can('edit_complaints')) {
//            return response()->json(['error' => 'لا تملك صلاحية تعديل الشكوى'], 403);
//        }

        $this->repo->update($complaint, ['status' => $status]);

        ComplaintStatusHistory::create([
            'complaint_id' => $complaint->id,
            'admin_id' => $admin->id,
            'status' => $status,
            'note' => $note,
        ]);

        return ['success' => true];
    }

    public function addNote($id, $note)
    {
        $admin = Auth::guard('admin')->user();
        $complaint = $this->repo->findByAgency($id, $admin->government_agency_id);

        if ($complaint->locked_by_admin_id != $admin->id) {
            return ['error' => true, 'message' => 'لا يمكنك تعديل شكوى ليست محجوزة لك', 'status' => 423];
        }

        ComplaintStatusHistory::create([
            'complaint_id' => $complaint->id,
            'admin_id' => $admin->id,
            'status' => $complaint->status,
            'note' => $note,
        ]);

        return ['success' => true];
    }
}
