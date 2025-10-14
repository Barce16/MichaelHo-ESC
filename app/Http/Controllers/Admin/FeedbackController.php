<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Display all feedback
     */
    public function index()
    {
        $feedbacks = Feedback::with(['event', 'customer'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $publishedCount = Feedback::where('is_published', true)->count();

        return view('admin.feedback.index', compact('feedbacks', 'publishedCount'));
    }

    /**
     * Publish feedback
     */
    public function publish(Feedback $feedback)
    {
        $feedback->publish();

        return back()->with('success', 'Feedback published successfully!');
    }

    /**
     * Unpublish feedback
     */
    public function unpublish(Feedback $feedback)
    {
        $feedback->unpublish();

        return back()->with('success', 'Feedback unpublished successfully!');
    }

    /**
     * Delete feedback
     */
    public function destroy(Feedback $feedback)
    {
        $feedback->delete();

        return back()->with('success', 'Feedback deleted successfully!');
    }
}
