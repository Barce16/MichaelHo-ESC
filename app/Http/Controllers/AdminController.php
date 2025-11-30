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
            'name'      => [
                'required',
                'string',
                'min:2',
                'max:255',
                'regex:/^[a-zA-Z\s\-\.]+$/',
            ],
            'username'  => [
                'required',
                'string',
                'min:3',
                'max:50',
                'unique:users,username',
                'regex:/^[a-zA-Z0-9_\-]+$/',
                'not_regex:/^[0-9_\-]+$/',
            ],
            'email'     => ['required', 'email', 'max:255', 'unique:users,email'],
            'user_type' => ['required', Rule::in(['admin'])],
            'password'  => ['required', 'string', 'min:8'],
            'status'    => ['nullable', 'in:active,inactive'],
            'avatar'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            // Name validation messages
            'name.required' => 'Full name is required.',
            'name.regex' => 'Name must contain only letters, spaces, hyphens, and periods.',
            'name.min' => 'Name must be at least 2 characters.',

            // Username validation messages
            'username.required' => 'Username is required.',
            'username.unique' => 'This username is already taken. Please choose another.',
            'username.regex' => 'Username can only contain letters, numbers, underscores, and hyphens.',
            'username.not_regex' => 'Username must contain at least one letter.',
            'username.min' => 'Username must be at least 3 characters.',

            // Email validation messages
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email is already registered. Please use another.',

            // Password validation messages
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
        ]);

        $photoPath = null;
        if ($request->hasFile('avatar')) {
            $photoPath = $this->storeProfilePhoto($request->file('avatar'));
        }

        try {
            $user = User::create([
                'name'               => $data['name'],
                'username'           => $data['username'],
                'email'              => $data['email'],
                'user_type'          => $data['user_type'],
                'password'           => bcrypt($data['password']),
                'status'             => $request->has('status') ? 'active' : 'inactive',
                'profile_photo_path' => $photoPath,
            ]);

            return redirect()->route('admin.users.list')
                ->with('success', 'Administrator created successfully.');
        } catch (\Exception $e) {
            \Log::error('User creation failed: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create user. Please try again.');
        }
    }

    public function listUsers(Request $request)
    {
        $query = User::query();

        // Search filter (name or email)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // User type filter
        if ($request->filled('type')) {
            $query->where('user_type', $request->type);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Get users with pagination
        $users = $query->select('id', 'name', 'email', 'user_type', 'created_at', 'status')
            ->latest()
            ->paginate(5)
            ->withQueryString(); // Preserve query parameters in pagination links

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
