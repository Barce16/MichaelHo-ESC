<?php

use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\CustomerFeedbackController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PublicBookingController;
use App\Http\Controllers\EventShowcaseController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\PayrollController;
use App\Http\Controllers\Customer\BillingPageController;
use App\Http\Controllers\Customer\PaymentController;

use App\Http\Controllers\Customer\EventController as CustomerEventController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\CustomerPaymentController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\Admin\EventShowcaseController as AdminEventShowcaseController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\InclusionController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Staff\ScheduleController as StaffScheduleController;
use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\EnsureCustomer;
use App\Http\Middleware\EnsureStaff;
use Illuminate\Support\Facades\Route;
use App\Models\Package;
use App\Models\Feedback;
use App\Models\EventShowcase;




Route::get('/', function () {
    // Get event showcases for display
    $eventShowcases = EventShowcase::latest()->take(6)->get();

    // Get package categories for navigation
    $categories = Package::select('type')
        ->distinct()
        ->pluck('type')
        ->filter()
        ->sort()
        ->values();

    // Get featured packages for carousel
    $packages = Package::with('images')
        ->latest()
        ->take(6)  // or however many you want to show
        ->get();

    // Get published feedback for reviews section
    $publishedFeedback = Feedback::with(['customer', 'event'])
        ->where('is_published', true)
        ->latest()
        ->take(6)
        ->get();

    return view('welcome', compact('eventShowcases', 'categories', 'packages', 'publishedFeedback'));
});


// Services/Packages Routes (Public)
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/category/{category}', [ServiceController::class, 'category'])->name('services.category');
Route::get('/services/{package}', [ServiceController::class, 'show'])->name('services.show');

// Event Showcase Routes
Route::get('/events-showcase', [EventShowcaseController::class, 'index'])->name('events-showcase.index');
Route::get('/events-showcase/{eventShowcase}', [EventShowcaseController::class, 'show'])->name('events-showcase.show');

Route::get('/booking-success', function () {

    if (!session()->has('success')) {
        return redirect()->route('welcome');
    }
    return view('booking.success');
})->name('booking.success');


Route::post('/book/{package}/form', [PublicBookingController::class, 'showBookingForm'])
    ->name('book.form');

Route::post('/book/{package}', [PublicBookingController::class, 'store'])
    ->name('book.store');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/api/availability', [AvailabilityController::class, 'getMonthAvailability'])
    ->name('api.availability');

Route::get('/inclusions/by-package-type', [InclusionController::class, 'getByPackageType'])
    ->name('inclusions.by-package-type');


