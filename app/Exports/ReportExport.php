<?php

namespace App\Exports;

use App\Models\BookLoanItem;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Database\Eloquent\Builder;

class ReportExport implements FromView
{
    protected $from;
    protected $to;

    public function __construct($from = null, $to = null)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function view(): View
    {
        $query = BookLoanItem::query()
            ->with(['book', 'bookLoan.member', 'bookLoan.user']);

        if ($this->from) {
            $query->whereHas('bookLoan', fn($q) => $q->whereDate('loan_date', '>=', $this->from));
        }

        if ($this->to) {
            $query->whereHas('bookLoan', fn($q) => $q->whereDate('loan_date', '<=', $this->to));
        }

        return view('exports.reports', [
            'items' => $query->get(),
            'from' => $this->from,
            'to' => $this->to,
        ]);
    }
}
