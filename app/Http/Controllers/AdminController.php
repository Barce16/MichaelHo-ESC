<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Inclusion;
use App\Models\Feedback;
use Illuminate\Http\Request;
use App\Support\HandlesProfilePhotos;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{

    use HandlesProfilePhotos;

    public function createUserForm()
    {
        return view('admin.create-user');
    }

    public function createUser(Request $request)
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'username'  => ['required', 'string', 'max:50', 'unique:users,username'],
            'email'     => ['required', 'email', 'max:255', 'unique:users,email'],
            'user_type' => ['required', Rule::in(['admin'])],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
            'avatar'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $photoPath = $this->storeProfilePhoto($request->file('avatar'));

        $user = User::create([
            'name'      => $data['name'],
            'username'  => $data['username'],
            'email'     => $data['email'],
            'user_type' => $data['user_type'],
            'password'  => bcrypt($data['password']),
            'profile_photo_path' => $photoPath,
        ]);

        return redirect()->route('admin.users.list')
            ->with('success', 'User created successfully.');
    }

    public function listUsers()
    {
        $users = User::select('id', 'name', 'email', 'user_type', 'created_at', 'status')
            ->latest()->paginate(15);

        return view('admin.users', compact('users'));
    }

    public function block(User $user)
    {
        if ($user->user_type === 'admin') {
            return back()->with('error', 'You cannot block an admin.');
        }

        $user->update(['status' => 'blocked']);
        return back()->with('success', 'User has been blocked.');
    }

    public function unblock(User $user)
    {
        $user->update(['status' => 'active']);
        return back()->with('success', 'User has been unblocked.');
    }

    public function managementIndex()
    {
        $totalEvents = Event::count();
        $totalCustomers = Customer::count();

        // Package statistics
        $totalPackages = Package::count();
        $activePackages = Package::where('is_active', true)->count();

        // Inclusion statistics
        $totalInclusions = Inclusion::count();
        $availableInclusions = Inclusion::where('is_active', true)->count();

        // Recent packages (top 4 active ones)
        $recentPackages = Package::where('is_active', true)
            ->latest()
            ->take(4)
            ->get();

        // Popular inclusions (most used, top 6)
        $popularInclusions = Inclusion::withCount('events')
            ->where('is_active', true)
            ->orderByDesc('events_count')
            ->take(6)
            ->get();

        return view('admin.management.index', [
            'totalEvents' => Event::count(),
            'totalCustomers' => Customer::count(),
            'totalPackages' => Package::count(),
            'activePackages' => Package::where('is_active', true)->count(),
            'totalInclusions' => Inclusion::count(),
            'availableInclusions' => Inclusion::where('is_active', true)->count(),
            'recentPackages' => Package::where('is_active', true)->latest()->limit(4)->get(),
            'popularInclusions' => Inclusion::where('is_active', true)->latest()->limit(6)->get(),
            'totalFeedback' => Feedback::count(), // Add this
            'publishedFeedback' => Feedback::where('is_published', true)->count(), // Add this
        ]);
    }
}
