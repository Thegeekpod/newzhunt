<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:newsletters,email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'সঠিক ইমেল ঠিকানা দিন বা এটি ইতিমধ্যেই সাবস্ক্রাইব করা আছে।'
            ], 422);
        }

        Newsletter::create([
            'name' => $request->name,
            'email' => $request->email
        ]);

        return response()->json([
            'success' => true,
            'message' => '✓ সাবস্ক্রাইব সফল হয়েছে!'
        ]);
    }
}
