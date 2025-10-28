<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Models\Staff;
use App\Models\User;
use App\Support\HandlesProfilePhotos;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;



class StaffController extends Controller
{

    use HandlesProfilePhotos;

    public function index()
    {
        $staffs = Staff::with('user')->latest()->paginate(15);
        return view('staff.index', compact('staffs'));
    }

    public function create()
    {
        return view('staff.create');
    }

    public function store(StoreStaffRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $request) {

            $photoPath = $this->storeProfilePhoto($request->file('avatar'));

            $user = User::create([
                'name'      => $validated['name'],
                'username'  => $validated['username'],
                'email'     => $validated['email'],
                'user_type' => 'staff',
                'status'    => 'active',
                'password'  => Hash::make($validated['password']),
                'profile_photo_path' => $photoPath,
            ]);

            Staff::create([
                'user_id'        => $user->id,
                'contact_number' => $validated['contact_number'],
                'role_type'      => $validated['role_type'],
                'rate' => $validated['rate'] ?? null,
                'rate_type' => $validated['rate_type'] ?? 'per_event',
                'address'        => $validated['address'],
                'gender'         => $validated['gender'],
                'remarks'        => $validated['remarks'],
                'is_active'      => (bool) $validated['is_active'],
            ]);
        });

        return redirect()->route('admin.staff.index')->with('success', 'Staff created.');
    }


    public function show(Staff $staff)
    {
        $assignedEvents = $staff->events()->with(['customer'])->orderByDesc('event_date')->paginate(10);
        return view('staff.show', compact('staff', 'assignedEvents'));
    }

    public function edit(Staff $staff)
    {
        $staff->load('user');
        return view('staff.edit', compact('staff'));
    }

    public function update(UpdateStaffRequest $request, Staff $staff)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $request, $staff) {

            $newPath = $this->storeProfilePhoto($request->file('avatar'), $staff->user->profile_photo_path);

            $staff->user->update([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'username' => $validated['username'],
                ...(!empty($validated['password']) ? ['password' => Hash::make($validated['password'])] : []),
                'profile_photo_path' => $newPath,
            ]);

            // Then update Staff profile
            $staff->update([
                'contact_number' => $validated['contact_number'] ?? null,
                'role_type'      => $validated['role_type'] ?? null,
                'rate' => $validated['rate'] ?? null,
                'rate_type' => $validated['rate_type'] ?? 'per_event',
                'address'        => $validated['address'] ?? null,
                'gender'         => $validated['gender'] ?? null,
                'remarks'        => $validated['remarks'] ?? null,
                'is_active'      => (bool) ($validated['is_active'] ?? $staff->is_active),
            ]);
        });

        return redirect()->route('admin.staff.show', $staff)->with('success', 'Staff updated.');
    }

    public function destroy(Staff $staff)
    {
        $staff->delete();
        return back()->with('success', 'Staff archived.');
    }
}
