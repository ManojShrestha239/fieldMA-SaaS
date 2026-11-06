<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;

class OrderCollectionExport implements FromView
{
    /**
     * @return Collection
     */
    protected $data;

    protected $dealer;

    protected $from;

    protected $to;

    protected $month;

    protected $year;

    public function __construct($data, $dealer, $from, $to, $month, $year)
    {
        $this->data = $data;
        $this->dealer = $dealer;
        $this->from = $from;
        $this->to = $to;
        $this->month = $month;
        $this->year = $year;
    }

    public function view(): View
    {

        return view('dashboards.orders.ordercollectionReport', [

            'list' => $this->data,
            'dealer' => $this->dealer,
            'from' => $this->from,
            'to' => $this->to,
            'month' => $this->month,
            'year' => $this->year,
            // dd($this->year)
        ]);
    }
}
