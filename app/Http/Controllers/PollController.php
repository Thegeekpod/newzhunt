<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\PollOption;
use Illuminate\Http\Request;
use App\Helpers\BengaliHelper;

class PollController extends Controller
{
    public function vote(Request $request, $id)
    {
        $poll = Poll::findOrFail($id);
        $optionId = $request->input('option_id');

        if (!$optionId) {
            return response()->json([
                'success' => false,
                'message' => 'একটি বিকল্প নির্বাচন করুন।'
            ], 400);
        }

        // Check if voted already
        $cookieName = 'voted_poll_' . $id;
        if ($request->cookie($cookieName) || session()->has($cookieName)) {
            return response()->json([
                'success' => false,
                'message' => 'আপনি ইতিমধ্যে ভোট দিয়েছেন!'
            ], 422);
        }

        $option = PollOption::where('poll_id', $poll->id)->where('id', $optionId)->firstOrFail();
        $option->increment('vote_count');
        $poll->increment('total_votes');

        // Track vote in session
        session()->put($cookieName, true);

        // Fetch updated options to calculate percentages
        $poll->load('options');
        
        $optionsData = [];
        foreach ($poll->options as $opt) {
            $pct = $poll->total_votes > 0 ? round(($opt->vote_count / $poll->total_votes) * 100) : 0;
            $optionsData[] = [
                'id' => $opt->id,
                'option_text' => $opt->option_text,
                'vote_count' => $opt->vote_count,
                'pct' => $pct,
                'pct_bn' => BengaliHelper::toBengaliNumerals($pct) . '%'
            ];
        }

        $totalVotesBn = BengaliHelper::toBengaliNumerals($poll->total_votes);

        return response()->json([
            'success' => true,
            'total_votes' => $poll->total_votes,
            'total_votes_bn' => $totalVotesBn . ' ভোট',
            'options' => $optionsData
        ])->withCookie(cookie()->forever($cookieName, 'true'));
    }
}
