<?php

namespace App\Http\Controllers;

use App\Models\NotePurchase;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CreditController extends Controller
{
    public function create(): View
    {
        return view('credits.buy');
    }

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

        $purchased = $user->payments()->get()->map(fn ($payment) => (object) [
            'type' => 'purchase',
            'amount' => $payment->credits_purchased,
            'description' => 'Purchased credits',
            'created_at' => $payment->created_at,
        ]);

        $spent = $user->purchases()->with('note')->get()->map(fn ($purchase) => (object) [
            'type' => 'spent',
            'amount' => $purchase->credits_spent,
            'description' => 'Unlocked: '.($purchase->note->title ?? 'a note'),
            'created_at' => $purchase->created_at,
        ]);

        $earned = NotePurchase::whereHas('note', fn ($query) => $query->where('uploader_id', $user->id))
            ->with('note')
            ->get()
            ->map(fn ($purchase) => (object) [
                'type' => 'earned',
                'amount' => (int) round($purchase->credits_spent * 0.1),
                'description' => 'Someone unlocked: '.($purchase->note->title ?? 'your note'),
                'created_at' => $purchase->created_at,
            ]);

        $transactions = $purchased->concat($spent)->concat($earned)->sortByDesc('created_at')->values();

        return view('credits.history', compact('transactions'));
    }
}
