<?php

namespace App\Http\Controllers;

use App\Models\BookLoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function history()
    {
        $user = Auth::user();

        // Jika bukan role member, munculkan alert
        if ($user->role !== 'member') {
            return redirect()->back()->with('not_member', true);
        }

        // Ambil member terkait
        $member = $user->member;

        if (!$member) {
            return redirect()->back()->with('not_member', true);
        }

        $bookLoans = BookLoan::with(['items.book'])
            ->where('member_id', $member->id)
            ->latest()
            ->get();

        return view('pages.history.index', compact('bookLoans'));
    }
}