Route::get('/test-sms', function () {
    try {
        // Check config
        $apiToken = config('services.iprogtech.api_token');

        if (empty($apiToken)) {
            return response()->json([
                'error' => 'iprogtech API token not configured',
                'instructions' => [
                    '1. Get API token from iprogtech',
                    '2. Add to .env: IPROGTECH_API_TOKEN=your_token',
                    '3. Clear cache: php artisan config:clear'
                ]
            ]);
        }

        $smsNotifier = app(\App\Services\SmsNotifier::class);

        // Send to a DIFFERENT phone number (not your own!)
        $result = $smsNotifier->sendSms(
            '09152796976', // ← Change this to recipient's number
            'Message from iprogtech at ' . now()->format('h:i A')
        );

        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'SMS sent successfully! Check recipient phone.',
                'provider' => 'iprogtech'
            ]);
        }

        // Check logs for errors
        $logFile = storage_path('logs/laravel.log');
        $logs = '';
        if (file_exists($logFile)) {
            $lines = file($logFile);
            $recentLines = array_slice($lines, -20);
            foreach ($recentLines as $line) {
                if (stripos($line, 'iprogtech') !== false || stripos($line, 'sms') !== false) {
                    $logs .= $line . "\n";
                }
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to send SMS - check logs',
            'recent_logs' => $logs
        ], 500);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
});

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // Notifications
    Route::middleware(['auth'])->group(function () {
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
        Route::get('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    });


    // ========== CUSTOMER AREA ==========
    Route::middleware(['auth', EnsureCustomer::class])
        ->prefix('customer')
        ->name('customer.')
        ->group(function () {
            // Events
            Route::resource('events', CustomerEventController::class);

            // Payment routes
            // Generic create route (auto-detects payment type)
            Route::get('/events/{event}/payments/create', [PaymentController::class, 'create'])
                ->name('payments.create');

            // Specific payment type routes
            Route::get('/events/{event}/pay-intro', [PaymentController::class, 'createIntro'])
                ->name('payments.createIntro');
            Route::get('/events/{event}/pay-downpayment', [PaymentController::class, 'createDownpayment'])
                ->name('payments.createDownpayment');

            // Store payment
            Route::post('/events/{event}/payments', [PaymentController::class, 'store'])
                ->name('payments.store');

            // Payment history
            Route::get('/payments', [PaymentController::class, 'index'])
                ->name('payments.index');
            Route::get('/payments/{payment}', [PaymentController::class, 'show'])
                ->name('payments.show');

            // Billings
            Route::get('/billings', [BillingPageController::class, 'index'])
                ->name('billings');

            // Customer Feedback Routes
            Route::get('/events/{event}/feedback/create', [CustomerFeedbackController::class, 'create'])->name('feedback.create');
            Route::post('/events/{event}/feedback', [CustomerFeedbackController::class, 'store'])->name('feedback.store');
            Route::get('/events/{event}/feedback/edit', [CustomerFeedbackController::class, 'edit'])->name('feedback.edit');
            Route::put('/events/{event}/feedback', [CustomerFeedbackController::class, 'update'])->name('feedback.update');
        });

    // ========== STAFF AREA ==========

    Route::middleware(['auth', EnsureStaff::class])
        ->prefix('staff')
        ->name('staff.')
        ->group(function () {
            Route::get('/schedules', [StaffScheduleController::class, 'index'])->name('schedules.index');
            Route::get('/schedules/{event}', [StaffScheduleController::class, 'show'])->name('schedules.show');
            Route::get('/earnings', [StaffScheduleController::class, 'earnings'])->name('earnings');
        });

    // ========== ADMIN AREA ==========
    Route::prefix('admin')
        ->middleware(CheckAdmin::class)
        ->name('admin.')
        ->group(function () {

            // Users (admin/staff management)
            Route::get('/create-user', [AdminController::class, 'createUserForm'])->name('create-user');
            Route::post('/create-user', [AdminController::class, 'createUser'])->name('create-user.store');
            Route::get('/users', [AdminController::class, 'listUsers'])->name('users.list');
            Route::patch('/users/{user}/block', [AdminController::class, 'block'])->name('users.block');
            Route::patch('/users/{user}/unblock', [AdminController::class, 'unblock'])->name('users.unblock');

            // Events
            Route::resource('events', AdminEventController::class)->only(['index', 'show', 'update', 'destroy']);

            Route::get('events/{event}/inclusions/edit', [AdminEventController::class, 'editInclusions'])->name('events.editInclusions');
            Route::put('events/{event}/inclusions', [AdminEventController::class, 'updateInclusions'])->name('events.updateInclusions');

            Route::post('/events/{event}/complete-meeting', [AdminEventController::class, 'completeMeeting'])
                ->name('events.completeMeeting');

            // Event approval flow
            Route::post('events/{event}/approve', [AdminEventController::class, 'approve'])
                ->name('events.approve');
            Route::post('events/{event}/reject', [AdminEventController::class, 'reject'])
                ->name('events.reject');

            // Introductory payment verification
            Route::post('events/{event}/approve-intro-payment', [AdminEventController::class, 'approveIntroPayment'])
                ->name('events.approveIntroPayment');
            Route::post('events/{event}/reject-intro-payment', [AdminEventController::class, 'rejectIntroPayment'])
                ->name('events.rejectIntroPayment');

            // Downpayment flow
            Route::post('events/{event}/request-downpayment', [AdminEventController::class, 'requestDownpayment'])
                ->name('events.requestDownpayment');
            Route::post('events/{event}/approve-downpayment', [AdminEventController::class, 'approveDownpayment'])
                ->name('events.approveDownpayment');
            Route::post('events/{event}/reject-downpayment', [AdminEventController::class, 'rejectDownpayment'])
                ->name('events.rejectDownpayment');

            Route::resource('customers', CustomerController::class);
            // Staff assignment
            Route::resource('staff', StaffController::class);
            Route::get('events/{event}/assign-staff', [AdminEventController::class, 'assignStaffPage'])
                ->name('events.assignStaffPage');
            Route::post('events/{event}/assign-staff', [AdminEventController::class, 'assignStaff'])
                ->name('events.assignStaff');
            Route::put('events/{event}/staff', [AdminEventController::class, 'updateStaff'])
                ->name('events.staff.update');

            // Event guests & staffs view
            Route::get('/event/{event}/guests', [AdminEventController::class, 'guests'])
                ->name('event.guests');
            Route::get('/event/{event}/staffs', [AdminEventController::class, 'staffs'])
                ->name('event.staffs');

            // Generic status update (for manual overrides if needed)
            Route::patch('events/{event}/status', [AdminEventController::class, 'updateStatus'])
                ->name('events.status');

            // Payment list (all payments from all events)
            Route::get('payments', [CustomerPaymentController::class, 'index'])->name('payments.index');
            Route::get('payments/{payment}', [CustomerPaymentController::class, 'show'])->name('payments.show');
            Route::post('payments/{paymentId}/approve', [CustomerPaymentController::class, 'approve'])->name('payments.approve');
            Route::post('payments/{paymentId}/reject', [CustomerPaymentController::class, 'reject'])->name('payments.reject');

            // ---- Management ----
            Route::prefix('management')->name('management.')->group(function () {

                Route::get('/', [AdminController::class, 'managementIndex'])->name('index');

                Route::resource('packages', controller: PackageController::class)
                    ->names('packages');

                Route::patch('packages/{package}/toggle', [PackageController::class, 'toggle'])
                    ->name('packages.toggle');

                Route::resource('inclusions', InclusionController::class)
                    ->names('inclusions');

                Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
                Route::post('/feedback/{feedback}/publish', [FeedbackController::class, 'publish'])->name('feedback.publish');
                Route::post('/feedback/{feedback}/unpublish', [FeedbackController::class, 'unpublish'])->name('feedback.unpublish');
                Route::delete('/feedback/{feedback}', [FeedbackController::class, 'destroy'])->name('feedback.destroy');

                Route::get('/showcases', [AdminEventShowcaseController::class, 'index'])->name('showcases.index');
                Route::get('/showcases/create', [AdminEventShowcaseController::class, 'create'])->name('showcases.create');
                Route::post('/showcases', [AdminEventShowcaseController::class, 'store'])->name('showcases.store');
                Route::get('/showcases/{showcase}/edit', [AdminEventShowcaseController::class, 'edit'])->name('showcases.edit');
                Route::put('/showcases/{showcase}', [AdminEventShowcaseController::class, 'update'])->name('showcases.update');
                Route::delete('/showcases/{showcase}', [AdminEventShowcaseController::class, 'destroy'])->name('showcases.destroy');
                Route::post('/showcases/{showcase}/publish', [AdminEventShowcaseController::class, 'publish'])->name('showcases.publish');
                Route::post('/showcases/{showcase}/unpublish', [AdminEventShowcaseController::class, 'unpublish'])->name('showcases.unpublish');
            });

            // ---- Payroll ----
            Route::prefix('payroll')->name('payroll.')->group(function () {
                Route::get('/', [PayrollController::class, 'index'])->name('index');
                Route::get('/event/{event}', [PayrollController::class, 'viewStaffs'])->name('viewStaffs');
                Route::post('/event/{event}/staff/{staff}/mark-paid', [PayrollController::class, 'markAsPaid'])->name('markAsPaid');
                Route::post('/event/{event}/staff/{staff}/mark-pending', [PayrollController::class, 'markAsPending'])->name('markAsPending');
            });

            // ---- Report ----
            Route::prefix('reports')->name('reports.')->group(function () {
                Route::get('/', [ReportController::class, 'index'])->name('index');
                Route::get('/events', [ReportController::class, 'eventsReport'])->name('events');
                Route::get('/revenue', [ReportController::class, 'revenueReport'])->name('revenue');
                Route::get('/customers', [ReportController::class, 'customersReport'])->name('customers');
                Route::get('/customer-spending', [ReportController::class, 'customerSpending'])->name('customer-spending');
                Route::get('/package-usage', [ReportController::class, 'packageUsage'])->name('package-usage');
                Route::get('/payment-method', [ReportController::class, 'paymentMethod'])->name('payment-method');
                Route::get('/event-status', [ReportController::class, 'eventStatus'])->name('event-status');
                Route::get('/remaining-balances', [ReportController::class, 'remainingBalances'])->name('remaining-balances');
                Route::get('/system-summary', [ReportController::class, 'systemSummary'])->name('system-summary');
            });
        });


    // Route::get('/reports/monthly', fn() => view('reports.monthly'))->name('reports.monthly');
});

require __DIR__ . '/auth.php';
