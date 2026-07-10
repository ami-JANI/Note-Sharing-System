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

    public function history(Request $request)
    {
        $user = $request->user();

        $purchases = $user->purchases()->with('note')->latest()->get()->map(function ($p) {
            return [
                'type' => 'spend',
                'amount' => -$p->credits_spent,
                'description' => 'Unlocked: ' . ($p->note->title ?? 'Unknown'),
                'date' => $p->created_at,
            ];
        });

        $payments = $user->payments()->latest()->get()->map(function ($p) {
            return [
                'type' => 'buy',
                'amount' => $p->credits_purchased,
                'description' => 'Purchased credits',
                'date' => $p->created_at,
            ];
        });

        $transactions = $purchases->concat($payments)->sortByDesc('date')->values();

        return view('credits.history', compact('transactions'));
    }
}
