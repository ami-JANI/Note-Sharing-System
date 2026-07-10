<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class CreditController extends Controller
{
    public function purchase(Request $request)
    {
        $validated = $request->validate([
            'amount' => ['required', 'integer', 'min:1', 'max:10000'],
        ]);

        $user = $request->user();

        Payment::create([
            'user_id' => $user->id,
            'credits_purchased' => $validated['amount'],
            'status' => 'completed',
        ]);

        $user->increment('credits', $validated['amount']);

        return back()->with('status', 'Credits purchased.');
    }
}
