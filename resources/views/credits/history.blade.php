<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 28px; color: rgb(27, 42, 74); letter-spacing: -0.02em;">
                Credit History
            </h2>
        </div>
    </x-slot>

    <div style="padding: 48px 0;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" style="max-width: 720px; margin: 0 auto;">

            {{-- Balance Summary --}}
            <div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 16px; padding: 28px; margin-bottom: 24px;">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <p style="font-size: 14px; color: rgb(91, 104, 133);">Current Balance</p>
                        <p style="font-family: 'Source Serif 4', serif; font-weight: 700; font-size: 32px; color: rgb(27, 42, 74); margin-top: 4px;">{{ number_format(Auth::user()->credits ?? 0) }} <span style="font-size: 15px; font-weight: 500; color: rgb(91, 104, 133);">credits</span></p>
                    </div>
                    <a href="{{ route('credits.buy') }}"
                       style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: rgb(138, 28, 36); color: rgb(251, 248, 243); border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none; transition: background 0.15s;"
                       onmouseover="this.style.background='rgb(110, 20, 27)'" onmouseout="this.style.background='rgb(138, 28, 36)'">
                        <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Buy Credits
                    </a>
                </div>
            </div>

            {{-- Transaction Table --}}
            <div style="background: white; border: 1px solid rgba(27, 42, 74, 0.1); border-radius: 16px; padding: 28px;">
                <h3 style="font-family: 'Source Serif 4', serif; font-weight: 600; font-size: 20px; color: rgb(27, 42, 74); margin-bottom: 20px;">Transactions</h3>

                @if ($transactions->isEmpty())
                    <div style="text-align: center; padding: 48px 0;">
                        <svg style="margin: 0 auto; width: 48px; height: 48px; color: rgb(91, 104, 133);" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                        <h3 style="font-family: 'Source Serif 4', serif; font-weight: 600; font-size: 18px; color: rgb(27, 42, 74); margin-top: 16px;">No transactions yet</h3>
                        <p style="font-size: 14px; color: rgb(91, 104, 133); margin-top: 6px;">Your credit history will appear here.</p>
                    </div>
                @else
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="border-bottom: 1px solid rgba(27, 42, 74, 0.08);">
                                    <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: rgb(91, 104, 133); text-transform: uppercase; letter-spacing: 0.05em;">Date</th>
                                    <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: rgb(91, 104, 133); text-transform: uppercase; letter-spacing: 0.05em;">Type</th>
                                    <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: rgb(91, 104, 133); text-transform: uppercase; letter-spacing: 0.05em;">Description</th>
                                    <th style="padding: 12px 16px; text-align: right; font-size: 12px; font-weight: 600; color: rgb(91, 104, 133); text-transform: uppercase; letter-spacing: 0.05em;">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr style="border-bottom: 1px solid rgba(27, 42, 74, 0.06);">
                                        <td style="padding: 14px 16px; font-size: 14px; color: rgb(91, 104, 133);">
                                            {{ $transaction->created_at->format('M d, Y') }}
                                        </td>
                                        <td style="padding: 14px 16px;">
                                            @if ($transaction->type === 'purchase' || $transaction->type === 'earned')
                                                <span style="display: inline-flex; padding: 3px 10px; border-radius: 100px; font-size: 12px; font-weight: 600; background: rgba(46, 125, 79, 0.08); color: rgb(46, 125, 79);">
                                                    Credit
                                                </span>
                                            @elseif ($transaction->type === 'unlock' || $transaction->type === 'spent')
                                                <span style="display: inline-flex; padding: 3px 10px; border-radius: 100px; font-size: 12px; font-weight: 600; background: rgba(180, 30, 30, 0.06); color: rgb(180, 30, 30);">
                                                    Debit
                                                </span>
                                            @else
                                                <span style="display: inline-flex; padding: 3px 10px; border-radius: 100px; font-size: 12px; font-weight: 600; background: rgba(27, 42, 74, 0.06); color: rgb(91, 104, 133);">
                                                    {{ ucfirst($transaction->type) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td style="padding: 14px 16px; font-size: 14px; color: rgb(27, 42, 74);">
                                            {{ $transaction->description ?? '—' }}
                                        </td>
                                        <td style="padding: 14px 16px; text-align: right; font-size: 14px; font-weight: 600;">
                                            @if (in_array($transaction->type, ['purchase', 'earned']))
                                                <span style="color: rgb(46, 125, 79);">+{{ $transaction->amount }}</span>
                                            @else
                                                <span style="color: rgb(180, 30, 30);">-{{ $transaction->amount }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
