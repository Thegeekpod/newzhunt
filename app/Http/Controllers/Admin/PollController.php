<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poll;
use App\Models\PollOption;
use Illuminate\Http\Request;

class PollController extends Controller
{
    public function index()
    {
        $polls = Poll::with('options')->latest()->get();
        return view('admin.polls.index', compact('polls'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string|max:255',
        ]);

        // Create the poll
        $poll = Poll::create([
            'question' => $request->question,
            'is_active' => false,
            'total_votes' => 0
        ]);

        // Create the options
        foreach ($request->options as $optionText) {
            PollOption::create([
                'poll_id' => $poll->id,
                'option_text' => $optionText,
                'vote_count' => 0
            ]);
        }

        return redirect()->route('admin.polls.index')->with('success', 'Poll created successfully.');
    }

    public function update(Request $request, Poll $poll)
    {
        // Toggle is_active. If setting to true, deactivate all other polls
        $newActiveStatus = !$poll->is_active;
        
        if ($newActiveStatus) {
            Poll::where('id', '!=', $poll->id)->update(['is_active' => false]);
        }

        $poll->update([
            'is_active' => $newActiveStatus
        ]);

        return redirect()->route('admin.polls.index')->with('success', 'Poll status updated successfully.');
    }

    public function destroy(Poll $poll)
    {
        $poll->delete();
        return redirect()->route('admin.polls.index')->with('success', 'Poll deleted successfully.');
    }
}
