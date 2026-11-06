<?php

namespace App\Exports;

use App\Traits\Nepali_Calendar;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;

class CollectionSummaryReport implements FromView
{
    /**
     * @return Collection
     */
    use Nepali_Calendar;

    protected $payments;

    protected $status;

    protected $from;

    protected $to;

    protected $user;

    public function __construct($payments, $status, $user, $from, $to)
    {
        $this->status = $status;
        $this->user = $user;
        $this->from = $from;
        $this->to = $to;
        $this->payments = $payments;
    }

    public function view(): View
    {
        // dd($this->data);
        return view('dashboards.payments.collectionSummaryReport', [
            'payments' => $this->payments,
            'from' => $this->from,
            'to' => $this->to,
            'user' => $this->user,
        ]);
    }
}
