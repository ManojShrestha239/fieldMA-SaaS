<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;

class DealervisitReport implements FromView
{
    /**
     * @return Collection
     */
    public function collection()
    {
        //
    }

    /**
     * @return Illuminate\Contracts\View\View
     */
    protected $data;

    protected $dealer;

    protected $from;

    protected $to;

    public function __construct($data, $dealer, $from, $to)
    {
        $this->data = $data;
        $this->dealer = $dealer;
        $this->from = $from;
        $this->to = $to;
    }

    public function view(): View
    {
        return view('dashboards.dealervisit-reports.dealer-visit-report', [
            'list' => $this->data,
            'dealer' => $this->dealer,
            'from' => $this->from,
            'to' => $this->to,
        ]);
    }
}
